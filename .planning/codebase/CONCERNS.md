# Codebase Concerns

**Analysis Date:** 2026-03-05

---

## Tech Debt

**Incomplete template-based workflow:**
- Issue: The new `template_run()` flow is implemented but the `plantilla.docx` still has unresolved placeholder problems documented in `indret-corrector.md`. Specifically: `{{ABSTRACT}}` placeholder currently shows `{{SUMARI}}` content because the script does not yet distinguish them correctly, and `{{EDICIO}}` is not substituted in the header because the header XML structure has complex runs that the generic `_fill_template_markers()` does not handle without first calling `_fix_header_alignment()`. There is also uncertainty about whether `{{EDICIO}}` vs `{EDICIO}` appears in column 0 of the cover table.
- Files: `articles_correc/corrector.py` (lines 1490–1530), `articles_correc/plantilla.docx`
- Impact: Every article processed with the template flow may silently produce a cover page with placeholder text left unreplaced, requiring manual fix by the editor.
- Fix approach: Inspect the exact run structure of `plantilla.docx` programmatically (use `debug_h1.py` as a model), then correct the XML before `_fill_template_markers()` runs, or rework the abstract/sumari distinction in `MetadataExtractor.extract()`.

**No virtualenv / system-level pip installs:**
- Issue: Dependencies (`python-docx`, `spacy`, `es_core_news_lg`) are installed directly into the system Python 3.13 via pip3, with no `requirements.txt` or `pyproject.toml`.
- Files: `articles_correc/corrector.py` (import block, line 8–17)
- Impact: Reproducing the environment on a new machine (or a server) requires tribal knowledge. There is no lockfile, so a `pip install python-docx` would pull a potentially incompatible future version.
- Fix approach: Add `requirements.txt` pinning `python-docx==1.2.0` and `lxml` version. Create a virtualenv in `articles_correc/venv/` and document activation in `CLI.md`.

**spaCy imported but not used:**
- Issue: `es_core_news_lg` is installed and was originally planned for NER-based surname detection, but was abandoned in favour of the regex heuristic `is_likely_surname()`. The import and the model are still listed as dependencies in `indret-corrector.md` but no `import spacy` appears in `corrector.py`. The model occupies ~600 MB on disk unnecessarily if kept installed.
- Files: `articles_correc/corrector.py`, `articles_correc/indret-corrector.md`
- Impact: Confusion for any future developer about whether spaCy is actually needed. Wasted disk space.
- Fix approach: Remove spaCy from the dependency list and document in `indret-corrector.md` that NER was replaced by the heuristic approach.

**Single monolithic 1606-line file:**
- Issue: All logic — XML helpers, ZIP manipulation, metadata extraction, five correction phases, template filling, CLI entrypoint — lives in one file. There are no modules, no separation of concerns.
- Files: `articles_correc/corrector.py`
- Impact: Difficult to test individual phases in isolation. A failure in ZIP injection (lines 292–319) can mask bugs in style application. Adding the planned web server interface will require refactoring.
- Fix approach: Extract `MetadataExtractor` and `Report` into separate files. Group ZIP/footnote helpers into a `footnotes.py` module. Keep `InDretCorrector` as the orchestrator.

**Output always written next to input:**
- Issue: `_save_doc()` at line 1546 always writes `<stem>_corregit.docx` to `self.path.parent`. There is no `--output` CLI argument.
- Files: `articles_correc/corrector.py` (lines 1545–1550)
- Impact: Running the corrector on a file in a read-only directory (or any path outside the working directory) will silently fail or write to an unexpected location. If the same article is processed twice, the previous output is silently overwritten.
- Fix approach: Add `--output` / `--output-dir` CLI argument. Warn before overwriting an existing output file.

---

## Known Bugs

