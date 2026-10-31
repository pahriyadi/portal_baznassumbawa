const { test, expect } = require('@playwright/test');

test.describe('02. Layanan Zakat, Kalkulator Zakat 6 Tab, Konfirmasi, & Laporan Keuangan', () => {
  test('2.1 Kalkulator Zakat Komprehensif (kalkulator-zakat) menghitung seluruh 6 jenis zakat dengan akurat', async ({ page }) => {
    const response = await page.goto('kalkulator-zakat');
    expect(response.status()).toBe(200);
    await expect(page.locator('h2')).toContainText(/Kalkulator Zakat Komprehensif/i);

    await page.fill('#global_gold_price', '1200000');

    // 1. Tab Zakat Profesi
    await page.fill('#profesi_income', '5000000');
    await expect(page.locator('#val_profesi')).toHaveText('Rp 0');
    await expect(page.locator('#nis_profesi')).toContainText(/Belum Nisab/i);

    await page.fill('#profesi_income', '10000000');
    await expect(page.locator('#val_profesi')).toContainText('250.000');
    await expect(page.locator('#nis_profesi')).toContainText(/Sudah Wajib Zakat/i);

    // 2. Tab Zakat Maal
    await page.click("button.tab-btn:has-text('Maal')");
    await expect(page.locator('#maal')).toBeVisible();
    await page.fill('#maal_wealth', '200000000');
    await expect(page.locator('#val_maal')).toContainText('5.000.000');
    await expect(page.locator('#nis_maal')).toContainText(/Sudah Wajib Zakat/i);

    // 3. Tab Zakat Emas
    await page.click("button.tab-btn:has-text('Emas')");
    await expect(page.locator('#emas')).toBeVisible();
    await page.fill('#emas_weight', '100');
    await expect(page.locator('#val_emas')).toContainText('3.000.000');

    // 4. Tab Zakat Dagang
    await page.click("button.tab-btn:has-text('Dagang')");
    await expect(page.locator('#dagang')).toBeVisible();
    await page.fill('#dagang_assets', '150000000');
    await page.fill('#dagang_debt', '30000000');
    await expect(page.locator('#val_dagang')).toContainText('3.000.000');

    // 5. Tab Zakat Pertanian
    await page.click("button.tab-btn:has-text('Tani')");
    await expect(page.locator('#tani')).toBeVisible();
    await page.fill('#tani_yield', '1000');
    await page.selectOption('#tani_type', '0.05');
    await expect(page.locator('#val_tani')).toHaveText('50.0 kg');

    // 6. Tab Zakat Peternakan
    await page.click("button.tab-btn:has-text('Ternak')");
    await expect(page.locator('#ternak')).toBeVisible();
    await page.selectOption('#ternak_type', 'kambing');
    await page.fill('#ternak_qty', '45');
    await expect(page.locator('#val_ternak')).toContainText('1 Ekor Kambing');
  });

  test('2.2 Halaman Layanan Pembayaran Zakat & Rekening ZIS (bayarzakat) tampil lengkap', async ({ page }) => {
    const response = await page.goto('bayarzakat');
    expect(response.status()).toBe(200);
    await expect(page.locator('h2')).toContainText(/Layanan Pembayaran Zakat/i);
    await expect(page.locator('a[href*="bayarzakat/konfirmasi"]')).toBeVisible();
  });

  test('2.3 Halaman Formulir Konfirmasi Pembayaran ZIS (bayarzakat/konfirmasi) memiliki input lengkap', async ({ page }) => {
    const response = await page.goto('bayarzakat/konfirmasi');
    expect(response.status()).toBe(200);
    await expect(page.locator('h2')).toContainText(/Konfirmasi Pembayaran Zakat/i);

    await expect(page.locator('input[name="nama"]')).toBeVisible();
    await expect(page.locator('input[name="no_telp"]')).toBeVisible();
    await expect(page.locator('select[name="jenis_dana"]')).toBeVisible();
    await expect(page.locator('input[name="jumlah"]')).toBeVisible();
    await expect(page.locator('input[name="bank_pengirim"]')).toBeVisible();
    await expect(page.locator('select[name="rek_tujuan"]')).toBeVisible();
  });

  test('2.4 Halaman Laporan Keuangan (laporan) dapat diakses publik', async ({ page }) => {
    const response = await page.goto('laporan');
    expect(response.status()).toBe(200);
    await expect(page).toHaveTitle(/Laporan Keuangan/i);
    await expect(page.locator('body')).toContainText(/Laporan Keuangan/i);
  });
});
