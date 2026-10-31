const { test, expect } = require('@playwright/test');

test.describe('03. Monitoring GIS ZIS & Amil Virtual AI BAZNAS Sumbawa', () => {
  test('3.1 Dashboard Monitoring Penyaluran & Peta Sebaran ZIS (monitoring-penyaluran-zis) tampil dengan peta Leaflet', async ({ page }) => {
    const response = await page.goto('monitoring-penyaluran-zis');
    expect(response.status()).toBe(200);

    await expect(page.locator('.monitoring-container')).toBeVisible();
    await expect(page.locator('#map-monitoring')).toBeVisible();
    await expect(page.locator('.metric-card').first()).toBeVisible();
  });

  test('3.2 Widget Amil Virtual AI dapat dibuka, menampilkan chip pertanyaan cepat, dan merespons chat pengguna', async ({ page }) => {
    await page.goto('./');

    const launcher = page.locator('#ai-launcher');
    const chatWindow = page.locator('#ai-chat-window');

    await expect(launcher).toBeVisible();
    await expect(chatWindow).toBeHidden();

    await launcher.click();
    await expect(chatWindow).toBeVisible();
    await expect(chatWindow).toContainText(/Amil Virtual BAZNAS Sumbawa/i);

    const inputField = page.locator('#ai-input-field');
    const sendBtn = page.locator('#ai-send-btn');

    await inputField.fill('Berapa nomor rekening resmi BAZNAS Sumbawa?');
    await sendBtn.click();

    await expect(page.locator('.ai-msg-row.user').last()).toContainText('Berapa nomor rekening resmi BAZNAS Sumbawa?');
    await expect(page.locator('.ai-msg-row.bot')).toHaveCount(2, { timeout: 15000 });
    await expect(page.locator('.ai-msg-row.bot').last()).toContainText(/Rekening|Bank|Zakat/i);
  });

  test('3.3 Endpoint API AI Asisten (ai-asisten/chat) mengembalikan JSON valid saat dipanggil via POST', async ({ request }) => {
    const response = await request.post('ai-asisten/chat', {
      form: {
        message: 'Apa saja 5 program bantuan BAZNAS Sumbawa?',
      },
    });
    expect(response.status()).toBe(200);
    const json = await response.json();
    expect(json).toHaveProperty('status', 'success');
    expect(json).toHaveProperty('reply');
    expect(json.reply.length).toBeGreaterThan(20);
  });
});
