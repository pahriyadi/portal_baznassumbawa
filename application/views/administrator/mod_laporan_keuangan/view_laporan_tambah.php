<?php 
    echo "<div class='card card-info'>
      <div class='card-header with-border'>
        <h3 class='card-title'>Tambah Laporan Keuangan</h3>
      </div>
      <div class='card-body'>";
      $attributes = array('class'=>'form-horizontal','role'=>'form');
      echo form_open_multipart($this->uri->segment(1).'/tambah_laporankeuangan',$attributes); 
      echo "<div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Judul Laporan</label>
              <div class='col-sm-10'>
                <input type='text' class='form-control' name='a' required>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Kategori</label>
              <div class='col-sm-10'>
                <select name='b' class='form-control' required>
                  <option value='Tahunan'>Laporan Tahunan</option>
                  <option value='Bulanan'>Laporan Bulanan</option>
                </select>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Tahun</label>
              <div class='col-sm-10'>
                <input type='number' class='form-control' name='c' value='".date('Y')."' required>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Bulan</label>
              <div class='col-sm-10'>
                <select name='d' class='form-control'>
                  <option value=''>- Pilih Bulan (Kosongkan jika Tahunan) -</option>
                  <option value='Januari'>Januari</option>
                  <option value='Februari'>Februari</option>
                  <option value='Maret'>Maret</option>
                  <option value='April'>April</option>
                  <option value='Mei'>Mei</option>
                  <option value='Juni'>Juni</option>
                  <option value='Juli'>Juli</option>
                  <option value='Agustus'>Agustus</option>
                  <option value='September'>September</option>
                  <option value='Oktober'>Oktober</option>
                  <option value='November'>November</option>
                  <option value='Desember'>Desember</option>
                </select>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Keterangan Detail</label>
              <div class='col-sm-10'>
                <textarea id='editor1' class='form-control' name='e' style='height:260px'></textarea>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>File PDF Laporan</label>
              <div class='col-sm-10'>
                <input type='file' class='form-control' name='nama_file' accept='.pdf' required>
                <small class='text-muted'>Maksimal 20MB. Format yang direkomendasikan adalah PDF.</small>
              </div>
            </div>
          </div>
          <div class='card-footer'>
            <button type='submit' name='submit' class='btn btn-info'>Tambahkan</button>
            <a href='javascript:window.history.back();'><button type='button' class='btn btn-default float-right'>Cancel</button></a>
          </div>
        </div>
      </form>";
?>