**Author not detected for most real articles:**
- Symptoms: The informe for `article_mal.docx` shows `[!] Autor no detectat — s'ha inserit 'Nombre Autor' com a placeholder`. The extractor at lines 463–469 only captures author if they appear in a paragraph of fewer than 120 characters between the title and the first `RESUMEN:` label, and only if it is not a metadata line.
- Files: `articles_correc/corrector.py` (lines 463–469), `articles_correc/article_mal_informe.md`
- Trigger: Articles where the author block is formatted differently (e.g., author on same paragraph as affiliation, or preceded by a label like "Autor:"), or where the author paragraph is longer than 120 characters.
- Workaround: Editor must manually set `{{AUTOR}}` in the output document.

**Index end detection is fragile:**
- Symptoms: The index section end is detected as "the first paragraph longer than 120 characters" (line 1186). A long index entry or a multi-sentence abstract that appears before the body would break this heuristic.
- Files: `articles_correc/corrector.py` (lines 1183–1188)
- Trigger: Articles with unusually long index entries, or articles where the abstract is placed after the index.
- Workaround: None automatic. The body offset printed in the informe allows the editor to verify.

**Bookmark `+2` offset is incorrect under certain XML structures:**
- Symptoms: `add_bookmark()` at line 168 inserts `bm_end` at `idx_last + 2` to account for the `bm_start` just inserted. This is correct only when `bm_start` was inserted before `runs[0]`. If `runs[0]` is itself the first child, `idx_last + 2` may skip an element.
- Files: `articles_correc/corrector.py` (lines 154–169)
- Trigger: Paragraphs where the first run is the very first child of `<w:p>` (no `<w:pPr>`).
- Workaround: Bookmarks still render in Word; the off-by-one only affects which element falls inside the bookmark range.

**`_number_h1_headings()` is called nowhere in the active code path:**
- Symptoms: The method is defined at line 1407 but is never called from `run()` or `template_run()`. Automatic N1 numbering is therefore silently skipped.
- Files: `articles_correc/corrector.py` (lines 1407–1442, 1444–1599)
- Trigger: Any article with unnumbered N1 headings processed via either code path.
- Workaround: Editor must add numbering manually.

**`{{EDICIO}}` not substituted in header:**
- Symptoms: Documented as an open bug in `indret-corrector.md`. The header paragraph in `plantilla.docx` uses a complex multi-run structure; `_fill_template_markers()` only iterates `para.runs` but the marker may be split across runs or embedded in a field instruction.
- Files: `articles_correc/corrector.py` (lines 571–588), `articles_correc/plantilla.docx`, `articles_correc/indret-corrector.md` (lines 71–76)
- Trigger: Every run of `template_run()`.
- Workaround: `_fix_header_alignment()` was added as a partial mitigation but does not solve the substitution itself.

---

## Security Considerations

**Arbitrary file write via CLI argument:**
- Risk: `args.article` is passed directly to `Document(input_path)` and then `self.path.parent` is used to write outputs. A path like `../../etc/passwd.docx` would cause the output to be written to an unexpected directory. This is low risk for a local CLI tool but becomes a real risk if a web interface is added (planned).
- Files: `articles_correc/corrector.py` (lines 1573–1597)
- Current mitigation: OS-level file permissions on the local Mac.
- Recommendations: Validate that the input path resolves inside a designated working directory before processing, especially when the web server integration is implemented.

**No validation of `.docx` file contents:**
- Risk: `python-docx` parses the ZIP/XML of the uploaded file. A crafted `.docx` with a malicious XML entity (XML bomb / billion-laughs) could cause memory exhaustion. Relevant when moving to a server.
- Files: `articles_correc/corrector.py` (line 894: `Document(input_path)`)
- Current mitigation: Local use only. `lxml` has partial defences against entity expansion.
- Recommendations: Set `lxml` parser with `resolve_entities=False` and add a max file size check before parsing.

---

## Performance Bottlenecks

