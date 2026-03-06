# Project State

## Current Milestone
**Milestone 2: Web Interface**

## Current Phase
**Phase 4 — Polish i preparació VPS**
Status: NOT STARTED

## Phase Status

| Phase | Title | Status |
|-------|-------|--------|
| 1 | Flask app skeleton + gestió de fitxers | DONE |
| 2 | Integració del corrector | DONE |
| 3 | Informe de canvis | DONE |
| 4 | Polish i preparació VPS | NOT STARTED |

## Last Action
Sessió de bugfixing numeració de pàgines:
- `_add_page_numbers` corregit: `pgNumType start` inserit en ordre correcte (addprevious)
- Marge peu: `w:footer` passa de 0 a 567 twips (~1cm) — número ja no enganxat al límit
- Nou paràmetre `pagina_inici` (int, default 1) a `template_run` i `_add_page_numbers`
- Formulari web: nou camp numèric "Pàgina inicial del cos" passa `pagina_inici` al corrector
- CSS: `.field--narrow` per al camp numèric
- Diagnosi: error "Word found unreadable content" era específic a l'article Lyria (estat transitori durant dev), no un bug del corrector

## Pendent de resoldre
Res pendent de la fase actual. Phase 4 per iniciar.

## Next Action
Iniciar Phase 4 — Polish i preparació VPS:
- Spinner "Processant..." mentre el corrector treballa
- Missatges d'error informatius
- Favicon + títol pestanya
- gunicorn.conf.py + README desplegament nginx
- Test manual 3 articles reals
- Revisió seguretat (MIME, path traversal, límit 20MB)

---
*Updated: 2026-03-06*
