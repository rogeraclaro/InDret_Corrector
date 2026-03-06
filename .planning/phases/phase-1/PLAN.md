# Phase 1 — Flask app skeleton + gestió de fitxers

## Objective
App Flask arrenca localment, presenta un formulari per pujar .docx (amb camps edició i DOI) i retorna el fitxer sense processar. Valida que el corrector s'integrarà fàcilment a la Phase 2.

## Done When
- `cd web && python app.py` arrenca sense errors
- Navegant a `http://localhost:5000` es veu el formulari
- Pujant un .docx + edició + DOI → el navegador descarrega el mateix fitxer sense errors
- Cap secret ni path absolut al codi

---

## Context

### Estructura de fitxers a crear
```
web/
├── app.py                  # Flask app principal
├── requirements.txt        # Flask, Werkzeug
├── README.md               # Instruccions d'arrencada local
├── templates/
│   └── index.html          # Formulari de pujada
└── static/
    └── style.css           # CSS mínim
```

### Integració futura amb el corrector (per tenir-ho present ara)
- `articles_correc/corrector.py` conté la classe `InDretCorrector`
- Crida: `InDretCorrector(input_path).template_run(plantilla_path, edicio, doi)`
- Retorna: `(out_doc_path, out_report_path)` — paths absoluts als fitxers generats
- La plantilla és: `articles_correc/plantilla.docx`
- **Phase 1 no integra el corrector** — el POST simplement retorna el fitxer pujat tal qual
- Els fitxers temporals han d'anar a `web/uploads/` (no al directori del corrector)

### Restriccions
- Sense llibreries JS ni frameworks CSS (Bootstrap, Tailwind, etc.)
- HTML + CSS pur
- Python 3.13, Flask 3.x
- `secure_filename` de werkzeug per sanititzar noms de fitxer
- Límit de pujada: 20 MB (configurable via `MAX_CONTENT_LENGTH`)

---

## Tasks

### Task 1.1 — Crear `web/requirements.txt`
```
Flask>=3.0
Werkzeug>=3.0
```

---

### Task 1.2 — Crear `web/app.py`

Crea l'aplicació Flask amb:

**Configuració:**
```python
import os, uuid
from pathlib import Path
from flask import Flask, request, render_template, send_file, redirect, url_for, flash
from werkzeug.utils import secure_filename

BASE_DIR  = Path(__file__).parent
UPLOAD_DIR = BASE_DIR / 'uploads'
UPLOAD_DIR.mkdir(exist_ok=True)
ALLOWED_EXT = {'.docx'}
MAX_MB = 20

app = Flask(__name__)
app.secret_key = os.urandom(24)
app.config['MAX_CONTENT_LENGTH'] = MAX_MB * 1024 * 1024
```

**Ruta GET `/`:**
- Renderitza `index.html`

**Ruta POST `/corregir`:**
1. Comprova que `request.files['fitxer']` existeix i no és buit
2. Comprova extensió: `.docx` (via `Path(filename).suffix.lower()`)
3. Genera nom únic: `uuid4().hex + '_' + secure_filename(filename)`
4. Desa a `UPLOAD_DIR / nom_unic`
5. Per ara (Phase 1): retorna el mateix fitxer com a descàrrega
6. `send_file(..., as_attachment=True, download_name=f"{stem}_corregit.docx")`
7. En cas d'error: `flash(missatge)` + `redirect(url_for('index'))`

**Errors a gestionar:**
- Fitxer no adjunt → flash "Selecciona un fitxer .docx"
- Extensió incorrecta → flash "Només s'accepten fitxers .docx"
- Fitxer massa gran → gestor `413` personalitzat → flash "El fitxer supera els 20 MB"

**Arrencada:**
```python
if __name__ == '__main__':
    app.run(debug=True, port=5000)
```

---

### Task 1.3 — Crear `web/templates/index.html`

