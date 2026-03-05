# Coding Conventions

**Analysis Date:** 2026-03-05

---

## Scope

This document covers `articles_correc/corrector.py` — the primary project. The broader WordPress theme (`functions.php`, `.php` templates, `js/`) follows its own conventions documented below as secondary context.

---

## Python Corrector (`articles_correc/corrector.py`)

### Language and Version

- Python 3.13 (type hints use `bytes | None` union syntax, requires 3.10+)
- Single-file architecture: all logic in one 1,607-line module

### Naming Patterns

**Files:**
- Snake_case: `corrector.py`, `debug_h1.py`
- Output files follow pattern `<stem>_corregit.docx`, `<stem>_informe.md`

**Functions (module-level):**
- Snake_case: `get_heading_level()`, `classify_para()`, `set_run_font()`, `set_small_caps_xml()`
- Private helpers prefixed with single underscore: `_ensure_footnotes_rel()`, `_merge_footnotes_xml()`, `_fix_footnote_style_spacing()`, `_inject_footnotes()`, `_fill_template_markers()`, `_copy_index_to_template()`
- Nested helper functions defined inline within their parent when tightly scoped (e.g., `_make_run()` inside `_fix_header_alignment()`, `_apply_style()` inside `_copy_index_to_template()`)

**Methods (class-level):**
- Public entry points: `run()`, `template_run()`, `extract()`
- Private phases prefixed with `_phase`: `_phase1_text()`, `_phase2_styles()`, `_phase3_small_caps()`, `_phase4_index()`, `_phase5_checks()`
- Private helpers prefixed with double-verb or noun: `_fix_numpr_font()`, `_apply_footnote_font()`, `_small_caps_paragraphs()`, `_get_max_existing_bookmark_id()`

**Constants:**
- SCREAMING_SNAKE_CASE: `FONT_SERIF`, `FONT_SANS`, `INDEX_INDENT`, `LEGAL_ABBR`, `BIB_KEYWORDS`, `NO_NUMBER_HEADINGS`
- Regex constants prefixed with `RE_`: `RE_DOUBLE_SPACE`, `RE_QUOT_OPEN`, `RE_H1`, `RE_JURIS`, `RE_ECLI`

**Variables:**
- Snake_case throughout
- Single-letter variables acceptable for short loops: `p`, `r`, `t`, `n`, `i`
- Descriptive local names for anything used beyond 2 lines: `existing_runs`, `bm_start`, `tmpl_root`

**Classes:**
- PascalCase: `MetadataExtractor`, `InDretCorrector`, `Report`
- Class-level regex constants use class attribute pattern (all caps): `RE_SUMARI`, `RE_ABSTRACT`, `RE_KW_ES`

### Code Style

**Formatting:**
- No formatter config file (no `.flake8`, `pyproject.toml`, or `.pre-commit-config.yaml`)
- Alignment via spaces for visual columns (cosmetic alignment of assignments):
  ```python
  FONT_SERIF = "PT Serif"
  FONT_SANS  = "Open Sans"
  ```
- Inline `if/return` on single line for simple guards:
  ```python
  if not text: return 'empty'
  if tl in BIB_KEYWORDS:   return 'bib_keyword'
  ```
- Long string blocks use f-strings, never `%` formatting or `.format()`

**Imports:**
- Stdlib and third-party mixed on first lines (not separated by blank lines):
  ```python
  import sys, re, os, argparse, zipfile, io
  from copy import deepcopy
  from datetime import datetime
  from pathlib import Path
  from docx import Document
  ```
- Comma-separated `import` for stdlib builtins; `from X import Y` for specific symbols
- `lxml.etree` aliased at import: `from lxml import etree as _lxml_et` (underscore alias signals internal use)

### Section Organization

The file uses a clear visual section divider pattern:
```python
# ─── Section Name ────────────────────────────────────────────────────────────
```
Sections in order:
1. Module docstring
2. Imports
3. Constants
4. Regex patterns
5. Module-level helper functions (XML/typographic)
6. ZIP helpers
7. `MetadataExtractor` class
8. Template substitution helpers
9. `Report` class
10. `InDretCorrector` class
11. Entry point (`main()`)

