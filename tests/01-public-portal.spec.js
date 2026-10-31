const { test, expect } = require('@playwright/test');

test.describe('01. Portal Publik, Konten Berita, & Sitemap XML', () => {
  test('1.1 Beranda utama (Main) memuat elemen identitas BAZNAS, navigasi, dan konten berita', async ({ page }) => {
    const response = await page.goto('./');
    expect(response.status()).toBe(200);

    await expect(page).toHaveTitle(/BAZNAS/i);
    await expect(page.locator('body')).toContainText(/BAZNAS/i);
    await expect(page.locator('#baznas-ai-widget')).toBeVisible();
  });

  test('1.2 Halaman Semua Berita (berita) dan fitur pencarian berita berfungsi', async ({ page }) => {
    const response = await page.goto('berita');
    expect(response.status()).toBe(200);
    await expect(page).toHaveTitle(/Berita/i);
    await expect(page.locator('.main-page')).toBeVisible();
  });

  test('1.3 Modul Agenda (agenda), Album Foto (albums), Video (playlist), & Download (download) dapat diakses', async ({ page }) => {
    const routes = [
      { url: 'agenda', text: /Agenda/i },
      { url: 'albums', text: /Album/i },
      { url: 'playlist', text: /Playlist|Video/i },
      { url: 'download', text: /Download/i },
      { url: 'polling', text: /Polling/i },
    ];

    for (const r of routes) {
      const res = await page.goto(r.url);
      expect(res.status()).toBe(200);
      await expect(page).toHaveTitle(r.text);
    }
  });

  test('1.4 Modul Konsultasi (konsultasi) dan Hubungi Kami (hubungi) menampilkan formulir & captcha', async ({ page }) => {
    const resKonsul = await page.goto('konsultasi');
    expect(resKonsul.status()).toBe(200);
    await expect(page).toHaveTitle(/Konsultasi/i);

    const resHubungi = await page.goto('hubungi');
    expect(resHubungi.status()).toBe(200);
    await expect(page).toHaveTitle(/Hubungi Kami/i);
    await expect(page.locator('input[name="a"], input[name="security_code"]').first()).toBeVisible();
  });

  test('1.5 Sitemap XML (sitemap.xml) menghasilkan dokumen XML standar untuk SEO', async ({ request }) => {
    const response = await request.get('sitemap.xml');
    expect(response.status()).toBe(200);
    const body = await response.text();
    expect(body).toContain('<?xml version="1.0" encoding="UTF-8" ?>');
    expect(body).toContain('<urlset');
    expect(body).toContain('<loc>');
  });
});
