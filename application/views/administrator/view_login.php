<?php
$isGoogleAuthActive = isset($isGoogleAuthActive) ? (bool)$isGoogleAuthActive : false;
$googleClientId = isset($googleClientId) ? (string)$googleClientId : '';
$googleLoginMode = isset($googleLoginMode) ? (string)$googleLoginMode : 'both';
$hideManualFormByDefault = ($isGoogleAuthActive && $googleLoginMode === 'google_only');
$favicon = (isset($iden['favicon']) && $iden['favicon'] !== '') ? base_url() . 'asset/images/' . $iden['favicon'] : base_url() . 'asset/images/logobaznas.png';
$nama_web = isset($iden['nama_website']) && $iden['nama_website'] !== '' ? $iden['nama_website'] : 'BAZNAS Kabupaten Sumbawa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Masuk Administrator | <?php echo htmlspecialchars($nama_web); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="<?php echo $favicon; ?>">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin_mu/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script>
      (function() {
          var theme = localStorage.getItem('theme');
          if (theme === 'dark' || (!theme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
              document.documentElement.classList.add('dark-mode');
          }
      })();
  </script>
  <style>
      * {
          border-radius: 0px !important;
      }
      .login-page {
          background-color: #f4f6f9 !important;
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
      }
      .login-box {
          width: 400px;
          max-width: 94%;
      }
      .login-box .card {
          border: 1px solid #b8b8b8 !important;
          box-shadow: none !important;
          background-color: #ffffff !important;
      }
      .login-box .card-header {
          background-color: #ffffff !important;
          border-bottom: 1px solid #b8b8b8 !important;
          padding: 22px 20px !important;
          text-align: center;
      }
      .login-box .card-header .h1 {
          color: #006937 !important;
          font-size: 1.4rem !important;
          font-weight: 700 !important;
          text-decoration: none;
      }
      .login-box .card-header p {
          color: #555555 !important;
      }
      .login-box .card-body {
          padding: 25px 25px !important;
          background-color: #ffffff !important;
      }
      .login-box .form-control {
          border: 1px solid #b8b8b8 !important;
          background-color: #ffffff !important;
          color: #333333 !important;
          box-shadow: none !important;
      }
      .login-box .form-control:focus {
          border-color: #006937 !important;
      }
      .login-box .input-group-text {
          border: 1px solid #b8b8b8 !important;
          border-left: none !important;
          background-color: #f8f9fa !important;
          color: #006937 !important;
      }
      .login-box .btn-primary {
          background-color: #006937 !important;
          border: 1px solid #006937 !important;
          color: #ffffff !important;
          font-weight: 600 !important;
          letter-spacing: 0.4px;
          box-shadow: none !important;
      }
      .login-box .btn-primary:hover {
          background-color: #0b7c44 !important;
          border-color: #0b7c44 !important;
      }
      .login-divider {
          display: flex;
          align-items: center;
          text-align: center;
          color: #777777;
          font-size: 0.78rem;
          font-weight: 600;
          margin: 18px 0;
      }
      .login-divider::before,
      .login-divider::after {
          content: '';
          flex: 1;
          border-bottom: 1px solid #d1d5db;
      }
      .login-divider:not(:empty)::before {
          margin-right: 10px;
      }
      .login-divider:not(:empty)::after {
          margin-left: 10px;
      }
      .google-btn-wrapper {
          display: flex;
          justify-content: center;
          width: 100%;
          min-height: 42px;
      }

      /* Dark Mode Support */
      body.dark-mode.login-page {
          background-color: #14171a !important;
          color: #e5e7eb !important;
      }
      .dark-mode .login-divider {
          color: #9ca3af;
      }
      .dark-mode .login-divider::before,
      .dark-mode .login-divider::after {
          border-bottom-color: #374151;
      }
      .dark-mode .login-box .card,
      .dark-mode .login-box .card-header,
      .dark-mode .login-box .card-body {
          background-color: #1f2328 !important;
          border-color: #374151 !important;
      }
      .dark-mode .login-box .card-header .h1 {
          color: #34d399 !important;
      }
      .dark-mode .login-box .card-header p,
      .dark-mode .login-box .login-box-msg {
          color: #d1d5db !important;
      }
      .dark-mode .login-box .form-control {
          background-color: #262b32 !important;
          border-color: #4b5563 !important;
          color: #ffffff !important;
      }
      .dark-mode .login-box .input-group-text {
          background-color: #262b32 !important;
          border-color: #4b5563 !important;
          color: #34d399 !important;
      }
      .dark-mode #btn-theme-toggle {
          border-color: #4b5563 !important;
          color: #f3f4f6 !important;
          background-color: #1f2328 !important;
      }
  </style>
