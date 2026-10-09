<?php 
    echo "<div class='card card-info'>
      <div class='card-header with-border'>
        <h3 class='card-title'>Edit Laporan Keuangan</h3>
      </div>
      <div class='card-body'>";
      $attributes = array('class'=>'form-horizontal','role'=>'form');
      echo form_open_multipart($this->uri->segment(1).'/edit_laporankeuangan',$attributes); 
      echo "<div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Judul Laporan</label>
              <div class='col-sm-10'>
                <input type='hidden' name='id' value='$rows[id_laporan]'>
                <input type='text' class='form-control' name='a' value='$rows[judul]' required>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Kategori</label>
              <div class='col-sm-10'>
                <select name='b' class='form-control' required>
                  <option value='Tahunan' ".($rows['kategori']=='Tahunan'?'selected':'').">Laporan Tahunan</option>
                  <option value='Bulanan' ".($rows['kategori']=='Bulanan'?'selected':'').">Laporan Bulanan</option>
                </select>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Tahun</label>
              <div class='col-sm-10'>
                <input type='number' class='form-control' name='c' value='$rows[tahun]' required>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Bulan</label>
              <div class='col-sm-10'>
                <select name='d' class='form-control'>
                  <option value=''>- Pilih Bulan (Kosongkan jika Tahunan) -</option>";
                  $bulans = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                  foreach($bulans as $b){
                      if ($rows['bulan']==$b){
                          echo "<option value='$b' selected>$b</option>";
                      }else{
                          echo "<option value='$b'>$b</option>";
                      }
                  }
          echo "</select>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Keterangan Detail</label>
              <div class='col-sm-10'>
                <textarea id='editor1' class='form-control' name='e' style='height:260px'>$rows[keterangan]</textarea>
              </div>
            </div>
            <div class='form-group row'>
              <label class='col-sm-2 col-form-label'>Ganti File PDF</label>
              <div class='col-sm-10'>
                <input type='file' class='form-control' name='nama_file' accept='.pdf'>
                <small class='text-muted'>Kosongkan jika tidak ingin mengganti file laporan saat ini (<a href='".base_url()."asset/laporan/$rows[nama_file]' target='_BLANK'>File Saat Ini</a>).</small>
              </div>
            </div>
          </div>
          <div class='card-footer'>
            <button type='submit' name='submit' class='btn btn-info'>Update</button>
            <a href='".base_url($this->uri->segment(1).'/laporankeuangan')."'><button type='button' class='btn btn-default float-right'>Cancel</button></a>
          </div>
        </div>
      </form>";
?>
