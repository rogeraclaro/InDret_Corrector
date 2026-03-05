# Technology Stack

**Analysis Date:** 2026-03-05

## Languages

**Primary:**
- Python 3.13 - All corrector logic in `articles_correc/corrector.py`

**Secondary:**
- PHP 8.x - WordPress theme templates (`.php` files at theme root, e.g. `functions.php`, `single.php`)
- CSS - Theme styling (`style.css`, `tipos.css`)
- JavaScript - Theme frontend (`js/`)

> Note: The Python corrector is the active development project. The PHP/CSS/JS layer is an existing WordPress theme not under active development.

## Runtime

**Environment:**
- macOS (development), target: Linux web server (future)
- Python installed via python.org framework at `/Library/Frameworks/Python.framework/Versions/3.13/`

**Package Manager:**
- pip3 (system-level, no virtualenv)
- Lockfile: Not present — packages installed globally, no `requirements.txt` or `pyproject.toml`

## Frameworks

**Core:**
- python-docx 1.2.0 - Read, modify, and write `.docx` files (OOXML manipulation)
- lxml 6.0.2 - Low-level XML manipulation of OOXML internals (run properties, bookmarks, hyperlinks, footnotes)

**NLP (installed but not imported in corrector.py v2):**
- spaCy 3.8.11 + `es_core_news_lg` 3.8.0 - Originally planned for NER-based surname detection; replaced by heuristic `is_likely_surname()` function in current implementation

**Build/Dev:**
- No build system — single-file script, run directly with `python3 corrector.py`

## Key Dependencies

**Critical:**
- `python-docx` 1.2.0 — Core dependency; all document read/write operations go through this library. Used via `from docx import Document` and `from docx.shared import Pt, Cm`
- `lxml` 6.0.2 — Required by python-docx and used directly (`from lxml import etree as _lxml_et`) for XML surgery on footnotes, styles, numbering, and OOXML elements that python-docx does not expose via its API
- `zipfile` (stdlib) — Used to extract and repack `.docx` ZIP archive for footnote injection (python-docx does not support footnotes natively)

**Standard Library (all stdlib, no extra install):**
- `sys`, `re`, `os`, `argparse` - CLI and string processing
- `zipfile`, `io` - ZIP manipulation for `.docx` internals
- `copy.deepcopy` - Deep-copying XML elements
- `datetime`, `pathlib` - Timestamps and path handling

**Infrastructure:**
- `spaCy` 3.8.11 — Installed but not used in current `corrector.py`; retained for potential future semantic features

## Configuration

**Environment:**
- No `.env` file required — script is fully offline, no external services
- All configuration is passed via CLI arguments at runtime:
  - `--edicio` — edition number (e.g. `"2/2025"`)
  - `--doi` — DOI identifier (e.g. `"10.31009/InDret.2025.i2.03"`)
  - `--plantilla` — alternative template path (default: `plantilla.docx` next to script)
  - `--sense-plantilla` — classic mode, skip template entirely

**Build:**
- No build config files
- Shebang: `#!/usr/bin/env python3` — executable directly on Unix

## Assets

**Template:**
- `articles_correc/plantilla.docx` — Official InDret Word template with `{{PLACEHOLDER}}` markers for content injection
- `articles_correc/plantilla_original_backup.docx` — Backup of original template before markers were added

**Fonts (TTF, bundled):**
- `articles_correc/tipos/PTSerif-Regular.ttf`
- `articles_correc/tipos/PTSerif-Bold.ttf`
- `articles_correc/tipos/PTSerif-Italic.ttf`
- `articles_correc/tipos/PTSerif-BoldItalic.ttf`
- `articles_correc/tipos/OpenSans-VariableFont_wdth,wght.ttf`
- `articles_correc/tipos/OpenSans-Italic-VariableFont_wdth,wght.ttf`

> Fonts are bundled as TTF files. They must be installed in the OS font system for Word/LibreOffice to render them correctly. The script references them by name string (`"PT Serif"`, `"Open Sans"`) inside the OOXML — it does not embed them programmatically.

## Platform Requirements

**Development:**
- macOS with Python 3.13 installed via python.org
- `pip3 install python-docx lxml` (spacy optional)
- Input/output: `.docx` files only; no `.doc` support

**Production (planned):**
- Web server (Linux), triggered via web form at `indret.com/formulario/`
- Execution: one article at a time (batch not supported)
- Future possibility: Gemini API integration as optional semantic layer

---

*Stack analysis: 2026-03-05*