</head>
<body class="hold-transition login-page">
<script>
    if (document.documentElement.classList.contains('dark-mode')) {
        document.body.classList.add('dark-mode');
    }
</script>

<div style="position: fixed; top: 15px; right: 20px; z-index: 1000;">
    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-theme-toggle" onclick="toggleDarkMode()" title="Ganti Mode Gelap / Terang" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-moon" id="theme-toggle-icon"></i>
    </button>
</div>

<div class="login-box">
  <div class="card">
    <div class="card-header text-center">
      <div class="mb-2">
        <img src="<?php echo $favicon; ?>" alt="Logo BAZNAS" style="width: 58px; height: 58px; object-fit: contain;">
      </div>
      <a href="<?php echo base_url(); ?>" class="h1"><b>PORTAL</b> ADMIN</a>
      <p class="mb-0 text-sm font-weight-bold"><?php echo strtoupper(htmlspecialchars($nama_web)); ?></p>
    </div>

    <div class="card-body login-card-body">
      <p class="login-box-msg">Silakan masuk untuk mengelola portal resmi</p>

      <?php echo $this->session->flashdata('message'); ?>

      <div id="manualLoginSection" style="<?php echo $hideManualFormByDefault ? 'display: none;' : ''; ?>">
        <?php echo form_open($this->uri->segment(1).'/index', array('autocomplete' => 'off')); ?>
        <input type="hidden" name="auth_type" value="password">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="a" placeholder="Username" required <?php echo !$hideManualFormByDefault ? 'autofocus' : ''; ?>>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="b" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <?php if (!empty($image)): ?>
        <div class="mb-2 text-center" style="background:#f8f9fa; padding:8px; border:1px solid #b8b8b8;">
          <?php echo $image; ?>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="security_code" placeholder="Ketik kode captcha di atas" required autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-shield-alt"></span>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="row align-items-center">
          <div class="col-6">
            <a class="text-sm text-muted" data-dismiss="modal" aria-hidden="true" data-toggle="modal" href="#lupapass" data-target="#lupapass">Lupa Password?</a>
          </div>
          <div class="col-6">
            <button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt mr-1"></i> MASUK</button>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>

      <?php if ($isGoogleAuthActive): ?>
        <div id="googleDivider" class="login-divider" style="<?php echo $hideManualFormByDefault ? 'display: none;' : ''; ?>">ATAU MASUK DENGAN</div>
        <?php echo form_open($this->uri->segment(1).'/index', array('id' => 'googleLoginForm', 'class' => 'mb-0')); ?>
          <input type="hidden" name="auth_type" value="google">
          <input type="hidden" name="google_credential" id="google_credential" value="">
        <?php echo form_close(); ?>
        <div id="googleButtonContainer" class="google-btn-wrapper"></div>
        <?php if ($googleLoginMode === 'google_only'): ?>
          <div class="text-center mt-3">
            <a href="javascript:void(0)" onclick="toggleManualAdminLogin()" id="btnToggleManual" class="text-xs text-muted" style="text-decoration: underline;">
              <?php echo $hideManualFormByDefault ? '<i class="fas fa-user-shield mr-1"></i>Login Darurat Admin' : 'Sembunyikan Login Manual'; ?>
            </a>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <p class="mt-4 mb-0 text-xs text-muted text-center">
        &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($nama_web); ?>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>asset/admin_mu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>asset/admin_mu/dist/js/adminlte.min.js"></script>

