# Requirements — Milestone 2: Web Interface

## Goal

Proporcionar una interfície web accessible des del navegador que permeti a l'equip editorial d'InDret pujar un article .docx, processar-lo amb el corrector existent i descarregar el resultat corregit amb un informe de canvis, sense necessitat de cap instal·lació ni coneixement de la terminal.

## Scope

Aquest milestone cobreix exclusivament la interfície web per sobre del corrector existent (`articles_correc/corrector.py`). No modifica la lògica del corrector.

---

## Functional Requirements

### F1 — Formulari de pujada
- Camp de pujada de fitxer .docx (obligatori)
- Camp text "Edició" — ex: `4/2025` (opcional, igual que al CLI `--edicio`)
- Camp text "DOI" — ex: `10.31009/InDret.2025.i4.01` (opcional, igual que al CLI `--doi`)
- Botó "Corregir article"
- Validació del tipus de fitxer (només .docx) al servidor

### F2 — Processament
- El servidor executa `corrector.py` amb el fitxer i els paràmetres rebuts
- El fitxer pujat s'emmagatzema temporalment durant el processament
- El fitxer temporal s'esborra després de la descàrrega o passats 30 minuts
- Missatge d'error clar si el processament falla

### F3 — Informe de canvis (inline)
- Després del processament, la pàgina mostra:
  - Llista de **canvis aplicats** (ex: "Font cos → PT Serif 10pt", "Justificació aplicada")
  - Llista d'**advertiments** (elements que cal revisar manualment)
- L'informe és generat pel corrector i passat a la web

### F4 — Descàrrega
- Botó "Descarregar .docx corregit" — descarrega el fitxer processat
- Botó "Descarregar informe" — descarrega l'informe com a fitxer .txt
- El nom del fitxer de sortida segueix el patró: `[nom_original]_corregit.docx`

### F5 — Sense autenticació
- Accés obert, sense login
- Dissenyat per ser accessible dins la xarxa local (o VPS) sense exposició pública

---

## Non-Functional Requirements

### NF1 — Stack
- Python 3.13, Flask
- Reutilitza `articles_correc/corrector.py` sense modificar-lo
- HTML + CSS mínim (sense frameworks JS, sense npm)

### NF2 — Desplegament
- Local: `python app.py` → `http://localhost:5000`
- Producció (futur): gunicorn + nginx al VPS

### NF3 — Usabilitat
- Funciona des de qualsevol navegador modern
- Missatges en castellà/català (mateixa llengua que el corrector)
- Temps de processament visible (spinner o missatge "processant...")

### NF4 — Seguretat bàsica
- Validació del tipus MIME del fitxer al servidor (no confiar en l'extensió)
- Nom de fitxer sanititzat (werkzeug `secure_filename`)
- Carpeta `uploads/` fora del directori públic web

---

## Out of Scope (aquest milestone)

- Autenticació i control d'accés
- Processament per lots
- Historial de documents processats
- Previsualització del document a la web
- Integració amb el formulari d'autors d'indret.com
- Desplegament al VPS (es configura localment, instruccions al README)
