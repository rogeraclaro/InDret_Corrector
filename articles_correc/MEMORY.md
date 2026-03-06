# InDret — Memòria del projecte

## Projecte actiu
Automatització de la correcció de format d'articles per a la revista acadèmica InDret (UPF Barcelona).
→ Detalls complets: `indret-corrector.md`

## Fitxers clau
- Script principal: `articles_correc/corrector.py` (v2, en actiu)
- Llibre d'estil: `articles_correc/libro_estilo.pdf`
- Plantilla Word: `articles_correc/plantilla.docx`
- Fonts: `articles_correc/tipos/` (PT Serif + Open Sans)
- Pla del projecte: `plan.md` (amb respostes de l'usuari inline)
- Context general: `context.md`

## Stack
- Python 3.13, python-docx, spaCy + es_core_news_lg (instal·lats localment)
- Sense LLM (offline, regles + regex + spaCy NER)
- CLI: `python3 corrector.py <article.docx>`

## Estat
v2 del script escrita i amb sintaxi correcta. Pendent de prova amb article real.
