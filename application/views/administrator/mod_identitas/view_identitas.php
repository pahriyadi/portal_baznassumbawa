<?php 
    $g_enabled = isset($record['google_login_enabled']) ? (string)$record['google_login_enabled'] : '0';
    $g_mode = isset($record['google_login_mode']) ? (string)$record['google_login_mode'] : 'both';
    $g_client_id = isset($record['google_client_id']) ? htmlspecialchars((string)$record['google_client_id'], ENT_QUOTES, 'UTF-8') : '';
    $host_origin = isset($_SERVER['HTTP_HOST']) ? htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8') : 'baznassumbawa.id';

    echo "<div class='card card-info'>
                <div class='card-header with-border'>
                  <h3 class='card-title'><i class='fas fa-globe mr-2'></i>Identitas Website & Pengaturan Login Google</h3>
                </div>
              <div class='card-body'>";
              echo $this->session->flashdata('message');
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
          echo "<table class='table table-sm table-borderless'>
                  <tbody>
                    <input type='hidden' name='id' value='$record[id_identitas]'>
                    <tr><th width='180px' scope='row'>Nama Website</th>   <td><input type='text' class='form-control' name='a' value='$record[nama_website]'></td></tr>
                    <tr><th scope='row'>Email</th>                        <td><input type='email' class='form-control' name='b' value='$record[email]'></td></tr>
                    <tr><th scope='row'>Domain</th>                       <td><input type='url' class='form-control' name='c' value='$record[url]'></td></tr>
                    <tr><th scope='row'>Sosial Network <small><i>(Pisah dengan Koma)</i></small></th>               <td><textarea class='form-control' name='d' style='height:100px'>$record[facebook]</textarea></td></tr>
                    <tr><th scope='row'>No Rekening</th>                  <td><input type='number' class='form-control' name='e' value='$record[rekening]'></td></tr>
                    <tr><th scope='row'>No Telpon</th>                    <td><input type='text' class='form-control' name='f' value='$record[no_telp]'></td></tr>   
                    <tr><th scope='row'>Meta Deskripsi</th>               <td><input type='text' class='form-control' name='g' value='$record[meta_deskripsi]'></td></tr>           
                    <tr><th scope='row'>Meta Keyword</th>                 <td><input type='text' class='form-control' name='h' value='$record[meta_keyword]'></td></tr>
                    <tr><th scope='row'>Google Maps</th>                  <td><textarea class='form-control' name='i' style='height:80px'>$record[maps]</textarea></td></tr>
                    <tr><th scope='row'>Google Analytics / Tag</th>       <td><textarea class='form-control' name='ga' style='height:120px' placeholder='Paste kode <!-- Google tag (gtag.js) --> di sini...'>$record[google_analytics]</textarea><small class='text-muted'>Salin & tempel tag Google Analytics / GTM lengkap (termasuk tag &lt;script&gt;). Akan ditampilkan otomatis setelah elemen &lt;head&gt; di semua halaman.</small></td></tr>
                    <tr><th scope='row'>Google AdSense / Verifikasi</th>  <td><textarea class='form-control' name='ads' style='height:100px' placeholder='Paste kode &lt;script async src=&quot;https://pagead2.googlesyndication.com/...&quot;&gt;&lt;/script&gt; di sini...'>$record[google_adsense]</textarea><small class='text-muted'>Salin & tempel cuplikan kode verifikasi Google AdSense / script iklan otomatis. Akan ditampilkan otomatis di dalam elemen &lt;head&gt;.</small></td></tr>
                    <tr><th scope='row'>Favicon</th>                      <td><input type='file' class='form-control' name='j' value='$record[favicon]'><hr style='margin:5px'>
                                                                              Favicon Aktif Saat ini : <img style='width:32px; height:32px' src='".base_url()."asset/images/$record[favicon]'></td></tr>
                  </tbody>
                  </table>

                  <hr class='my-4' style='border-top: 1px solid #b8b8b8;'>

                  <h5 class='font-weight-bold text-success mb-3'>
                      <i class='fab fa-google mr-2'></i>Pengaturan Login Akun Google (OAuth 2.0 / Google Identity)
                  </h5>
                  <div class='row'>
                      <div class='col-md-3'>
                          <div class='form-group'>
                              <label><i class='fas fa-toggle-on mr-1'></i>Status Login Google</label>
                              <select class='form-control' name='google_login_enabled'>
                                  <option value='0' ".($g_enabled === '0' ? 'selected' : '').">Nonaktif</option>
                                  <option value='1' ".($g_enabled === '1' ? 'selected' : '').">Aktif</option>
                              </select>
                              <small class='text-muted d-block mt-1'>Hanya mengizinkan email yang terdaftar di <strong>Manajemen Users</strong>.</small>
                          </div>
                      </div>
                      <div class='col-md-4'>
                          <div class='form-group'>
                              <label><i class='fas fa-shield-alt mr-1'></i>Metode Login yang Diizinkan</label>
                              <select class='form-control' name='google_login_mode'>
                                  <option value='both' ".($g_mode === 'both' ? 'selected' : '').">Keduanya (Manual & Google)</option>
                                  <option value='staff_google_only' ".($g_mode === 'staff_google_only' ? 'selected' : '').">Wajib Google (Staf) + Manual (Admin)</option>
                                  <option value='google_only' ".($g_mode === 'google_only' ? 'selected' : '').">Hanya Akun Google (Sembunyikan Form Manual)</option>
                              </select>
                              <small class='text-muted d-block mt-1'>Mengatur apakah form Username & Password tetap ditampilkan atau dibatasi.</small>
                          </div>
                      </div>
                      <div class='col-md-5'>
                          <div class='form-group'>
                              <label><i class='fas fa-key mr-1'></i>Google OAuth Client ID</label>
                              <input type='text' class='form-control' name='google_client_id' value='$g_client_id' placeholder='Contoh: 1234567890-abcdefg.apps.googleusercontent.com'>
                              <small class='text-muted d-block mt-1'>
                                  Dapatkan dari <a href='https://console.cloud.google.com/apis/credentials' target='_blank' rel='noopener'>Google Cloud Console</a> (Authorized JS origins: <code>https://$host_origin</code>).
                              </small>
                          </div>
                      </div>
                  </div>
              
              <div class='card-footer'>
                    <button type='submit' name='submit' class='btn btn-info'><i class='fas fa-save mr-1'></i>Update Pengaturan</button>
                    <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
              </div>
            </div></div>";
            echo form_close();
