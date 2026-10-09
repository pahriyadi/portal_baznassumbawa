<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Rekening Zakat</h3>
    <div class="card-tools">
      <a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url().$this->uri->segment(1); ?>/tambah_rekeningzakat'>Tambahkan Data</a>
    </div>
  </div><!-- /.card-header -->
  <div class="card-body">
    <div class="table-responsive">
      <table id="example1" class="table table-sm table-striped">
        <thead>
          <tr>
            <th style='width:40px'>No</th>
            <th>Nama Bank</th>
            <th>Rekening Zakat</th>
            <th>Rekening Infaq</th>
            <th style='width:70px'>Action</th>
          </tr>
        </thead>
        <tbody>
      <?php 
        $no = 1;
        foreach ($record as $row){
        echo "<tr><td>$no</td>
                  <td>$row[nama_bank]</td>
                  <td>$row[rek_zakat]</td>
                  <td>$row[rek_infaq]</td>
                  <td><center>
                    <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url().$this->uri->segment(1)."/edit_rekeningzakat/$row[id_rekening]'><span class='nav-icon fas fa-edit'></span></a>
                    <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url().$this->uri->segment(1)."/delete_rekeningzakat/$row[id_rekening]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='nav-icon fas fa-trash-alt'></span></a>
                  </center></td>
              </tr>";
          $no++;
        }
      ?>
      </tbody>
    </table>
  </div></div></div>
