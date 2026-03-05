# Testing Patterns

**Analysis Date:** 2026-03-05

---

## Scope

There are NO automated tests for `articles_correc/corrector.py`. All testing of the Python corrector is manual (run against `.docx` sample files, inspect output). The `tests/` directory covers the WordPress theme only (JS + PHP + E2E). This document covers both realities.

---

## Python Corrector — No Automated Tests

**Status:** No test file, no test runner config, no CI pipeline.

**Current validation approach:**
- Sample articles in `articles_correc/articles/` serve as manual fixtures: `article_mal.docx` (input), `article_corregit.docx` (expected output), `article_mal_informe.md` (expected report)
- `articles_correc/debug_h1.py` is a diagnostic script, not a test: it prints XML of paragraphs classified as `h1` to stdout for visual inspection
- The `Report` class outputs a Markdown informe with applied changes and warnings — this serves as a manual audit trail

**Debug script pattern** (`articles_correc/debug_h1.py`):
```python
# Imports corrector internals directly via sys.path
sys.path.insert(0, '.')
from corrector import classify_para, get_heading_level

doc = Document(sys.argv[1])
for i, para in enumerate(doc.paragraphs):
    ptype = classify_para(para)
    if ptype == 'h1':
        print(etree.tostring(para._p, pretty_print=True).decode())
```

**Recommended test location if tests are added:**
- `articles_correc/tests/` (does not exist yet)
- Unit tests for pure functions: `get_heading_level()`, `classify_para()`, `is_likely_surname()`, `_fix_text()`
- Integration tests against fixture `.docx` files in `articles_correc/articles/`

---

## Theme Tests — Three Separate Test Suites

The WordPress theme has three independent testing setups in `tests/`, each with its own `package.json` or `composer.json`.

---

## Suite 1: JavaScript Unit Tests (Jest)

**Framework:** Jest 29.7.0
**Config:** `tests/js/package.json` (inline Jest config)
**Environment:** jsdom (simulates browser DOM)
**Test files:** `tests/js/*.test.js`

**Run Commands:**
```bash
cd tests/js
npm test              # Run all tests
npm run test:watch    # Watch mode
npm run test:coverage # With coverage report
```

**Coverage target:** `../../js/advanced-search.js` (single file)

**Test File Organization:**
- Co-located with test runner config, not with source
- Single test file: `tests/js/advanced-search.test.js`
- Global setup: `tests/js/setup.js`

**Setup Pattern** (`tests/js/setup.js`):
```javascript
// Simulates what WordPress injects via wp_localize_script
global.$ = require('jquery');
global.jQuery = $;
global.indretSearch = {
  ajaxurl: 'http://localhost/wp-admin/admin-ajax.php',
  nonce: 'test-nonce-abc123',
};
```

**Test Structure:**
```javascript
describe('Group Name', () => {
  beforeEach(() => {
    buildSearchDOM();          // reset DOM for each test
    mockAjaxSuccess({ ... });  // set mock state
    jest.isolateModules(() => {
      require('../../js/advanced-search.js'); // re-register jQuery listeners
    });
  });

  test('description in Catalan', () => {
    // arrange: set DOM values
    // act: trigger jQuery event
    // assert: expect
  });
});
```

**DOM Setup Pattern:**
DOM helper function `buildSearchDOM()` recreates the full form HTML via `document.body.innerHTML = ...` before each test group. This is the canonical way to get a clean state.

**Mocking Pattern:**
```javascript
// Ajax success mock
function mockAjaxSuccess(responseData = {}) {
  ajaxSpy = jest.fn().mockImplementation(function (options) {
    Promise.resolve().then(() => {
      if (options.success) options.success({ success: true, data: responseData });
      if (options.complete) options.complete();
    });
    return { done: () => {}, fail: () => {} };
  });
  $.ajax = ajaxSpy;
}

// Ajax error mock
function mockAjaxError() {
  ajaxSpy = jest.fn().mockImplementation(function (options) {
    Promise.resolve().then(() => {
      if (options.error) options.error({ responseText: 'Server Error' }, 'error', '...');
      if (options.complete) options.complete();
    });
    return { done: () => {}, fail: () => {} };
  });
  $.ajax = ajaxSpy;
}
```

**Async Testing Pattern:**
```javascript
// Double Promise.resolve() to flush microtask queue after mock async callbacks
await Promise.resolve();
await Promise.resolve();
expect(element.style.display).toBe('none');
```

**Global mocks for browser APIs:**
```javascript
global.alert = jest.fn();  // Suppress and spy on alert()
```

**jQuery document.ready mock** (in `beforeAll`):
```javascript
jest.spyOn($.fn, 'ready').mockImplementation(function (fn) {
  fn($);  // execute immediately instead of waiting for DOM ready
  return this;
});
```

