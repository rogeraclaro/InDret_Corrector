# Instruccions per traslladar el projecte a una altra màquina

## 1. Fitxers a copiar

Copia la carpeta sencera `articles_correc/` a la nova màquina. Ha de contenir:

```
articles_correc/
├── corrector.py                        ← script principal
├── libro_estilo.pdf                    ← llibre d'estil InDret
├── plantilla.docx                      ← plantilla Word oficial
├── corregir.docx                       ← document d'anotacions de proves
├── Exemple article ben editat.docx     ← referència per proves
├── Exemple article mal editat.docx     ← referència per proves
├── MEMORY.md                           ← memòria resumida del projecte
├── indret-corrector.md                 ← estat detallat del projecte
├── instruccions.md                     ← aquest fitxer
└── tipos/
    ├── PTSerif-Regular.ttf
    ├── PTSerif-Bold.ttf
    ├── PTSerif-Italic.ttf
    ├── PTSerif-BoldItalic.ttf
    ├── OpenSans-VariableFont_wdth,wght.ttf
    └── OpenSans-Italic-VariableFont_wdth,wght.ttf
```

## 2. Requisits de la nova màquina

- **Python 3.11 o superior** (recomanat 3.13)
- **pip3** disponible

Comprova-ho amb:

```bash
python3 --version
pip3 --version
```

## 3. Instal·lar dependències

```bash
pip3 install python-docx spacy
python3 -m spacy download es_core_news_lg
```

> ⚠️ El model `es_core_news_lg` pesa ~570 MB. Necessitaràs connexió a internet per la instal·lació.

## 4. Verificar que tot funciona

```bash
python3 -c "import docx; import spacy; spacy.load('es_core_news_lg'); print('Tot OK')"
```

Ha de mostrar `Tot OK` sense errors.

## 5. Provar el script

```bash
cd /ruta/a/articles_correc
python3 corrector.py "Exemple article mal editat.docx"
```

Ha de generar:

- `Exemple article mal editat_corregit.docx`
- `Exemple article mal editat_informe.md`

## 6. Reprendre la feina amb Claude

Obre Claude Code al directori `articles_correc/` i escriu:

> "Llegeix MEMORY.md, indret-corrector.md i corrector.py i reprèn el projecte del corrector d'InDret"

Claude llegirà els fitxers i tindrà tot el context per continuar sense que hagis d'explicar res de nou.

## 7. Instal·lar les fonts al sistema (opcional)

Les fonts PT Serif i Open Sans de la carpeta `tipos/` s'utilitzen als documents Word generats.
Per instal·lar-les al Mac:

- Fes doble clic a cada fitxer `.ttf` → "Instal·la la font"

O per línia de comandes:

```bash
cp tipos/*.ttf ~/Library/Fonts/
```

---

**Nota**: El directori de treball original és:
`/Volumes/1Tera/Local Sites/indret-prod/app/public/wp-content/themes/indret/`

A la nova màquina pots posar `articles_correc/` on vulguis — el script no depèn de cap ruta absoluta.

o

1. Copiar la carpeta de memòria a la nova màquina, a la mateixa ruta:
   ~/.claude/projects/-Volumes-1Tera-Local-Sites-indret-prod-app-public-wp-content-themes-indret/memory/
2. Més senzill: copiar el contingut de indret-corrector.md i enganxar-lo directament al xat quan reprendis, dient "aquí tens el context del projecte anterior".
3. El més pràctic: com que tots els fitxers del projecte (plan.md, context.md, corrector.py) ja contenen tota la informació, a la nova màquina pots simplement dir:
   "Llegeix els fitxers plan.md, context.md i articles_correc/corrector.py i reprèn el projecte"

Reprendre sessió al mac mini:

claude --resume 425b7360-038b-48d0-8284-c4f8bfede9cb
