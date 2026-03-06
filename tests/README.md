# Tests del tema InDret

Tres tipus de tests independents, cadascun amb el seu propi directori.

---

## 1. PHP Unit (PHPUnit + Brain Monkey)

**Requisit:** PHP 8.x + Composer

```bash
cd tests/php
composer install
./vendor/bin/phpunit
```

**Cobreix:**
- `FiltersTest` — excerpt_length, query_vars, exclude_terms, disable comments
- `AuthorsTest` — indret_get_post_authors(), indret_get_all_authors()
- `EditionsTest` — ordenació d'edicions per any/trimestre
- `SearchQueryTest` — construcció d'args de WP_Query per a la cerca avançada

---

## 2. JavaScript (Jest + jsdom)

**Requisit:** Node.js 18+ + npm

```bash
cd tests/js
npm install
npm test

# Amb cobertura
npm run test:coverage

# Mode watch
npm run test:watch
```

**Cobreix:**
- Submit del formulari → petició AJAX correcta
- Loader visible/ocult durant la petició
- Botó reset → neteja formulari i contenidor
- Autocompletat d'autor → actualitza autor_id hidden
- Auto-cerca en canviar selects
- Paginació AJAX → extreu número de pàgina
- Gestió d'errors AJAX

---

## 3. E2E (Playwright)

**Requisit:** Node.js 18+ + site Local actiu a `http://indret-prod.local`

```bash
cd tests/e2e
npm install
npx playwright install   # Installa navegadors (1a vegada)
npm test

# Amb navegador visible
npm run test:headed

# Interfície visual
npm run test:ui

# Veure report HTML
npm run test:report
```

**Specs:**
- `homepage.spec.js` — portada: logo, nav, articles, footer, errors JS
- `language-switch.spec.js` — cookie d'idioma, textos per idioma
- `advanced-search.spec.js` — formulari, filtre per àrea, reset, AJAX
- `article.spec.js` — article individual, comentaris desactivats, PDF/descàrregues

---

## Estructura

```
tests/
├── php/
│   ├── composer.json
│   ├── phpunit.xml
│   ├── bootstrap.php
│   └── unit/
│       ├── FiltersTest.php
│       ├── AuthorsTest.php
│       ├── EditionsTest.php
│       └── SearchQueryTest.php
├── js/
│   ├── package.json
│   ├── setup.js
│   └── advanced-search.test.js
├── e2e/
│   ├── package.json
│   ├── playwright.config.js
│   └── specs/
│       ├── homepage.spec.js
│       ├── language-switch.spec.js
│       ├── advanced-search.spec.js
│       └── article.spec.js
└── README.md
```
