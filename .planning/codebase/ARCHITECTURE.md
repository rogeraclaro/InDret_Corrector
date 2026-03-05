# Architecture

**Analysis Date:** 2026-03-05

## Pattern Overview

**Overall:** Single-file procedural + OOP pipeline CLI tool

**Key Characteristics:**
- All logic lives in one file: `articles_correc/corrector.py` (1607 lines)
- Two execution modes: classic (direct correction) and template-based
- Sequential 5-phase pipeline executed by `InDretCorrector`
- No external API calls, no LLM — fully offline regex + python-docx + lxml

## Layers

**Constants and Regex Layer:**
- Purpose: Define typographic rules, legal abbreviation sets, and compiled patterns
- Location: `articles_correc/corrector.py` lines 19–58
- Contains: `FONT_SERIF`, `FONT_SANS`, `LEGAL_ABBR`, `BIB_KEYWORDS`, `INDEX_KEYWORDS`, `NO_NUMBER_HEADINGS`, all `RE_*` compiled regex
- Depends on: Nothing (pure constants)
- Used by: All processing functions and class methods

**XML/Typographic Helpers Layer:**
- Purpose: Low-level OOXML manipulation reused across phases
- Location: `articles_correc/corrector.py` lines 61–196
- Contains: `get_heading_level()`, `classify_para()`, `is_likely_surname()`, `set_run_font()`, `set_small_caps_xml()`, `set_line_spacing()`, `add_bookmark()`, `add_internal_hyperlink()`
- Depends on: `python-docx`, `lxml`, constants layer
- Used by: `InDretCorrector` phases and ZIP helpers

**ZIP/Footnote Injection Helpers:**
- Purpose: Transplant footnotes from article into output docx by manipulating the ZIP structure directly (python-docx does not support footnote merging natively)
- Location: `articles_correc/corrector.py` lines 199–320
- Contains: `_ensure_footnotes_rel()`, `_ensure_footnotes_content_type()`, `_merge_footnotes_xml()`, `_fix_footnote_style_spacing()`, `_inject_footnotes()`
- Depends on: `zipfile`, `lxml`, `io`
- Used by: `InDretCorrector.template_run()` post-save

**MetadataExtractor Class:**
- Purpose: Parse article structure using a state machine to extract cover-page fields
- Location: `articles_correc/corrector.py` lines 323–495
- Contains: `MetadataExtractor.extract()`, `_detect_index_level()`, `_refine_index_levels()`, `_is_metadata_line()`
- Depends on: helpers layer, python-docx
- Used by: `InDretCorrector.template_run()` (not used in classic `run()`)

**Template Filling Helpers:**
- Purpose: Substitute `{{MARKER}}` placeholders in template.docx paragraphs, tables, and headers
- Location: `articles_correc/corrector.py` lines 498–820
- Contains: `_replace_in_para()`, `_fix_header_alignment()`, `_fill_template_markers()`, `_fix_cover_labels()`, `_handle_multiline_marker()`, `_copy_numbering_from_article()`, `_build_numformat_map()`, `_copy_index_to_template()`, `_append_body_to_template()`
- Depends on: python-docx, lxml, XML helpers
- Used by: `InDretCorrector.template_run()`

**Report Class:**
- Purpose: Collect correction log entries and render a Markdown report file
- Location: `articles_correc/corrector.py` lines 859–886
- Contains: `Report.ok()`, `Report.warn()`, `Report.to_markdown()`
- Depends on: `datetime`
- Used by: `InDretCorrector` (one instance per run)

**InDretCorrector Class:**
- Purpose: Orchestrate the full correction pipeline; owns the article document and report
- Location: `articles_correc/corrector.py` lines 890–1550
- Contains: `run()` (classic mode), `template_run()` (template mode), `_phase1_text()` through `_phase5_checks()`, footnote processing methods, `_save()`, `_save_doc()`
- Depends on: all helpers and classes above
- Used by: `main()`

**Entry Point (`main`):**
- Purpose: CLI argument parsing and mode dispatch
- Location: `articles_correc/corrector.py` lines 1553–1607
- Contains: `argparse` setup, mode selection, `InDretCorrector` instantiation

## Data Flow

**Template Mode (default):**

