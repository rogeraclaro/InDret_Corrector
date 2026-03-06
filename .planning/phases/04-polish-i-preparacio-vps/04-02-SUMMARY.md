# Summary: Plan 04-02 — Validació MIME i requirements

**Status:** DONE
**Date:** 2026-03-06

## What was built

- `web/requirements.txt`: Updated with `puremagic>=1.28` and `gunicorn>=23.0`
- `web/app.py`: Added `import puremagic`, `ALLOWED_MIME` constant, `validar_mime()` function, and MIME check call after `fitxer.save()`

## Verification

All automated checks passed:
- `requirements.txt` contains `puremagic>=1.28` and `gunicorn>=23.0`
- `app.py` AST parse confirms `validar_mime` function, `puremagic` import, `ALLOWED_MIME` constant present

## Key details

- `ALLOWED_MIME` accepts both full DOCX MIME and `application/zip` (puremagic often returns the latter for valid DOCX)
- On MIME failure: file is deleted (`unlink(missing_ok=True)`) and user gets flash error
- Extension check (`.docx`) remains as first-line defense; MIME is second layer after `save()`
- `puremagic` installed locally