**Test Groups in `advanced-search.test.js`:**
1. `Form submit` — preventDefault, AJAX call, nonce, field collection, loader UI
2. `Reset button` — clears author_id, hides results container
3. `Author autocomplete` — datalist matching, clears on no match
4. `Auto-search on select change` — triggers submit on `change` event
5. `Pagination AJAX` — click fires AJAX with correct `paged` param
6. `AJAX error handling` — shows alert to user on error

---

## Suite 2: E2E Tests (Playwright)

**Framework:** Playwright (via `@playwright/test`)
**Config:** `tests/e2e/playwright.config.js`
**Test files:** `tests/e2e/specs/*.spec.js`

**Run Commands:**
```bash
cd tests/e2e
npx playwright test                  # Run all specs
npx playwright test specs/homepage   # Run single spec
npx playwright show-report           # Open HTML report
```

**Configuration:**
```javascript
// playwright.config.js
module.exports = defineConfig({
  testDir: './specs',
  timeout: 30_000,
  retries: 1,
  workers: 1,           // Sequential — avoids session/cookie conflicts
  use: {
    baseURL: 'http://indret-prod.local',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'on-first-retry',
  },
  projects: [
    { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    { name: 'firefox',  use: { ...devices['Desktop Firefox'] } },
    { name: 'mobile-chrome', use: { ...devices['Pixel 5'] } },
  ],
});
```

**Spec Files:**
- `tests/e2e/specs/homepage.spec.js`
- `tests/e2e/specs/article.spec.js`
- `tests/e2e/specs/advanced-search.spec.js`
- `tests/e2e/specs/language-switch.spec.js`

**E2E Test Structure:**
```javascript
test.describe('Group name', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('description in Catalan', async ({ page }) => {
    await expect(page).toHaveTitle(/InDret/i);
    const el = page.locator('article').first();
    await expect(el).toBeVisible();
  });
});
```

**Locator Strategy:**
- Semantic locators preferred: `page.locator('article')`, `page.locator('nav')`
- Fallback to ID/class: `page.locator('#site-navigation')`, `page.locator('.entry-title a')`
- Avoid brittle: `page.locator('body').getByText()` used for flexible text matching
- Soft assertions used where content may vary: `expect(count).toBeGreaterThanOrEqual(0)`

**JavaScript Error Checking Pattern:**
```javascript
const errors = [];
page.on('pageerror', err => errors.push(err.message));
await page.goto('/');
await page.waitForLoadState('networkidle');
const criticalErrors = errors.filter(e => !e.includes('console') && !e.includes('FormData'));
expect(criticalErrors).toHaveLength(0);
```

**Requires local dev server** at `http://indret-prod.local` (Local by Flywheel).

---

## Suite 3: PHP Unit Tests (PHPUnit)

**Framework:** PHPUnit 9.6
**Config:** `tests/php/phpunit.xml`
**Bootstrap:** `tests/php/bootstrap.php`
**Test files:** `tests/php/unit/*.php`

**Run Commands:**
```bash
cd tests/php
./vendor/bin/phpunit
```

**Coverage target:** `../../functions.php` only

**Test Files:**
- `tests/php/unit/AuthorsTest.php`
- `tests/php/unit/EditionsTest.php`
- `tests/php/unit/FiltersTest.php`
- `tests/php/unit/SearchQueryTest.php`

---

## Coverage Summary

| Component | Framework | Coverage |
|---|---|---|
| `articles_correc/corrector.py` | None | 0% automated |
| `js/advanced-search.js` | Jest 29 | Measured (jsdom) |
| `functions.php` | PHPUnit 9 | Measured |
| WordPress theme UI | Playwright | E2E (3 browsers) |

---

## What to Test When Adding corrector.py Tests

**Pure functions (easy to unit test, no fixtures needed):**
- `get_heading_level(text)` — 12+ patterns to verify
- `is_likely_surname(text)` — legal abbr exclusions, length guards
- `_fix_text(text)` — double spaces, quote substitution
- `classify_para(para)` — requires mock `para` with `.text`, `.style.name`, `.runs`

**State machine (medium complexity):**
- `MetadataExtractor.extract(doc)` — use fixture `.docx` files from `articles_correc/articles/`

**Integration tests (use real fixtures):**
- Run `InDretCorrector(article_mal.docx).template_run(plantilla.docx)` and compare output to `article_be.docx`

**Fixture files available:**
- `articles_correc/article_mal.docx` — poorly-formatted input
- `articles_correc/article_be.docx` — correctly-formatted reference
- `articles_correc/articles/article_mal.docx`, `article_mal2.docx` — additional inputs

---

*Testing analysis: 2026-03-05*