1. CLI parses `article`, `--edicio`, `--doi`, `--plantilla` args → `main()`
2. `InDretCorrector(article_path)` loads article `.docx` via `Document()`
3. Phase 1 (`_phase1_text`): iterates all runs, fixes double spaces and curly quotes
4. Phase 2 (`_phase2_styles`): classifies each paragraph via `classify_para()`, applies fonts/sizes/spacing per type (h1/h2/h3/h4/body/bib)
5. Phase 3 (`_phase3_small_caps`): detects CAPS runs via `is_likely_surname()`, converts to title case + `<w:smallCaps/>` XML on body paragraphs and footnotes
6. Phase 4 (metadata, template mode only): `MetadataExtractor.extract()` runs a state machine over paragraphs extracting title, author, sumari, abstract, keywords, index entries
7. Phase 5 (`_phase5_checks`): validates jurisprudence citations (ECLI/Roj), checks for op. cit. in footnotes, warns on missing abstract/keywords
8. Template loaded via `Document(plantilla_path)`, markers `{{X}}` substituted via `_fill_template_markers()`
9. Index paragraphs deep-copied from article into template at `{{INDEX}}` marker
10. Article body appended to template body via `_append_body_to_template()`
11. Template saved to `<stem>_corregit.docx`; footnotes injected via ZIP manipulation
12. Report written to `<stem>_informe.md`

**Classic Mode (`--sense-plantilla`):**

1–3 same as above, phases 1–5 run on article directly
4. `_save()` writes corrected article + report without using template

**State Management:**
- No persistent state between runs; all state is held in `InDretCorrector` instance variables (`self.doc`, `self.report`, `self._headings`)
- `MetadataExtractor` is stateless (pure `extract()` method returns a dict)
- `Report` accumulates lists in memory, rendered to string at save time

## Key Abstractions

**`classify_para(para) -> str`:**
- Purpose: Determine a paragraph's semantic role
- Examples: `articles_correc/corrector.py` line 72
- Pattern: Returns `'h1'`/`'h2'`/`'h3'`/`'h4'`/`'body'`/`'empty'`/`'bib_keyword'`/`'index_keyword'`. Priority: known keywords → regex heading detection → Word style name → bold+short fallback → `'body'`

**`is_likely_surname(text) -> bool`:**
- Purpose: Heuristic to identify UPPERCASE author surnames for small-caps conversion
- Examples: `articles_correc/corrector.py` line 97
- Pattern: Must be all-caps, length ≥ 4 (or contain space), not in `LEGAL_ABBR` frozenset

**`MetadataExtractor.extract(doc) -> dict`:**
- Purpose: Parse article front matter via state machine
- Examples: `articles_correc/corrector.py` line 379
- Pattern: States: `post_title` → `sumari` → `abstract` → `kw_es`/`kw_en` → `index` → `body`. Returns structured dict with all cover-page fields plus `body_start_idx`/`body_end_idx`

**`{{MARKER}}` Template System:**
- Purpose: Placeholder substitution in `plantilla.docx`
- Examples: `articles_correc/corrector.py` line 571
- Pattern: Markers are literal strings like `{{TITOL}}`, `{{AUTOR}}`, `{{SUMARI}}`, `{{ABSTRACT}}`, `{{INDEX}}`, `{{EDICIO}}`, `{{DOI}}`. Replaced by scanning all paragraphs, table cells, and header/footer sections

## Entry Points

**`main()` function:**
- Location: `articles_correc/corrector.py` line 1554
- Triggers: `python3 corrector.py <article.docx> [--edicio X] [--doi Y] [--plantilla Z] [--sense-plantilla]`
- Responsibilities: Validate input file exists, auto-detect `plantilla.docx` sibling, dispatch to `corrector.template_run()` or `corrector.run()`

## Error Handling

**Strategy:** Lenient — errors in optional processing (footnotes, numbering, spaCy) are caught and logged as `Report.warn()` entries; the run continues and still produces output

**Patterns:**
- `try/except Exception as e: self.report.warn(f"...")` for optional features (footnote injection, numbering fix, small-caps footnotes)
- Hard exit only on missing input file (`sys.exit(1)`)
- Missing metadata fields issue warnings but are replaced by placeholder strings (e.g., `'Nombre Autor'`)

## Cross-Cutting Concerns

**Logging:** `print()` to stdout for phase progress; `Report` class for structured correction log written to `_informe.md`

**Validation:** Phase 5 validates citation style and required sections; issues go to `Report.warn()` not exceptions

**Authentication:** None — fully local, no network access

**Document Safety:** Input file is never modified; all output goes to new `_corregit.docx` file. Template is loaded fresh each run from `plantilla.docx`

---

*Architecture analysis: 2026-03-05*
