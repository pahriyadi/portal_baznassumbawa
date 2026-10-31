const { test, expect } = require('@playwright/test');
const { getCaptchaFromPageSession, clearLoginThrottleInSession } = require('./helpers/session-helper');

test.describe('04. Halaman Login Administrator, Dark Mode, Captcha, & Google OAuth 2.0', () => {
  test('4.1 Halaman administrator dapat diakses langsung tanpa parameter kunci rahasia dan menampilkan form + Google Login', async ({ page }) => {
    const response = await page.goto('administrator');
    expect(response.status()).toBe(200);

    await expect(page).toHaveTitle(/Masuk Administrator/i);
    await expect(page.locator('.login-box')).toBeVisible();
    await expect(page.locator('input[name="a"]')).toBeVisible();
    await expect(page.locator('input[name="b"]')).toBeVisible();
    await expect(page.locator('input[name="security_code"]')).toBeVisible();

    await expect(page.locator('#googleLoginForm')).toBeAttached();
    await expect(page.locator('#googleButtonContainer')).toBeVisible();
  });

  test('4.2 Toggle Dark Mode / Light Mode pada halaman Login Administrator berfungsi dengan baik', async ({ page }) => {
    await page.goto('administrator');

    const toggleBtn = page.locator('#btn-theme-toggle');
    await expect(toggleBtn).toBeVisible();

    const initialDark = await page.evaluate(() => document.body.classList.contains('dark-mode'));
    await toggleBtn.click();
    const afterClickDark = await page.evaluate(() => document.body.classList.contains('dark-mode'));
    expect(afterClickDark).toBe(!initialDark);

    await toggleBtn.click();
    const restoredDark = await page.evaluate(() => document.body.classList.contains('dark-mode'));
    expect(restoredDark).toBe(initialDark);
  });

  test('4.3 Validasi Captcha menolak kode keamanan yang salah', async ({ page }) => {
    await page.goto('administrator');
    await clearLoginThrottleInSession(page);

    await page.fill('input[name="a"]', 'admin');
    await page.fill('input[name="b"]', 'salahpassword');
    await page.fill('input[name="security_code"]', 'XXXXX');
    await page.click('button[name="submit"]');

    await expect(page.locator('.alert-danger')).toContainText(/Captcha/i);
  });

  test('4.4 Validasi Login Manual menolak password salah namun Captcha benar (Brute-Force Counter)', async ({ page }) => {
    await page.goto('administrator');
    await clearLoginThrottleInSession(page);

    const captchaWord = await getCaptchaFromPageSession(page);
    expect(captchaWord).toBeTruthy();

    await page.fill('input[name="a"]', 'admin');
    await page.fill('input[name="b"]', 'wrong_password_123');
    await page.fill('input[name="security_code"]', captchaWord);
    await page.click('button[name="submit"]');

    await expect(page.locator('.alert-danger')).toContainText(/Username atau Password Salah/i);
    await clearLoginThrottleInSession(page);
  });

  test('4.5 Validasi Google Login menolak token JWT Google yang tidak valid/palsu secara aman di sisi server', async ({ page }) => {
    await page.goto('administrator');
    await clearLoginThrottleInSession(page);

    await page.evaluate(() => {
      document.getElementById('google_credential').value = 'invalid.jwt.token';
      document.getElementById('googleLoginForm').submit();
    });

    await page.waitForLoadState('networkidle');
    await expect(page.locator('.alert-danger')).toContainText(/Google|kredensial|Token/i);
    await clearLoginThrottleInSession(page);
  });
});