**Full document re-read as ZIP after python-docx save:**
- Problem: `_inject_footnotes()` at line 292 reads the entire saved `.docx` into a `BytesIO` buffer, iterates all ZIP members, writes a new ZIP to `BytesIO`, then writes it back to disk. For large articles this means the full file is held in memory twice.
- Files: `articles_correc/corrector.py` (lines 292–319)
- Cause: `python-docx` does not expose a direct API to replace `word/footnotes.xml` in-memory before saving; the ZIP post-processing is the only viable approach with the current stack.
- Improvement path: Acceptable for single articles on a local machine. On a server with concurrent users, add a temporary-file approach instead of in-memory `BytesIO` for files >10 MB.

**Linear scan of all paragraphs multiple times:**
- Problem: The corrector iterates `doc.paragraphs` independently in each of the five phases plus `MetadataExtractor.extract()`. A 200-paragraph article causes ~6 full linear scans.
- Files: `articles_correc/corrector.py` (phases 1–5, lines 912–1286)
- Cause: Architectural simplicity; each phase is self-contained.
- Improvement path: For current article sizes (100–300 paragraphs), negligible. If batch processing is introduced, a single-pass design would reduce wall time.

---

## Fragile Areas

**ZIP-level footnote injection (`_inject_footnotes`):**
- Files: `articles_correc/corrector.py` (lines 292–319, 1532–1537)
- Why fragile: Depends on exact ZIP member names (`word/footnotes.xml`, `word/_rels/document.xml.rels`, `[Content_Types].xml`). If a Word processor uses non-standard member paths or compresses with a different algorithm, the injection silently skips the footnotes.
- Safe modification: Always verify with `zin.namelist()` before assuming member existence (already done partially). Add an explicit warning to the report if `word/footnotes.xml` is absent after injection.
- Test coverage: No automated tests exist. Verified manually with `article_mal.docx` only.

**`classify_para()` bold-shortcut heuristic:**
- Files: `articles_correc/corrector.py` (lines 88–93)
- Why fragile: Any paragraph shorter than 120 characters where all runs happen to be bold — including a bold author name or a bold abstract label — will be misclassified as `h1`. This can cause an author or affiliation line to receive Open Sans 11 bold heading formatting.
- Safe modification: Only apply the bold-shortcut fallback if no other classification matched and the paragraph is not in the pre-title or metadata zone.
- Test coverage: None.

**`_merge_footnotes_xml()` replaces template separator notes:**
- Files: `articles_correc/corrector.py` (lines 228–252)
- Why fragile: The function removes all footnotes with `id >= 1` from the template and inserts article footnotes. If the template uses footnotes numbered 1+ for demo purposes that should be preserved, they will be deleted. Also, if the article footnote XML uses namespace prefixes different from those in the template root, lxml `tostring()` may produce invalid XML.
- Safe modification: After merge, validate the output XML with `lxml.etree.XMLSchema` against the OOXML schema, or at minimum check that the root element and namespace declarations are intact.
- Test coverage: None.

**Regex heading detection vs. numbered list paragraphs:**
- Files: `articles_correc/corrector.py` (lines 39–42, `get_heading_level()`)
- Why fragile: `RE_H1` matches `^\d+\.\s+\S`, which also matches any numbered list item starting with a digit and period (e.g., `"1. Lorem ipsum"`). A numbered body list item will be styled as a section heading.
- Safe modification: Check `para.style.name` and the presence of `w:numPr` before applying heading classification from regex alone.
- Test coverage: None.

---

## Scaling Limits

**Single-article, single-process design:**
- Current capacity: One article at a time, local Mac.
- Limit: No queuing, no temp-file isolation between runs. Two concurrent runs writing to the same directory would overwrite each other's `_corregit.docx` output.
- Scaling path: Add a UUID-based output directory per run (e.g., `output/<uuid>/`) before implementing the planned web server interface.

**Fonts not embedded, must be installed on target system:**
- Current capacity: Fonts in `articles_correc/tipos/` are present locally but `python-docx` does not install or embed fonts into the document; it only references them by name. If `PT Serif` or `Open Sans` are not installed on the machine where the `.docx` is opened, Word substitutes a fallback font.
- Limit: Any editor opening the output on a machine without those fonts will see incorrect rendering.
- Scaling path: Document font installation as a prerequisite in `CLI.md`. When moving to server, add a step to embed font references using the `w:embedTrueTypeFonts` document setting in the template.

