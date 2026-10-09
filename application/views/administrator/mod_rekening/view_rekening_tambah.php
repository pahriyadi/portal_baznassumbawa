<?php 
    echo "
              <div class='card card-info'>
                <div class='card-header with-border'>
                  <h3 class='card-title'>Tambah Rekening Zakat</h3>
                </div>
              <div class='card-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/tambah_rekeningzakat',$attributes); 
          echo "
                  <table class='table table-sm table-borderless'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Nama Bank</th>    <td><input type='text' class='form-control' name='a' placeholder='Contoh: Bank NTB Syariah' required></td></tr>
                    <tr><th scope='row'>No. Rek Zakat</th>    <td><input type='text' class='form-control' name='b' placeholder='000.00.000000'></td></tr>
                    <tr><th scope='row'>No. Rek Infaq</th>    <td><input type='text' class='form-control' name='c' placeholder='000.00.000000'></td></tr>
                  </tbody>
                  </table>
                </div>
              
              <div class='card-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Tambahkan</button>
                    <a href='".base_url().$this->uri->segment(1)."/rekeningzakat'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div></div>";
            echo form_close();
?>
