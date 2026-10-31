const { test, expect } = require('@playwright/test');
const { loginAsAdmin } = require('./helpers/session-helper');

test.describe('05. Seluruh Modul Backend Administrator BAZNAS Kabupaten Sumbawa', () => {
  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page, 'admin');
  });

  test('5.1 Dashboard Admin (administrator/home) & API Realtime Traffic Stats berfungsi dengan Paper White Design', async ({ page }) => {
    const response = await page.goto('administrator/home');
    expect(response.status()).toBe(200);

    await expect(page.locator('.rt-dashboard-header')).toBeVisible();
    await expect(page.locator('.rt-dashboard-header')).toContainText(/BAZNAS Real-Time Traffic Intelligence/i);
    await expect(page.locator('#rt-online-val')).toBeVisible();
    await expect(page.locator('aside.main-sidebar')).toHaveClass(/sidebar-light-success/);

    const apiRes = await page.goto('administrator/realtime_stats');
    expect(apiRes.status()).toBe(200);
    const statsJson = await apiRes.json();
    expect(statsJson).toHaveProperty('status', 'success');
    expect(statsJson).toHaveProperty('online');
  });

  test('5.2 Modul Menu Utama: Identitas Website (termasuk Pengaturan Login Google), Menu Website, Halaman Baru, Rekening ZIS, Konfirmasi ZIS, & Laporan Keuangan', async ({ page }) => {
    await page.goto('administrator/identitaswebsite');
    await expect(page.locator('.card-title')).toContainText(/Identitas Website/i);
    await expect(page.locator('select[name="google_login_enabled"]')).toBeVisible();
    await expect(page.locator('select[name="google_login_mode"]')).toBeVisible();
    await expect(page.locator('input[name="google_client_id"]')).toBeVisible();

    const mainModules = [
      'administrator/menuwebsite',
      'administrator/halamanbaru',
      'administrator/rekeningzakat',
      'administrator/konfirmasipembayaran',
      'administrator/laporankeuangan',
    ];

    for (const modUrl of mainModules) {
      const res = await page.goto(modUrl);
      expect(res.status()).toBe(200);
      await expect(page.locator('.content-wrapper')).toBeVisible();
    }
  });

  test('5.3 Modul Berita & Media: Berita, Kategori, Tag, Komentar, Sensor, Berita Foto (Album), & Gallery', async ({ page }) => {
    const beritaModules = [
      'administrator/listberita',
      'administrator/kategoriberita',
      'administrator/tagberita',
      'administrator/komentarberita',
      'administrator/sensorkomentar',
      'administrator/album',
      'administrator/gallery',
    ];

    for (const modUrl of beritaModules) {
      const res = await page.goto(modUrl);
      expect(res.status()).toBe(200);
      await expect(page.locator('.content-wrapper .card').first()).toBeVisible();
    }
  });

  test('5.4 Modul Video, Modul Iklan/Slider, & Modul Web (Logo, Template, Background)', async ({ page }) => {
    const mediaAndWebModules = [
      'administrator/playlist',
      'administrator/video',
      'administrator/tagvideo',
      'administrator/komentarvideo',
      'administrator/iklanatas',
      'administrator/iklanhome',
      'administrator/iklansidebar',
      'administrator/banner',
      'administrator/slider',
      'administrator/logowebsite',
      'administrator/templatewebsite',
      'administrator/background',
    ];

    for (const modUrl of mediaAndWebModules) {
      const res = await page.goto(modUrl);
      expect(res.status()).toBe(200);
      await expect(page.locator('.content-wrapper .card').first()).toBeVisible();
    }
  });

  test('5.5 Modul Interaksi & Modul Users (Agenda, Sekilas Info, Polling, Download, Alamat, Pesan Masuk, Manajemen User, Manajemen Modul, & Logout)', async ({ page }) => {
    const interactionAndUserModules = [
      'administrator/agenda',
      'administrator/sekilasinfo',
      'administrator/jajakpendapat',
      'administrator/download',
      'administrator/alamat',
      'administrator/pesanmasuk',
      'administrator/manajemenuser',
      'administrator/tambah_manajemenuser',
      'administrator/manajemenmodul',
    ];

    for (const modUrl of interactionAndUserModules) {
      const res = await page.goto(modUrl);
      expect(res.status()).toBe(200);
      await expect(page.locator('.content-wrapper .card').first()).toBeVisible();
    }

    await page.goto('administrator/logout');
    await expect(page).not.toHaveURL(/administrator\/home/i);
  });
});