Within `InDretCorrector`, methods use a lighter divider:
```python
# ── Phase name ────────────────────────────────────────────────────────────
```

### Type Hints

Used selectively, not exhaustively:
- Function signatures annotated when return type is non-obvious: `-> int`, `-> str`, `-> tuple[str, str]`, `-> dict`
- Parameters annotated when type matters: `font_name: str`, `size_pt`, `value: float`, `bm_id: int`
- Modern union syntax `bytes | None` (Python 3.10+) used in newer code
- Class instance variables not annotated (assigned in `__init__` without hints)

### Docstrings

- Module-level docstring: concise, includes usage and output format
- Class/method docstrings: present on complex methods, single-sentence for simple ones
- Many private helpers have no docstring (behavior implied by name + context)
- Docstring style: plain text, no reStructuredText or Google-style params

```python
def get_heading_level(text: str) -> int:
    """Retorna el nivell del títol (1-4) o 0 si no és títol."""

def is_likely_surname(text: str) -> bool:
    """True si el text és un cognom en MAJÚSCULES que cal convertir a versaletes."""
```

**Language:** All comments, docstrings, and user-facing strings are in Catalan or Spanish. Internal variable names are in English/Catalan mixed.

### Error Handling

**Strategy:** Defensive `try/except` wrapping around all python-docx and lxml operations that touch optional document parts. Silent failure is preferred over crashing — errors are routed to the `Report` object (`.warn()`).

**Patterns:**

1. Silent pass on optional subsystems (footnotes, numbering):
```python
except Exception:
    pass
```

2. Warning to report on semi-critical failures:
```python
except Exception as e:
    self.report.warn(f"No s'han pogut processar les notes al peu: {e}")
```

3. Print + early return on hard failures (missing input file):
```python
if not os.path.exists(args.article):
    print(f"Error: fitxer no trobat → {args.article}")
    sys.exit(1)
```

4. Guard returns on empty/None data:
```python
if not runs:
    return
if title_idx < 0:
    return data
```

**Exception specificity:** Most `except` clauses catch `Exception` broadly. Only `_merge_footnotes_xml()` and similar use `(ValueError, TypeError)` tuples for anticipated cast failures.

### Logging / Output

No logging framework. Print statements only, used for phase progress:
```python
print("  [1/5] Correccions de text (espais, cometes)")
print(f"\n[✓] Document generat → {out_doc}")
```

User-facing progress uses `[N/5]` prefix. Success outputs use `[✓]`. No debug/verbose mode.

### State Machine Pattern

`MetadataExtractor.extract()` uses an explicit string-valued state variable (`state = 'post_title'`) with transitions to `'sumari'`, `'abstract'`, `'kw_es'`, `'kw_en'`, `'index'`, `'body'`. This is the canonical pattern for multi-section document parsing within this codebase.

### Template Marker Convention

Double-brace markers in Catalan: `{{TITOL}}`, `{{AUTOR}}`, `{{EDICIO}}`, `{{SUMARI}}`, `{{ABSTRACT}}`, `{{INDEX}}`, `{{DOI}}`, `{{PARAULES_CLAU}}`, `{{KEYWORDS}}`. Always uppercase, always double-brace.

---

## Secondary: WordPress Theme PHP (`functions.php`, `*.php`)

- Functions prefixed with `indret_`: `indret_advanced_search()`, `indret_scripts()`
- WordPress hooks registered inline, not in a class
- No PSR standards applied

## Secondary: JavaScript (`js/advanced-search.js`, tests)

- `'use strict'` at top of all JS files
- jQuery (`$`) used throughout — no vanilla DOM API preference
- Test helper functions named descriptively: `buildSearchDOM()`, `mockAjaxSuccess()`, `mockAjaxError()`

---

*Convention analysis: 2026-03-05*
