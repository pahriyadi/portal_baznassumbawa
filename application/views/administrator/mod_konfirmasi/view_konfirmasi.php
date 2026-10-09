<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Konfirmasi Pembayaran Zakat / ZIS</h3>
  </div><!-- /.card-header -->
  <div class="card-body">
    <div class="table-responsive">
      <table id="example1" class="table table-sm table-bordered table-striped">
        <thead>
          <tr>
            <th style='width:30px'>No</th>
            <th>Muzakki / Kontak</th>
            <th>Jenis Dana</th>
            <th>Jumlah</th>
            <th>Bank Pengirim & Tujuan</th>
            <th>Tgl Transfer</th>
            <th>Bukti</th>
            <th>Status</th>
            <th style='width:120px'>Aksi</th>
          </tr>
        </thead>
        <tbody>
      <?php 
        $no = 1;
        foreach ($record->result_array() as $row){
          // Status Badge
          if ($row['status'] == 'Valid') {
            $status = "<span class='badge badge-success'>Valid</span>";
          } elseif ($row['status'] == 'Tidak Valid') {
            $status = "<span class='badge badge-danger'>Tidak Valid</span>";
          } else {
            $status = "<span class='badge badge-warning'>Pending</span>";
          }

          // Bukti Transfer file
          $bukti = "";
          if (!empty($row['bukti_transfer'])) {
            $bukti = "<a href='".base_url()."asset/bukti_transfer/$row[bukti_transfer]' target='_blank'>
                        <img src='".base_url()."asset/bukti_transfer/$row[bukti_transfer]' style='width:60px; height:60px; object-fit:cover; border-radius:4px; border:1px solid #ddd;' title='Klik untuk memperbesar'>
                      </a>";
          } else {
            $bukti = "<span class='text-muted'>Tidak ada</span>";
          }

          $formatted_date = date('d-m-Y', strtotime($row['tanggal_transfer']));

          echo "<tr>
                  <td>$no</td>
                  <td>
                    <strong>".htmlspecialchars($row['nama'])."</strong><br>
                    <small class='text-muted'>Email: ".htmlspecialchars($row['email'])."</small><br>
                    <small class='text-muted'>WA/HP: ".htmlspecialchars($row['no_telp'])."</small>
                  </td>
                  <td>".htmlspecialchars($row['jenis_dana'])."</td>
                  <td><strong>Rp ".number_format($row['jumlah'], 0, ',', '.')."</strong></td>
                  <td>
                    <small>Pengirim: ".htmlspecialchars($row['bank_pengirim'])."</small><br>
                    <small>Tujuan: ".htmlspecialchars($row['rek_tujuan'])."</small>
                  </td>
                  <td>".$formatted_date."</td>
                  <td align='center'>$bukti</td>
                  <td align='center'>$status</td>
                  <td>
                    <div class='btn-group btn-group-sm' style='display:flex; gap:4px;'>
                      <a class='btn btn-success btn-xs' title='Set Valid' href='".base_url().$this->uri->segment(1)."/validasi_konfirmasipembayaran/$row[id_konfirmasi]/valid' onclick=\"return confirm('Tandai pembayaran ini VALID?')\"><span class='fas fa-check'></span></a>
                      <a class='btn btn-warning btn-xs text-white' title='Set Tidak Valid' href='".base_url().$this->uri->segment(1)."/validasi_konfirmasipembayaran/$row[id_konfirmasi]/tidakvalid' onclick=\"return confirm('Tandai pembayaran ini TIDAK VALID?')\"><span class='fas fa-times'></span></a>
                      <a class='btn btn-danger btn-xs' title='Hapus' href='".base_url().$this->uri->segment(1)."/delete_konfirmasipembayaran/$row[id_konfirmasi]' onclick=\"return confirm('Yakin ingin menghapus data ini?')\"><span class='fas fa-trash'></span></a>
                    </div>
                  </td>
              </tr>";
          $no++;
        }
      ?>
      </tbody>
    </table>
  </div>
</div>
</div>