<?php if ($isGoogleAuthActive): ?>
<script src="https://accounts.google.com/gsi/client" async defer onload="initGoogleSignIn()"></script>
<script>
function handleGoogleCredentialResponse(response) {
    if (response && response.credential) {
        document.getElementById('google_credential').value = response.credential;
        document.getElementById('googleLoginForm').submit();
    }
}

function renderGoogleButton() {
    if (typeof google === 'undefined' || !google.accounts || !google.accounts.id) return;
    var container = document.getElementById('googleButtonContainer');
    if (!container) return;
    var isDark = document.body.classList.contains('dark-mode') || document.documentElement.classList.contains('dark-mode');
    container.innerHTML = '';
    var btnWidth = container.offsetWidth > 0 ? Math.min(348, container.offsetWidth) : 340;
    google.accounts.id.renderButton(container, {
        type: 'standard',
        theme: isDark ? 'filled_black' : 'outline',
        size: 'large',
        text: 'signin_with',
        shape: 'rectangular',
        logo_alignment: 'left',
        width: btnWidth
    });
}

function initGoogleSignIn() {
    if (typeof google === 'undefined' || !google.accounts || !google.accounts.id) return;
    google.accounts.id.initialize({
        client_id: <?php echo json_encode($googleClientId); ?>,
        callback: handleGoogleCredentialResponse,
        auto_select: false,
        cancel_on_tap_outside: true
    });
    renderGoogleButton();
}

function toggleManualAdminLogin() {
    var sec = document.getElementById('manualLoginSection');
    var div = document.getElementById('googleDivider');
    var btn = document.getElementById('btnToggleManual');
    if (!sec) return;
    if (sec.style.display === 'none') {
        sec.style.display = 'block';
        if (div) div.style.display = 'flex';
        if (btn) btn.innerHTML = 'Sembunyikan Login Manual';
        var inputUser = sec.querySelector('input[name="a"]');
        if (inputUser) inputUser.focus();
    } else {
        sec.style.display = 'none';
        if (div) div.style.display = 'none';
        if (btn) btn.innerHTML = '<i class="fas fa-user-shield mr-1"></i>Login Darurat Admin';
    }
}
</script>
<?php endif; ?>

<script>
function toggleDarkMode() {
    var isDark = document.body.classList.toggle('dark-mode');
    document.documentElement.classList.toggle('dark-mode', isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeUI(isDark);
    if (typeof renderGoogleButton === 'function') {
        renderGoogleButton();
    }
}
function updateThemeUI(isDark) {
    var icon = document.getElementById('theme-toggle-icon');
    if (icon) {
        icon.className = isDark ? 'fas fa-sun text-warning' : 'fas fa-moon';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var isDark = document.body.classList.contains('dark-mode') || document.documentElement.classList.contains('dark-mode');
    if (isDark && !document.body.classList.contains('dark-mode')) {
        document.body.classList.add('dark-mode');
    }
    updateThemeUI(isDark);
});
</script>

<div class="modal fade" id="lupapass">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Lupa Password?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php 
        $attributes = array('class'=>'form-horizontal');
        echo form_open('administrator/lupapassword',$attributes); 
      ?>
      <div class="modal-body">
      <div class="card-body">
          <div class="form-group row">
              <label class="col-sm-2 col-form-label">E-mail</label>
              <div class="input-group col-sm-10">
                  <input style='text-transform:lowercase;' placeholder='tulis.email.anda@---.---' type="email" class="required form-control" name="email">
              </div>
          </div>

          <div class="form-group row">
              <label class="col-sm-2 col-form-label"></label>
              <div class="input-group col-sm-10">
              <button style='margin-right:5px' type="submit" name='lupa' class="btn btn-primary">Kirimkan</button> 
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              </div>
          </div>
      </div>
      </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