HTML5 mínim amb:
- `<title>InDret — Corrector d'articles</title>`
- Formulari `method="POST" action="/corregir" enctype="multipart/form-data"`
- Camp `<input type="file" name="fitxer" accept=".docx" required>`
- Camp `<input type="text" name="edicio" placeholder="ex: 4/2025">`
- Camp `<input type="text" name="doi" placeholder="ex: 10.31009/InDret.2025.i4.01">`
- Botó `<button type="submit">Corregir article</button>`
- Bloc per mostrar `flash messages` si n'hi ha (`{% with messages = get_flashed_messages() %}`)
- Referència a `{{ url_for('static', filename='style.css') }}`

**Estructura visual (simple):**
```
┌─────────────────────────────────────┐
│  InDret — Corrector d'articles      │
│  ─────────────────────────────────  │
│  Article (.docx)  [Tria fitxer___]  │
│  Edició           [_______________] │
│  DOI              [_______________] │
│                   [ Corregir ]      │
└─────────────────────────────────────┘
```

---

### Task 1.4 — Crear `web/static/style.css`

CSS net i funcional:
- Font del sistema: `font-family: system-ui, sans-serif`
- Contenidor centrat: `max-width: 560px; margin: 60px auto; padding: 0 1rem`
- Títol: mida gran, pes normal, color fosc
- Formulari: `display: flex; flex-direction: column; gap: 1rem`
- Labels: `font-size: 0.875rem; font-weight: 600; color: #555`
- Inputs i file: `padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; width: 100%`
- Botó: fons blau fosc (`#1a3a5c`), text blanc, padding ampli, cursor pointer
- Flash errors: fons groc pàl·lid, text fosc, padding, border-radius

---

### Task 1.5 — Crear `web/README.md`

Instruccions d'arrencada local:

```markdown
# InDret — Corrector d'articles (web)

## Requisits
- Python 3.13+
- pip

## Instal·lació
```bash
cd web
pip install -r requirements.txt
```

## Arrencada
```bash
python app.py
```
Obre http://localhost:5000 al navegador.

## Estructura
- `app.py` — servidor Flask
- `templates/index.html` — formulari web
- `static/style.css` — estils
- `uploads/` — fitxers temporals (s'esborra automàticament a la Phase 2)
```

---

### Task 1.6 — Test manual

1. `cd web && python app.py` — comprova que arrenca sense errors
2. Obre `http://localhost:5000` — comprova que es veu el formulari
3. Puja `articles_correc/articles/article.docx` amb edició "1/2025" — comprova que el navegador descarrega `article_corregit.docx`
4. Intenta pujar un `.pdf` — comprova que apareix el missatge d'error
5. Envia el formulari sense fitxer — comprova que apareix el missatge d'error

---

## Notes per a la Phase 2

Quan s'integri el corrector, el POST `/corregir` haurà de:
1. Importar: `sys.path.insert(0, str(BASE_DIR.parent / 'articles_correc'))`
2. `from corrector import InDretCorrector`
3. `plantilla = BASE_DIR.parent / 'articles_correc' / 'plantilla.docx'`
4. `c = InDretCorrector(str(input_path))`
5. `out_doc, out_report = c.template_run(str(plantilla), edicio, doi)`
6. Retornar `send_file(out_doc, ...)`
7. Esborrar fitxers temporals de `UPLOAD_DIR` després d'enviar

---

## Verification Checklist

- [ ] `python web/app.py` arrenca sense ImportError ni SyntaxError
- [ ] Formulari es mostra correctament a `http://localhost:5000`
- [ ] Camps: fitxer (.docx), edició (text), DOI (text) visibles
- [ ] Puja article.docx → descarrega `article_corregit.docx`
- [ ] Fitxer pujat desa a `web/uploads/`
- [ ] Puja .pdf → flash error visible, sense crash
- [ ] Envia sense fitxer → flash error visible
- [ ] Cap path absolut hardcoded al codi
- [ ] `web/requirements.txt` conté Flask i Werkzeug
- [ ] `web/README.md` té instruccions clares d'arrencada
