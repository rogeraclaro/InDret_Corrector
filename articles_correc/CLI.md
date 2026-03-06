# InDret Corrector — Referència CLI

## Sintaxi

```
python3 corrector.py <article.docx> [opcions]
```

---

## Paràmetres

### `article` *(obligatori)*
Ruta al fitxer `.docx` a corregir.

```bash
python3 corrector.py article.docx
```

Genera dos fitxers al mateix directori:
- `article_corregit.docx` — document corregit amb plantilla InDret
- `article_informe.md` — informe de canvis aplicats i avisos

---

### `--edicio NUM`
Número d'edició de la revista. S'insereix a la capçalera de totes les pàgines en format `InDret X.XXXX`.

```bash
python3 corrector.py article.docx --edicio "1/2024"
# → capçalera: "InDret 1.2024"

python3 corrector.py article.docx --edicio "4/2025"
# → capçalera: "InDret 4.2025"
```

---

### `--doi DOI`
Identificador DOI de l'article. S'insereix al camp DOI de la portada.

```bash
python3 corrector.py article.docx --doi "10.31009/InDret.2024.i1.01"
```

Si no s'especifica, el camp DOI de la portada queda en blanc.

---

### `--plantilla FITXER`
Ruta alternativa a la plantilla `.docx`. Per defecte, el corrector cerca `plantilla.docx` al mateix directori que `corrector.py`.

```bash
python3 corrector.py article.docx --plantilla /ruta/altra/plantilla.docx
python3 corrector.py article.docx --plantilla ../plantilles/plantilla_2025.docx
```

---

### `--sense-plantilla`
Mode clàssic: aplica les correccions directament sobre l'article sense usar la plantilla InDret. El document de sortida no inclou portada ni capçaleres de la revista.

```bash
python3 corrector.py article.docx --sense-plantilla
```

Útil per revisar un article de manera ràpida sense generar el document final maquettat.

---

## Exemple complet

```bash
python3 corrector.py article_garcia_2025.docx \
  --edicio "2/2025" \
  --doi "10.31009/InDret.2025.i2.03"
```

---

## Notes

- El fitxer original **mai es modifica**; sempre es crea un fitxer `_corregit.docx` nou.
- Si el directori de treball no és el del script, cal especificar la ruta completa a l'article:
  ```bash
  python3 /ruta/corrector.py /ruta/articles/article.docx --edicio "1/2025"
  ```