---

## Dependencies at Risk

**`python-docx` private API usage (`._p`, `._r`, `._element`, `part.numbering_part._element`):**
- Risk: The entire script relies heavily on private python-docx internals. Any minor version bump of `python-docx` beyond 1.2.0 could rename or remove `._p`, `._r`, `part.footnotes_part`, or `part.numbering_part`.
- Files: `articles_correc/corrector.py` — pervasive throughout all phases and helpers.
- Impact: The corrector would silently fail or crash on a future pip update.
- Migration plan: Pin `python-docx==1.2.0` in a `requirements.txt` immediately. When upgrading, run the full manual test suite against `article_mal.docx` and `article_be.docx`.

**`lxml` used for raw XML manipulation without schema validation:**
- Risk: Direct construction of OOXML elements (e.g., `OxmlElement('w:rFonts')`, `OxmlElement('w:spacing')`) bypasses any validity checking. Malformed XML inserted into the document can cause Word to show a repair dialog on open.
- Files: `articles_correc/corrector.py` — throughout all helper functions.
- Impact: Corrupt output documents that require manual repair.
- Migration plan: Add a post-save validation step that opens the output `.docx` with `python-docx` and checks that key paragraphs are readable. A simple smoke-test script would catch most regressions.

---

## Missing Critical Features

**No automated tests of any kind:**
- Problem: There are no unit tests, integration tests, or regression snapshots. `debug_h1.py` is a manual inspection tool, not a test.
- Blocks: Safe refactoring, confident version upgrades, catching regressions between sessions.
- Files: `articles_correc/debug_h1.py` (only debugging aid present)

**No `requirements.txt` or environment spec:**
- Problem: The exact dependency versions are documented only in `indret-corrector.md` (prose), not in a machine-readable lockfile.
- Blocks: Reproducible setup on a new machine or server deployment.

**Subtítol extraction not implemented:**
- Problem: `data['subtitol']` is initialised to `''` in `MetadataExtractor.extract()` and is never populated. The `{{SUBTITOL}}` marker in the template is always replaced with an empty string.
- Files: `articles_correc/corrector.py` (line 384, line 1498)
- Blocks: Articles with subtitles will have the subtitle silently dropped from the cover page.

**Bibliography format checking not implemented:**
- Problem: `plan.md` defines Phase 2 tasks including bibliographic entry format detection (monograph vs. article vs. book chapter) and checking for correct italics on titles, correct use of `«»` for article titles, etc. None of this is implemented.
- Files: `articles_correc/corrector.py` (phase 5 only checks jurisprudence and op. cit.)
- Blocks: Editors must check bibliography format entirely manually.

---

## Test Coverage Gaps

**All correction phases uncovered:**
- What's not tested: `_phase1_text()`, `_phase2_styles()`, `_phase3_small_caps()`, `_phase4_index()`, `_phase5_checks()`, `template_run()`, `_inject_footnotes()`.
- Files: All of `articles_correc/corrector.py`
- Risk: A regression in any phase (e.g., font size silently set to wrong half-point value) would only be caught by the editor reviewing the final document.
- Priority: High

**`is_likely_surname()` heuristic uncovered:**
- What's not tested: The surname/abbreviation boundary logic that determines whether a CAPS run gets small-caps applied. False positives would corrupt legal abbreviations; false negatives would miss required conversions.
- Files: `articles_correc/corrector.py` (lines 97–113)
- Risk: Silent formatting errors in citations and bibliography.
- Priority: High

**`MetadataExtractor.extract()` uncovered:**
- What's not tested: Title, author, sumari, abstract, keywords, and index extraction from diverse real article formats.
- Files: `articles_correc/corrector.py` (lines 379–495)
- Risk: Silent extraction failures produce cover pages with placeholder text or wrong metadata.
- Priority: High

---

*Concerns audit: 2026-03-05*
