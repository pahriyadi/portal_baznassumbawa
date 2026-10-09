            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Daftar Laporan Keuangan BAZNAS</h3>
                  <div class="card-tools">
                    <a class='btn btn-primary btn-sm' href='<?php echo base_url().$this->uri->segment(1); ?>/tambah_laporankeuangan'>Tambahkan Data</a>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Judul Laporan</th>
                        <th>Kategori</th>
                        <th>Bulan/Tahun</th>
                        <th>Nama File</th>
                        <th>Tanggal Posting</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                    $tgl_posting = tgl_indo($row['tgl_posting']);
                    $periode = $row['kategori'] == 'Tahunan' ? $row['tahun'] : $row['bulan'] . ' ' . $row['tahun'];
                    echo "<tr><td>$no</td>
                              <td>$row[judul]</td>
                              <td>$row[kategori]</td>
                              <td>$periode</td>
                              <td><a target='_BLANK' href='".base_url()."asset/laporan/$row[nama_file]'>$row[nama_file]</a></td>
                              <td>$tgl_posting</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url().$this->uri->segment(1)."/edit_laporankeuangan/$row[id_laporan]'><i class='fas fa-edit'></i></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url().$this->uri->segment(1)."/delete_laporankeuangan/$row[id_laporan]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fas fa-trash-alt'></i></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
