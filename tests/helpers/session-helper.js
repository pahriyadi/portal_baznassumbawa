const fs = require('fs');
const path = require('path');

const SESSION_DIR = 'C:\\xampp\\tmp';

function readCiSessionFile(sessionId) {
  const filePath = path.join(SESSION_DIR, `ci_session${sessionId}`);
  if (!fs.existsSync(filePath)) return null;
  return fs.readFileSync(filePath, 'utf8');
}

async function getCaptchaFromPageSession(page) {
  const cookies = await page.context().cookies();
  const ciCookie = cookies.find((c) => c.name === 'ci_session');
  if (!ciCookie) return null;

  const content = readCiSessionFile(ciCookie.value);
  if (!content) return null;

  const match = content.match(/mycaptcha\|s:\d+:"([^"]+)"/);
  return match ? match[1] : null;
}

async function clearLoginThrottleInSession(page) {
  const cookies = await page.context().cookies();
  const ciCookie = cookies.find((c) => c.name === 'ci_session');
  if (!ciCookie) return;

  const filePath = path.join(SESSION_DIR, `ci_session${ciCookie.value}`);
  if (!fs.existsSync(filePath)) return;

  let content = fs.readFileSync(filePath, 'utf8');
  content = content.replace(/login_throttle_[a-f0-9]+\|a:\d+:\{[^}]+\}/g, '');
  fs.writeFileSync(filePath, content, 'utf8');
}

async function loginAsAdmin(page, username = 'admin') {
  const cookies = await page.context().cookies();
  let ciCookie = cookies.find((c) => c.name === 'ci_session');

  if (!ciCookie) {
    await page.goto('administrator');
    const updatedCookies = await page.context().cookies();
    ciCookie = updatedCookies.find((c) => c.name === 'ci_session');
  }

  if (!ciCookie) {
    throw new Error('Cookie ci_session tidak ditemukan.');
  }

  const filePath = path.join(SESSION_DIR, `ci_session${ciCookie.value}`);
  const now = Math.floor(Date.now() / 1000);
  const idSession = '21232f297a57a5a743894a0e4a801fc3-20251102153140';

  const sessionPayload =
    `__ci_last_regenerate|i:${now};` +
    `upload_image_file_manager|b:1;` +
    `username|s:${username.length}:"${username}";` +
    `level|s:5:"admin";` +
    `id_session|s:${idSession.length}:"${idSession}";`;

  fs.writeFileSync(filePath, sessionPayload, 'utf8');
}

module.exports = {
  getCaptchaFromPageSession,
  clearLoginThrottleInSession,
  loginAsAdmin,
};
