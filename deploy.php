<?php
/**
 * =========================================================================
 * GITHUB WEBHOOK AUTO-DEPLOY SCRIPT - BAZNAS KABUPATEN SUMBAWA
 * =========================================================================
 * Endpoint : https://baznassumbawa.id/deploy.php
 * Fungsi   : Menarik update otomatis (git pull origin main) setiap kali
 *            ada git push ke branch main di GitHub.
 * =========================================================================
 */

date_default_timezone_set('Asia/Makassar');
header('Content-Type: application/json; charset=utf-8');

// Secret Token untuk validasi keamanan Webhook GitHub
$webhook_secret = 'BaznasSumbawa2026_AutoDeploy_99!';

// 1. Ambil raw payload dan header signature dari GitHub
$raw_payload = file_get_contents('php://input');
$signature_256 = isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']) ? $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';
$github_event  = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : '';

// Mendukung pemicu manual lewat browser dengan ?key=BaznasSumbawa2026_AutoDeploy_99! (opsional untuk tes)
$manual_key = isset($_GET['key']) ? $_GET['key'] : '';
$is_valid_manual = ($manual_key !== '' && hash_equals($webhook_secret, $manual_key));

// 2. Validasi HMAC SHA-256 dari GitHub Webhook
$is_valid_webhook = false;
if (!empty($signature_256) && !empty($raw_payload)) {
    $expected_signature = 'sha256=' . hash_hmac('sha256', $raw_payload, $webhook_secret);
    $is_valid_webhook = hash_equals($expected_signature, $signature_256);
}

if (!$is_valid_webhook && !$is_valid_manual) {
    http_response_code(403);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Akses ditolak: Signature GitHub Webhook tidak valid.'
    ], JSON_PRETTY_PRINT);
    exit;
}

// 3. Jika event dari GitHub adalah 'ping' (saat pertama kali pasang webhook)
if ($github_event === 'ping') {
    http_response_code(200);
    echo json_encode([
        'status'  => 'success',
        'message' => 'Pong! Webhook BAZNAS Kabupaten Sumbawa berhasil terhubung.'
    ], JSON_PRETTY_PRINT);
    exit;
}

// 4. Pastikan hanya mengeksekusi git pull jika push dilakukan ke branch 'main'
if ($is_valid_webhook && $github_event === 'push') {
    $payload_data = json_decode($raw_payload, true);
    $ref = isset($payload_data['ref']) ? $payload_data['ref'] : '';
    if ($ref !== '' && $ref !== 'refs/heads/main') {
        http_response_code(200);
        echo json_encode([
            'status'  => 'ignored',
            'message' => "Push ke branch '{$ref}' diabaikan (hanya memproses refs/heads/main)."
        ], JSON_PRETTY_PRINT);
        exit;
    }
}

// 5. Pastikan direktori kerja berada di root repository
$repo_dir = __DIR__;
chdir($repo_dir);

// 6. Fungsi helper untuk menjalankan perintah shell di berbagai konfigurasi cPanel
function run_git_command($cmd) {
    $full_cmd = $cmd . ' 2>&1';
    if (function_exists('exec')) {
        $output = [];
        $return_var = 0;
        @exec($full_cmd, $output, $return_var);
        return ['output' => implode("\n", $output), 'code' => $return_var, 'method' => 'exec'];
    }
    if (function_exists('shell_exec')) {
        $out = @shell_exec($full_cmd);
        return ['output' => trim((string)$out), 'code' => 0, 'method' => 'shell_exec'];
    }
    if (function_exists('popen')) {
        $handle = @popen($full_cmd, 'r');
        if ($handle) {
            $out = '';
            while (!feof($handle)) {
                $out .= fread($handle, 2096);
            }
            pclose($handle);
            return ['output' => trim($out), 'code' => 0, 'method' => 'popen'];
        }
    }
    return ['output' => 'Tidak ada fungsi eksekusi shell (exec/shell_exec/popen) yang diizinkan di PHP hosting ini.', 'code' => 1, 'method' => 'none'];
}

// Cari lokasi binary git di server cPanel
$git_bin = 'git';
if (file_exists('/usr/local/cpanel/3rdparty/bin/git')) {
    $git_bin = '/usr/local/cpanel/3rdparty/bin/git';
} elseif (file_exists('/usr/bin/git')) {
    $git_bin = '/usr/bin/git';
}

// 7. Jalankan git fetch & git pull
$fetch_result = run_git_command("{$git_bin} fetch origin main");
$pull_result  = run_git_command("{$git_bin} reset --hard origin/main && {$git_bin} pull origin main");

// 8. Catat ke file log (application/logs/deploy.log)
$log_dir  = $repo_dir . '/application/logs';
$log_file = $log_dir . '/deploy.log';
$log_entry = sprintf(
    "[%s WITA] Event: %s | Method: %s | Code: %d\nOutput:\n%s\n%s\n",
    date('Y-m-d H:i:s'),
    ($is_valid_manual ? 'manual_trigger' : ($github_event ?: 'webhook')),
    $pull_result['method'],
    $pull_result['code'],
    $pull_result['output'],
    str_repeat('-', 60)
);
if (is_dir($log_dir) && is_writable($log_dir)) {
    @file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// 9. Kembalikan respons JSON ke GitHub
http_response_code($pull_result['code'] === 0 ? 200 : 500);
echo json_encode([
    'status'    => ($pull_result['code'] === 0 ? 'success' : 'error'),
    'timestamp' => date('Y-m-d H:i:s') . ' WITA',
    'method'    => $pull_result['method'],
    'git_bin'   => $git_bin,
    'output'    => $pull_result['output']
], JSON_PRETTY_PRINT);

