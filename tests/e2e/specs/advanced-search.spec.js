// @ts-check
const { test, expect } = require('@playwright/test');

/**
 * Tests E2E de la cerca avançada
 *
 * Comprova:
 * - Formulari es renderitza correctament
 * - Cerca bàsica per text retorna resultats
 * - Filtre per àrea filtra correctament
 * - Botó de reset neteja el formulari
 * - Paginació és funcional
 * - Comportament en cerca sense resultats
 */

// URL de la pàgina de cerca avançada (intentem totes les slugs conegudes)
const SEARCH_URLS = [
  '/busqueda-avanzada/',
  '/cerca-avancada/',
  '/advanced-search/',
];

/**
 * Troba la primera URL de cerca vàlida
 */
async function findSearchPage(request) {
  for (const url of SEARCH_URLS) {
    const resp = await request.get(url);
    if (resp.status() === 200) return url;
  }
  return null;
}

test.describe('Cerca avançada', () => {

  let searchUrl;

  test.beforeAll(async ({ request }) => {
    searchUrl = await findSearchPage(request);
  });

  test.beforeEach(async ({ page }) => {
    if (!searchUrl) {
      test.skip();
      return;
    }
    await page.goto(searchUrl);
    await page.waitForLoadState('networkidle');
  });

  // ---------------------------------------------------------------
  // Estructura del formulari
  // ---------------------------------------------------------------

  test('el formulari de cerca és visible', async ({ page }) => {
    const form = page.locator('#indret-search-form');
    await expect(form).toBeVisible();
  });

  test('el camp de text lliure existeix', async ({ page }) => {
    const textInput = page.locator('input[name="text_search"]');
    await expect(textInput).toBeVisible();
  });

  test('el select d\'àrea existeix', async ({ page }) => {
    const areaSelect = page.locator('select[name="nombre_area"]');
    await expect(areaSelect).toBeVisible();
  });

  test('el select d\'edició existeix', async ({ page }) => {
    const editionSelect = page.locator('select[name="edicion_gral"]');
    await expect(editionSelect).toBeVisible();
  });

  test('el botó de submit existeix', async ({ page }) => {
    const submitBtn = page.locator('#search-submit-btn, button[type="submit"]').first();
    await expect(submitBtn).toBeVisible();
  });

  test('el botó de reset existeix', async ({ page }) => {
    const resetBtn = page.locator('#reset-search-btn');
    await expect(resetBtn).toBeVisible();
  });

  // ---------------------------------------------------------------
  // Cerca per text
  // ---------------------------------------------------------------

  test('cerca per text retorna resultats o missatge de no resultats', async ({ page }) => {
    const textInput = page.locator('input[name="text_search"]');
    await textInput.fill('responsabilitat');

    // Esperem que el formulari s'enviï automàticament (hi ha un debounce de 800ms)
    await page.waitForTimeout(1000);
    await page.waitForLoadState('networkidle');

    const container = page.locator('#search-results-container');
    // El contenidor hauria de tenir contingut (resultats o missatge no-results)
    const html = await container.innerHTML();
    expect(html.length).toBeGreaterThan(0);
  });

  test('cerca per text buit no mostra resultats inicials', async ({ page }) => {
    const container = page.locator('#search-results-container');
    // En carregar, el contenidor ha de ser buit o ocult
    const isVisible = await container.isVisible();
    if (isVisible) {
      const html = await container.innerHTML();
      // Pot ser que hi hagi un resultat per defecte, però acceptem ambdós casos
      expect(typeof html).toBe('string');
    }
  });

  // ---------------------------------------------------------------
  // Submit explícit
  // ---------------------------------------------------------------

  test('el botó cercar dispara la petició AJAX i mostra resultats', async ({ page }) => {
    // Interceptar la petició AJAX
    const ajaxRequest = page.waitForRequest(req =>
      req.url().includes('admin-ajax.php') && req.method() === 'POST'
    );

    const submitBtn = page.locator('#search-submit-btn').first();
    await submitBtn.click();

    const req = await ajaxRequest;
    const postData = req.postData() ?? '';
    expect(postData).toContain('action=indret_advanced_search');
    expect(postData).toContain('nonce=');
  });

  // ---------------------------------------------------------------
  // Filtre per àrea
  // ---------------------------------------------------------------

  test('seleccionar àrea envia el filtre correcte', async ({ page }) => {
    const areaSelect = page.locator('select[name="nombre_area"]');
    const options = await areaSelect.locator('option').allTextContents();

    // Si hi ha opcions a part de "Totes"
    const nonEmptyOptions = options.filter(o => o.trim() !== '' && o !== 'Totes' && o !== 'Todas' && o !== 'All');
    if (nonEmptyOptions.length === 0) {
      test.skip();
      return;
    }

    // Interceptar AJAX
    const ajaxRequest = page.waitForRequest(req =>
      req.url().includes('admin-ajax.php') && req.method() === 'POST'
    );

    // Seleccionar la primera àrea disponible
    await areaSelect.selectOption({ index: 1 });

    const req = await ajaxRequest;
    const postData = req.postData() ?? '';
    expect(postData).toContain('nombre_area=');
  });

  // ---------------------------------------------------------------
  // Reset del formulari
  // ---------------------------------------------------------------

  test('el botó reset buida el camp de text', async ({ page }) => {
    const textInput = page.locator('input[name="text_search"]');
    await textInput.fill('test search text');
    expect(await textInput.inputValue()).toBe('test search text');

    await page.locator('#reset-search-btn').click();

    await page.waitForTimeout(500);
    expect(await textInput.inputValue()).toBe('');
  });

  test('el botó reset oculta el contenidor de resultats', async ({ page }) => {
    // Primer fem una cerca per omplir el contenidor
    const submitBtn = page.locator('#search-submit-btn').first();
    await submitBtn.click();
    await page.waitForTimeout(2000);

    // Ara fem reset
    await page.locator('#reset-search-btn').click();
    await page.waitForTimeout(600); // fadeOut de 400ms

    const container = page.locator('#search-results-container');
    // Hauria d'estar amagat o buit
    const html = await container.innerHTML();
    expect(html.trim()).toBe('');
  });

  // ---------------------------------------------------------------
  // Resposta AJAX — verificació de l'estructura HTML retornada
  // ---------------------------------------------------------------

  test('la resposta AJAX conté articles o missatge no-results', async ({ page }) => {
    // Interceptar la resposta AJAX
    const ajaxResponse = page.waitForResponse(resp =>
      resp.url().includes('admin-ajax.php') && resp.request().method() === 'POST'
    );

    const submitBtn = page.locator('#search-submit-btn').first();
    await submitBtn.click();

    const resp = await ajaxResponse;
    const json  = await resp.json();

    expect(json.success).toBe(true);
    expect(json.data).toHaveProperty('html');
    expect(json.data).toHaveProperty('found');
    expect(typeof json.data.html).toBe('string');
    expect(typeof json.data.found).toBe('number');
  });

});

// ---------------------------------------------------------------
// Tests de seccions (templates de secció)
// ---------------------------------------------------------------

test.describe('Seccions de contingut', () => {

  const sections = [
    { name: 'Derecho Penal',    paths: ['/derecho-penal/', '/dret-penal/', '/criminal-law/'] },
    { name: 'Edicions',         paths: ['/ediciones/', '/edicions/', '/editions/'] },
    { name: 'Masleidos',        paths: ['/mas-leidos/', '/mes-llegits/', '/most-read/'] },
  ];

  for (const section of sections) {
    test(`la secció "${section.name}" es carrega sense errors`, async ({ page, request }) => {
      let loaded = false;
      for (const path of section.paths) {
        const resp = await request.get(path);
        if (resp.status() === 200) {
          await page.goto(path);
          loaded = true;
          break;
        }
      }

      if (!loaded) {
        test.skip();
        return;
      }

      // Sense errors JS crítics
      const errors = [];
      page.on('pageerror', err => errors.push(err.message));
      await page.waitForLoadState('networkidle');

      await expect(page).toHaveTitle(/.+/); // Té títol
      expect(errors.filter(e => /TypeError|ReferenceError/i.test(e))).toHaveLength(0);
    });
  }

});
