# InDret Article Corrector

## What This Is

Sistema de correcció automàtica de format d'articles per a la revista acadèmica InDret (UPF Barcelona). Processa fitxers .docx enviats pels autors i els reformata automàticament seguint el llibre d'estil i la plantilla oficial de la revista. L'objectiu és eliminar el treball manual repetitiu de l'equip editorial.

## Core Value

Qualsevol membre de l'equip editorial pot pujar un article .docx, veure exactament què s'ha corregit i què queda pendent, i descarregar el fitxer corregit — sense tocar la terminal.

## Requirements

### Validated

- ✓ CLI: `python3 corrector.py <article.docx> [--edicio] [--doi]` — v2
- ✓ Portada: Title (italic), Palabras clave, Keywords, DOI formatejats correctament — v2
- ✓ Índex: numeració preservada, indentació N1/N2/N3 corregida, PT Serif 9pt — v2
- ✓ Cos: PT Serif 10pt, justificat, sense sagnat primera línia — v2
- ✓ Capçalera: "InDret X.XXX" (esquerra) + autor (dreta), tab dret — v2
- ✓ Títols N1: Open Sans 11pt bold, space_after 18pt — v2
- ✓ Notes al peu: PT Serif 8.5pt, núm. sense cursiva, 5pt entre notes, via ZIP — v2

### Active

- [ ] Interfície web: formulari per pujar .docx amb camps edició i DOI
- [ ] Processament al servidor: executa el corrector i retorna el fitxer corregit
- [ ] Informe de canvis: llista dels canvis aplicats automàticament
- [ ] Advertiments: llista d'elements que no s'han pogut corregir i cal fer manualment
- [ ] Descàrrega del resultat: fitxer .docx corregit descarregable directament
- [ ] Sense autenticació: accés obert per a tot l'equip (no cal login per ara)

### Out of Scope

- Bibliografies — pendent de futura iteració, requereix lògica complexa
- Processament per lots — un article a la vegada (decisió actual)
- Desplegament públic/núvol — comencem local, s'escala si cal
- Integració LLM/IA — sistema offline per disseny, regles deterministes
- Edició en línia del document — no és un editor, és un processador

## Context

### Estat actual del corrector (v2)
- Script: `articles_correc/corrector.py`
- Llibre d'estil: `articles_correc/libro_estilo.pdf`
- Plantilla: `articles_correc/plantilla.docx`
- Fonts: `articles_correc/tipos/` (PT Serif 4 variants + Open Sans 2 variants)
- Dependències: Python 3.13, python-docx 1.2.0, lxml, spaCy 3.8.11 (es_core_news_lg)
- Pendent implementar: formatació bibliografies

### Usuaris
Equip editorial d'InDret. Confort tècnic baix (no CLI). Necesiten accés des de qualsevol ordinador sense instal·lar res.

### Flux de treball objectiu
1. Obrir la web → pujar .docx
2. Introduir edició (ex: "4/2025") i DOI (opcional)
3. El servidor processa l'article
4. L'usuari veu: canvis aplicats + advertiments de coses pendents
5. Descarrega el .docx corregit

## Constraints

- **Stack**: Python — el corrector ja és Python, la web ha de reutilitzar-lo directament
- **Offline**: sense LLM ni serveis externs — tot local i determinista
- **Compatibilitat**: ha de funcionar amb els .docx que arriben dels autors, que poden tenir formats molt variats
- **Simplicitat**: solució local primer, sense sobre-enginyeria de desplegament

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Python per al corrector | Disponibilitat de python-docx i spaCy | ✓ Good |
| ZIP post-process per footnotes | python-docx no permet manipular footnotes directament | ✓ Good |
| Sense LLM | Offline, determinista, reproduïble sense costos d'API | ✓ Good |
| Web framework per la GUI | A determinar (Flask vs FastAPI vs Streamlit) | — Pending |

---
*Last updated: 2026-03-05 after initialization*
