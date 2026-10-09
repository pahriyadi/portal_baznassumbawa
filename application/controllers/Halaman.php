<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Halaman extends CI_Controller
{
    /**
     * index() — dipanggil oleh route (:any) via News/_remap jika judul_seo
     * cocok dengan halamanstatis, BUKAN dengan tabel berita.
     *
     * Karena route (:any) sudah menuju News, kita tidak memanggil ini
     * secara langsung. Method ini dipakai jika user mengakses
     * domain.id/halaman/judul (fallback lama).
     */
    public function index()
    {
        $judul_seo = $this->uri->segment(2) ?: $this->uri->segment(1);
        $this->_load($judul_seo);
    }

    /**
     * detail() — URL lama: domain.id/halaman/detail/judul
     * Dipertahankan untuk backward compatibility.
     */
    public function detail()
    {
        $this->_load($this->uri->segment(3));
    }

    /**
     * _load() — logika utama memuat halaman statis
     */
    private function _load($judul_seo)
    {
        $query = $this->model_utama->view_join_one(
            'halamanstatis', 'users', 'username',
            array('judul_seo' => $judul_seo),
            'id_halaman', 'DESC', 0, 1
        );

        if ($query->num_rows() <= 0) {
            redirect('main');
        } else {
            $row = $query->row_array();

            $data['title']       = cetak($row['judul']);
            $data['description'] = cetak_meta($row['isi_halaman'], 0, 200);
            $data['keywords']    = cetak(str_replace(' ', ', ', $row['judul']));
            $data['rows']        = $row;

            // Cookie protection: hitung dibaca hanya sekali per 24 jam per pengunjung
            $cookie_name = 'viewed_halaman_' . $row['id_halaman'];
            if (!$this->input->cookie($cookie_name)) {
                $this->model_utama->update(
                    'halamanstatis',
                    array('dibaca' => $row['dibaca'] + 1),
                    array('id_halaman' => $row['id_halaman'])
                );
                $this->input->set_cookie(array(
                    'name'   => $cookie_name,
                    'value'  => 'yes',
                    'expire' => '86400',
                    'secure' => FALSE
                ));
            }

            $this->template->load(template() . '/template', template() . '/detailhalaman', $data);
        }
    }
}
