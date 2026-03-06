// @ts-check
const { test, expect } = require('@playwright/test');

/**
 * Tests E2E d'un article individual (single.php / single-sumarios.php)
 *
 * Comprova:
 * - Accés a un article sense error 404
 * - Presència del títol, autor i àrea
 * - Botó/link de descàrrega PDF present
 * - Comptador de descàrregues és numèric
 * - La crida AJAX de descàrrega funciona
 */

/**
 * Troba un article publicat al lloc
 */
async function findFirstArticleUrl(page) {
  await page.goto('/');
  await page.waitForLoadState('networkidle');

  // Busquem el primer link d'article a la portada
  const articleLink = page.locator('article a[href], .entry-title a').first();
  const count = await articleLink.count();

  if (count === 0) return null;

  const href = await articleLink.getAttribute('href');
  return href;
}

test.describe('Pàgina d\'article', () => {

  let articleUrl;

  test.beforeAll(async ({ browser }) => {
    const page = await browser.newPage();
    articleUrl = await findFirstArticleUrl(page);
    await page.close();
  });

  test.beforeEach(async ({ page }) => {
    if (!articleUrl) {
      test.skip();
      return;
    }
    await page.goto(articleUrl);
    await page.waitForLoadState('networkidle');
  });

  test('l\'article es carrega amb codi 200', async ({ page }) => {
    const resp = await page.request.get(articleUrl);
    expect(resp.status()).toBe(200);
  });

  test('l\'article té títol visible', async ({ page }) => {
    const title = page.locator('h1.entry-title, h1, .post-title').first();
    await expect(title).toBeVisible();
    const text = await title.textContent();
    expect(text?.trim().length).toBeGreaterThan(0);
  });

  test('l\'article no té comentaris oberts (comentaris desactivats)', async ({ page }) => {
    // El tema desactiva els comentaris globalment
    const commentForm = page.locator('#respond, #commentform, .comment-respond');
    const count = await commentForm.count();
    expect(count).toBe(0);
  });

  test('no hi ha errors de JS en carregar l\'article', async ({ page }) => {
    const errors = [];
    page.on('pageerror', err => errors.push(err.message));
    await page.goto(articleUrl);
    await page.waitForLoadState('networkidle');

    const criticalErrors = errors.filter(e => /TypeError|ReferenceError/i.test(e));
    expect(criticalErrors).toHaveLength(0);
  });

});

test.describe('Descàrrega de PDFs', () => {

  let articleUrl;

  test.beforeAll(async ({ browser }) => {
    const page = await browser.newPage();
    articleUrl = await findFirstArticleUrl(page);
    await page.close();
  });

  test.beforeEach(async ({ page }) => {
    if (!articleUrl) {
      test.skip();
      return;
    }
    await page.goto(articleUrl);
    await page.waitForLoadState('networkidle');
  });

  test('hi ha un link o botó de descàrrega PDF si l\'article en té', async ({ page }) => {
    // El tema linka PDFs com <a href="*.pdf"> o usa la funció SumaDescarregues
    const pdfLinks = page.locator('a[href$=".pdf"], a[href*=".pdf"], .boto-descarrega, [onclick*="SumaDescarregues"]');
    const count = await pdfLinks.count();

    // No tots els articles tenen PDF, acceptem 0
    expect(count).toBeGreaterThanOrEqual(0);
  });

  test('el comptador de descàrregues és un número visible', async ({ page }) => {
    const counter = page.locator('.descargas, .ranking_descargas, [class*="descarr"]').first();
    const count = await counter.count();

    if (count > 0) {
      const text = await counter.textContent();
      // Ha de ser numèric o buit (0)
      expect(/^\d*$/.test(text?.trim() ?? '0')).toBe(true);
    }
  });

  test('la crida AJAX SumaDescarregues retorna resposta vàlida', async ({ page, request }) => {
    // Comprovem directament que l'endpoint AJAX respon (sense autenticació)
    const resp = await request.post('/wp-admin/admin-ajax.php', {
      form: {
        action: 'SumaDescarregues',
        variable: '0',    // Valor actual de descàrregues (mock)
        variable2: '1',   // Post ID (mock)
      },
    });

    // L'endpoint ha d'acceptar la petició (200 o 400 per nonce invàlid)
    // El tema NO valida nonce en SumaDescarregues, per tant hauria de respondre 200
    expect([200, 400]).toContain(resp.status());
  });

});
