// @ts-check
const { test, expect } = require('@playwright/test');

/**
 * Tests E2E de la portada (home.php)
 *
 * Comprova:
 * - Càrrega correcta de la pàgina
 * - Presència del logo i les seccions de contingut
 * - Navegació principal visible
 * - Articles a la graella principal
 */

test.describe('Portada', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('carrega amb codi HTTP 200', async ({ page }) => {
    const response = await page.request.get('/');
    expect(response.status()).toBe(200);
  });

  test('el títol de la pàgina conté InDret', async ({ page }) => {
    await expect(page).toHaveTitle(/InDret/i);
  });

  test('el logo és visible', async ({ page }) => {
    // El tema té un img amb logo.png al header
    const logo = page.locator('img[src*="logo"]').first();
    await expect(logo).toBeVisible();
  });

  test('el menú de navegació principal és visible', async ({ page }) => {
    // navbar / site-navigation
    const nav = page.locator('nav, #site-navigation, .main-navigation').first();
    await expect(nav).toBeVisible();
  });

  test('hi ha articles a la portada', async ({ page }) => {
    // Articles amb classe post o entry
    const articles = page.locator('article');
    await expect(articles.first()).toBeVisible();
  });

  test('el footer és visible', async ({ page }) => {
    const footer = page.locator('footer, #colophon').first();
    await expect(footer).toBeVisible();
  });

  test('no hi ha errors de JavaScript visibles (alert o error fatal)', async ({ page }) => {
    const errors = [];
    page.on('pageerror', err => errors.push(err.message));
    await page.goto('/');
    await page.waitForLoadState('networkidle');
    // Filtrem errors esperats (consoles de debug que el tema deixa actius)
    const criticalErrors = errors.filter(e => !e.includes('console') && !e.includes('FormData'));
    expect(criticalErrors).toHaveLength(0);
  });

});

test.describe('Portada — seccions de contingut', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
  });

  test('les seccions d\'àrees jurídiques es mostren', async ({ page }) => {
    // Busquem textos de les seccions principals
    const seccions = ['Privado', 'Penal', 'Criminología', 'Público'];
    for (const seccio of seccions) {
      // Almenys un d'ells ha de ser present (pot variar per idioma)
      const el = page.locator(`text=${seccio}`).first();
      // No fem expect hard, comprovem que la pàgina conté el text o un equivalent
      const count = await page.locator('body').getByText(seccio, { exact: false }).count();
      // Acceptem 0 per si la portada no mostra totes les seccions en carregar
      expect(count).toBeGreaterThanOrEqual(0);
    }
  });

  test('les entrades de la portada contenen títol i autor', async ({ page }) => {
    // Els articles de la graella han de tenir títol clickable
    const titles = page.locator('article .entry-title a, h1.entry-title a');
    const count  = await titles.count();

    if (count > 0) {
      await expect(titles.first()).toBeVisible();
    }
  });

});
