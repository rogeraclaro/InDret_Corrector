# Codebase Structure

**Analysis Date:** 2026-03-05

## Directory Layout

The repository root is a WordPress theme. The Python corrector project lives entirely inside `articles_correc/`.

```
indret/                               # WordPress theme root (git repo)
├── articles_correc/                  # Python corrector project (primary focus)
│   ├── corrector.py                  # Main script — all correction logic (1607 lines)
│   ├── debug_h1.py                   # Debug utility — inspect h1 paragraph XML
│   ├── plantilla.docx                # InDret template with {{MARKER}} placeholders
│   ├── plantilla_original_backup.docx # Backup before marker editing
│   ├── plantilla_pre_labels_backup.docx # Intermediate backup
│   ├── libro_estilo.pdf              # Official InDret style guide (7 pages)
│   ├── CLI.md                        # CLI usage reference
│   ├── indret-corrector.md           # Detailed project state and architecture notes
│   ├── instruccions.md               # Setup instructions for new machine
│   ├── MEMORY.md                     # Project memory (mirrored from .claude/memory)
│   ├── plan.md                       # Project plan with user answers
│   ├── context.md                    # General InDret context
│   ├── tipos/                        # Required font files (TTF)
│   │   ├── PTSerif-Regular.ttf
│   │   ├── PTSerif-Bold.ttf
│   │   ├── PTSerif-Italic.ttf
│   │   ├── PTSerif-BoldItalic.ttf
│   │   ├── OpenSans-VariableFont_wdth,wght.ttf
│   │   └── OpenSans-Italic-VariableFont_wdth,wght.ttf
│   ├── articles/                     # Test article collection
│   │   ├── article.docx              # Primary test article
│   │   ├── article_mal.docx          # Badly-formatted reference article
│   │   ├── article_mal2.docx         # Second bad article
│   │   ├── article_be2.docx          # Well-formatted reference article
│   │   ├── article_corregit.docx     # Generated output (corrected)
│   │   ├── article_mal_corregit.docx # Generated output
│   │   ├── article_mal_informe.md    # Generated report
│   │   └── corregir.docx             # Working test article
│   ├── article_mal.docx              # Root-level test article (active)
│   ├── article_be.docx               # Root-level reference article
│   ├── article_mal_corregit.docx     # Root-level generated output
│   ├── article_mal_informe.md        # Root-level generated report
│   └── article_mal_guia_correcio.odt # Manual correction guide (ODT)
├── .planning/                        # GSD planning documents
│   └── codebase/                     # Codebase analysis documents
├── .claude/                          # Claude project memory
├── css/                              # WordPress theme CSS
├── js/                               # WordPress theme JS
├── inc/                              # WordPress theme PHP includes
├── img/                              # WordPress theme images
├── layouts/                          # WordPress theme layout templates
├── template-parts/                   # WordPress theme template parts
├── pdf/                              # WordPress theme PDF assets
├── tipos/                            # WordPress theme fonts (top-level copy)
├── tests/                            # WordPress theme tests
└── languages/                        # WordPress theme i18n
```

## Directory Purposes

**`articles_correc/` — Project root:**
- Purpose: Contains all Python corrector code, assets, and test documents
- Contains: `corrector.py` (main), `debug_h1.py` (debug tool), `plantilla.docx` (template), `libro_estilo.pdf` (style spec), documentation `.md` files, fonts, test articles
- Key files: `articles_correc/corrector.py`, `articles_correc/plantilla.docx`, `articles_correc/libro_estilo.pdf`

**`articles_correc/tipos/` — Font assets:**
- Purpose: TTF font files required for typographic validation and specification reference (PT Serif + Open Sans). Not loaded by script — used as reference for the document template
- Contains: 6 TTF files (PT Serif 4 variants, Open Sans 2 variants)
- Generated: No
- Committed: Yes

**`articles_correc/articles/` — Test document archive:**
- Purpose: Collection of test `.docx` files and their generated outputs for development testing
- Contains: Input articles (`article_mal.docx`, `article.docx`, etc.) and script-generated outputs (`_corregit.docx`, `_informe.md`)
- Generated: Outputs are generated; inputs are hand-prepared test fixtures
- Committed: Yes (both inputs and outputs)

**`articles_correc/__pycache__/` — Python bytecode:**
- Purpose: Auto-generated Python bytecode cache
- Generated: Yes
- Committed: Should not be (no `.gitignore` entry observed for it)

## Key File Locations

**Entry Point:**
- `articles_correc/corrector.py` line 1554 — `main()` function, CLI entry point

