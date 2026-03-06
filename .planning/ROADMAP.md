# Roadmap — InDret Article Corrector

## Milestone 1: Corrector CLI (COMPLETAT)
Script de línia de comandes que formata articles .docx seguint el llibre d'estil d'InDret.
**Resultat:** `articles_correc/corrector.py` v2, completament funcional.

---

## Milestone 2: Web Interface
Interfície web Flask per als editors, sense necessitat de terminal.

### Phase 1: Flask app skeleton + gestió de fitxers
**Objectiu:** App Flask arrenca localment, rep un .docx i el retorna sense processar.

Tasks:
- Crear `web/app.py` amb Flask, ruta GET `/` i POST `/corregir`
- Plantilla HTML `web/templates/index.html` amb formulari (fitxer + edició + DOI)
- Gestió de pujada: `werkzeug.secure_filename`, carpeta `web/uploads/` temporal
- Ruta `/descarregar/<filename>` per servir el fitxer processat
- CSS mínim `web/static/style.css` (net, funcional, sense llibreries externes)
- `web/requirements.txt` (Flask, Werkzeug)
- `web/README.md` — instruccions d'arrencada local

**Done when:** `python web/app.py` → formulari al navegador → puja .docx → descarrega el mateix fitxer sense errors.

---

### Phase 2: Integració del corrector
**Objectiu:** La web executa el corrector real i retorna el .docx corregit.

Tasks:
- Adaptar `corrector.py` per acceptar `(input_path, output_path, edicio, doi)` com a funció cridable (o mantenir CLI i cridar via subprocess)
- Cridar el corrector des de Flask en el POST `/corregir`
- Gestionar errors del corrector (excepció → missatge d'error a la pàgina)
- Retornar `[nom]_corregit.docx` com a descàrrega
- Neteja automàtica de fitxers temporals (esborra uploads > 30 min)

**Done when:** Puja un article real → descarrega el .docx corregit correctament formatat.

---

### Phase 3: Informe de canvis
**Objectiu:** La pàgina mostra els canvis aplicats i advertiments, i permet descarregar l'informe.

Tasks:
- Definir estructura de dades per a l'informe: `{"canvis": [...], "advertiments": [...]}`
- Modificar `corrector.py` per retornar (o escriure a fitxer) l'informe estructurat
- Mostrar canvis i advertiments a la pàgina de resultats (`web/templates/resultat.html`)
- Botó "Descarregar informe (.txt)" — genera text pla de l'informe
- Disseny de la pàgina de resultats: dos blocs clars (canvis / advertiments)

**Done when:** Després de processar, la pàgina mostra llista de canvis i advertiments; l'informe .txt es pot descarregar.

---

### Phase 4: Polish i preparació VPS
**Objectiu:** L'app és robusta i documentada per a desplegament al VPS.
**Plans:** 4 plans

Tasks:
- Spinner / missatge "Processant..." mentre el corrector treballa (htmx o polling simple)
- Missatges d'error informatius (fitxer no vàlid, error de processament)
- Favicon i títol de pestanya "InDret — Corrector d'articles"
- `web/gunicorn.conf.py` i instruccions de desplegament nginx al README
- Test manual amb 3 articles reals de tipologies diferents
- Revisió de seguretat: MIME validation, path traversal, límit mida fitxer (20MB)

Plans:
- [ ] 04-01-PLAN.md — UX polish: spinner overlay, favicon, bfcache fix
- [ ] 04-02-PLAN.md — Seguretat: validació MIME amb puremagic, requirements.txt
- [ ] 04-03-PLAN.md — Desplegament VPS: gunicorn.conf.py + README complet
- [ ] 04-04-PLAN.md — Checkpoint: test manual end-to-end amb articles reals

**Done when:** App estable amb articles reals, documentació de desplegament completa, sense errors inesperats.

---

## Milestone 3: Corrector de bibliografies (FUTUR)
Detecció i formatació automàtica d'entrades bibliogràfiques (monografies, articles, capítols).
*Pendent de definir.*
