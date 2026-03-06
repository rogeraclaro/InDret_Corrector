# InDret Corrector — Estat detallat

## Què és InDret
Revista acadèmica de dret en línia (UPF Barcelona). Trimestral, 26 anys, ~3.667 articles PDF.
Rep articles en .docx de tot l'àmbit hispanoamericà i els ha de formatar segons el seu Libro de estilo.

## Directori de treball
`/Volumes/1Tera/Local Sites/indret-prod/app/public/wp-content/themes/indret/`

## Fitxers del projecte
| Fitxer | Descripció |
|---|---|
| `articles_correc/corrector.py` | Script Python v2 — corrector principal |
| `articles_correc/libro_estilo.pdf` | Llibre d'estil oficial d'InDret (7 pàgines) |
| `articles_correc/plantilla.docx` | Plantilla Word oficial |
| `articles_correc/tipos/` | Fonts TTF: PT Serif (4 variants) + Open Sans (2 variants) |
| `articles_correc/corregir.docx` | Document de proves amb anotacions de l'usuari |
| `articles_correc/Exemple article ben editat.docx` | Article de referència ben formatat |
| `articles_correc/Exemple article mal editat.docx` | Article de referència mal formatat (per proves) |
| `plan.md` | Pla del projecte (amb respostes de l'usuari inline) |
| `context.md` | Context general de la revista |

## Resum del Libro de estilo (punts clau)
- **Portada (p.1)**: títol PT Serif 14 bold, subtítol PT Serif 12 italic, nº edició Open Sans 9, autor Open Sans 8, sumari+abstract PT Serif 9 italic, keywords PT Serif 9
- **Índex (p.2)**: N1 → PT Serif 9 italic bold; N2+ → PT Serif 9 normal. Sagnats creixents per nivell.
- **Cos del text**: PT Serif 10 normal, interlineat 1,1. Paràgrafs separats per espai simple.
- **Títols N1**: Open Sans 11 bold (numeració aràbiga). N2 (1.1.): PT Serif 10 bold. N3 (a.): PT Serif 10 italic.
- **Encapçalament**: Open Sans 8 bold — autor (dreta), nº InDret (esquerra). NO a portada ni índex.
- **Notes al peu**: PT Serif 8,5 normal, interlineat senzill, 3pt before.
- **Bibliografía**: PT Serif 10 normal, interlineat múltiple 1pt.
- **Cognoms d'autor** (cites + bibliografía + notes): VERSALETES (small caps), mai majúscules.
- **Cometes**: títols d'articles entre «» (llatines), mai "".
- **Jurisprudència**: STS/STSJ/SAP/SJPI/STC amb ECLI o Roj.
- **Primera cita**: sempre completa (§9). Les següents, abreviades. L'ús d'"op. cit." en primera cita és error.

## Arquitectura del script (v2)
```
corrector.py → InDretCorrector.run()
  Fase 1: _phase1_text()          — espais dobles, cometes "→«»
  Fase 2: _phase2_styles()        — fonts, mides, interlineat, espaiat paràgrafs
                                    + _apply_footnote_font() → PT Serif 8,5
  Fase 3: _phase3_small_caps()    — versaletes per regex (cos + bibliografía + notes)
  Fase 4: _phase4_index()         — sagnats per nivell, bookmarks, hiperenllaços interns
  Fase 5: _phase5_checks()        — jurisprudència, op. cit., portada (abstract/sumari/keywords)
  _save()                         → <nom>_corregit.docx + <nom>_informe.md
```

## Lògica clau: versaletes
- **No** s'usa spaCy NER (poc fiable per a text en MAJÚSCULES).
- S'usa `is_likely_surname(text)`: run tot en CAPS, longitud ≥4 (o conté espai), no és abreviatura legal (STS, BOE, CC, ECLI, etc.).
- Conversió: `run.text = run.text.title()` + `set_small_caps_xml(run._r)` (XML `<w:smallCaps/>`).

## Lògica clau: índex
- Detecta secció per paraula clau ('índice', 'sumario'...).
- Fi de l'índex: primer paràgraf de >120 caràcters.
- Per cada entrada: `get_heading_level(text)` → sagnat (Cm 0/0.5/1.0/1.5).
- Bookmarks als títols del cos (`w:bookmarkStart/End`) + `w:hyperlink w:anchor` a les entrades.

## Dependències instal·lades (Python 3.13)
```
python-docx 1.2.0
spacy 3.8.11 + es_core_news_lg 3.8.0
```
(instal·lades via pip3 al sistema, no virtualenv)

## Estat actual
- v2 en actiu. Detecta títols sense numeració (bold+curt → N1). footnotes_part fix aplicat.
- `plantilla.docx` parcialment actualitzada amb marcadors `{{}}`. Còpia de seguretat: `plantilla_original_backup.docx`
- **PROPER PAS (pendent)**: corregir 2 problemes a la plantilla i implementar nou script basat en plantilla.

## Problemes pendents a plantilla.docx
1. `{{ABSTRACT}}` mostra `{{SUMARI}}` (el segon resum és en anglès, cal distingir-los al script de modificació)
2. Encapçalament sec2: "InDret 1.2020 ... Nombre Apellidos" — el marcador `{{EDICIO}}` NO s'ha aplicat (el header té un format complex, cal inspeccionar els runs)
3. Columna esquerra taula 0: `{{AUTOR}}` OK però cal verificar que `{{EDICIO}}` és `{{EDICIO}}` i no `{EDICIO}`

## Com reprendre
Obrir nova sessió i dir: "Llegeix MEMORY.md, indret-corrector.md i corrector.py i reprèn el projecte del corrector d'InDret. Hem d'acabar d'editar la plantilla.docx i implementar el nou flux basat en plantilla."
- L'usuari ha d'editar `plantilla.docx` substituint placeholders per marcadors `{{TITOL}}`, `{{AUTOR}}`, etc. (veure llistat a la conversa).
- Possible millora futura: integrar Gemini API com a capa opcional per a casos complexos.

## Pròxima implementació: flux basat en plantilla
1. Còpia de `plantilla.docx` → `article_corregit.docx`
2. Extreure seccions del document original: títol, autor, sumari, abstract, keywords, cos, bibliografía
3. Omplir marcadors `{{X}}` a les taules de portada i índex
4. Buidar cos de la plantilla i injectar contingut real amb estils de la plantilla
5. Paràmetre CLI: `python3 corrector.py article.docx --edicio "2.2026"`

## Marcadors `{{}}` a afegir a plantilla.docx
- `{{EDICIO}}` — nº edició (taula portada col. esq. + taula índex col. esq. + encapçalament)
- `{{AUTOR}}` — nom i cognoms autor
- `{{ORGANITZACIO}}` — organització/universitat
- `{{TITOL}}` — títol article (PT Serif 14 bold)
- `{{SUBTITOL}}` — subtítol (PT Serif 12 italic)
- `{{SUMARI}}` — resum en castellà (PT Serif 9 italic)
- `{{ABSTRACT}}` — resum en anglès (PT Serif 9 italic)
- `{{TITOL_EN}}` — títol en anglès (PT Serif 9 italic)
- `{{PARAULES_CLAU}}` — paraules clau castellà
- `{{KEYWORDS}}` — paraules clau anglès
- Índex: buidar exemples, deixar 1 paràgraf buit (s'omplirà automàticament)

## Com reprendre
1. Llegir `instruccions.md` per saber com preparar la nova màquina.
2. Dir a Claude: "Llegeix MEMORY.md, indret-corrector.md i corrector.py i reprèn el projecte del corrector d'InDret"
3. Claude tindrà tot el context i es podrà continuar des d'on s'ha deixat.

## Preferències de l'usuari
- Llengua: català per comunicar-se, castellà/català dins els documents
- No vol LLM per ara (offline, sense cost API)
- Execució local (Mac) → futur: servidor web
- Un article a la vegada (no lots)