**Core Classes:**
- `articles_correc/corrector.py` line 323 — `MetadataExtractor` class
- `articles_correc/corrector.py` line 859 — `Report` class
- `articles_correc/corrector.py` line 890 — `InDretCorrector` class

**Helper Functions (XML/typographic):**
- `articles_correc/corrector.py` lines 61–196 — paragraph classification and OOXML manipulation

**ZIP/Footnote Injection:**
- `articles_correc/corrector.py` lines 199–320 — post-save ZIP-level footnote transplant

**Template Filling:**
- `articles_correc/corrector.py` lines 498–820 — `{{MARKER}}` substitution and body injection

**Constants and Regex:**
- `articles_correc/corrector.py` lines 19–58 — all shared constants

**Debug Utility:**
- `articles_correc/debug_h1.py` — standalone script; imports `classify_para` and `get_heading_level` from `corrector.py` via `sys.path` injection

**Template:**
- `articles_correc/plantilla.docx` — InDret Word template with `{{MARKER}}` placeholders

**Style Reference:**
- `articles_correc/libro_estilo.pdf` — authoritative typographic specification (7 pages)

**Documentation:**
- `articles_correc/CLI.md` — CLI flags reference
- `articles_correc/indret-corrector.md` — full project state, architecture notes, known issues
- `articles_correc/instruccions.md` — machine setup instructions

## Naming Conventions

**Files:**
- Main script: `corrector.py` (lowercase, descriptive)
- Debug scripts: `debug_<subject>.py`
- Generated outputs: `<input_stem>_corregit.docx` and `<input_stem>_informe.md` (same directory as input)
- Backups: `<original>_backup.docx` suffix

**Functions:**
- Phase methods: `_phase1_text()`, `_phase2_styles()` etc. — underscore prefix, numbered sequence
- Private helpers: underscore prefix (`_fix_header_alignment`, `_merge_footnotes_xml`)
- Public helpers: no prefix (`classify_para`, `get_heading_level`, `is_likely_surname`)
- Regex constants: `RE_` prefix all-caps (`RE_H1`, `RE_JURIS`, `RE_CAPS`)
- Font constants: `FONT_SERIF`, `FONT_SANS`
- Keyword sets: descriptive all-caps (`BIB_KEYWORDS`, `LEGAL_ABBR`, `NO_NUMBER_HEADINGS`)

**Template markers:**
- Double curly braces, all-caps Catalan names: `{{TITOL}}`, `{{AUTOR}}`, `{{SUMARI}}`, `{{ABSTRACT}}`, `{{TITOL_EN}}`, `{{PARAULES_CLAU}}`, `{{KEYWORDS}}`, `{{ORGANITZACIO}}`, `{{SUBTITOL}}`, `{{EDICIO}}`, `{{DOI}}`, `{{INDEX}}`

## Where to Add New Code

**New correction phase:**
- Add `_phaseN_<name>(self)` method to `InDretCorrector` in `articles_correc/corrector.py`
- Call it from `run()` and/or `template_run()` in sequence
- Log results via `self.report.ok()` or `self.report.warn()`

**New template marker:**
- Add `{{MARKER}}` to `plantilla.docx` manually
- Add extraction logic to `MetadataExtractor.extract()` in `articles_correc/corrector.py`
- Add entry to the `markers` dict in `InDretCorrector.template_run()` (around line 1496)
- If multiline: use `_handle_multiline_marker()` instead of `_fill_template_markers()`

**New regex pattern:**
- Add to the `# ─── Patrons regex ───` section at lines 35–47 in `articles_correc/corrector.py`
- Use `re.compile()` with `RE_` prefix naming

**New CLI flag:**
- Add `parser.add_argument()` call in `main()` at line 1554
- Pass value through to `InDretCorrector` constructor or `template_run()`

**New test article:**
- Place `.docx` in `articles_correc/articles/` for archiving, or directly in `articles_correc/` for active testing
- Generated `_corregit.docx` and `_informe.md` will appear in same directory as input

**New debug utility:**
- Create `articles_correc/debug_<subject>.py`
- Import from `corrector.py` using `sys.path.insert(0, '.')` pattern (see `debug_h1.py`)

## Special Directories

**`articles_correc/__pycache__/`:**
- Purpose: Python bytecode cache for `corrector.py`
- Generated: Yes (automatic on import/run)
- Committed: Yes (should be gitignored)

**`.planning/codebase/`:**
- Purpose: GSD codebase analysis documents
- Generated: Yes (by GSD map-codebase command)
- Committed: Yes

**`.claude/`:**
- Purpose: Claude project memory and conversation context
- Generated: Yes (auto-managed by Claude)
- Committed: No (not in git)

---

*Structure analysis: 2026-03-05*
