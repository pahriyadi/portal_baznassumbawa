<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller {
    
    public function index() {
        $this->load->database();
        $this->load->helper('url');
        
        $data['berita'] = $this->db->query("SELECT judul_seo, tanggal, jam FROM berita WHERE status='Y' ORDER BY id_berita DESC LIMIT 1000")->result_array();
        $data['kategori'] = $this->db->query("SELECT kategori_seo FROM kategori WHERE aktif='Y'")->result_array();
        $data['tag'] = $this->db->query("SELECT tag_seo FROM tag")->result_array();
        
        header("Content-Type: text/xml;charset=UTF-8");
        
        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
        
        // Homepage
        echo '<url>';
        echo '<loc>' . base_url() . '</loc>';
        echo '<changefreq>daily</changefreq>';
        echo '<priority>1.0</priority>';
        echo '</url>';
        
        // Kategori
        foreach($data['kategori'] as $k) {
            echo '<url>';
            echo '<loc>' . base_url() . 'kategori/detail/' . $k['kategori_seo'] . '</loc>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
        
        // Berita
        foreach($data['berita'] as $b) {
            $date = date('c', strtotime($b['tanggal'] . ' ' . $b['jam']));
            echo '<url>';
            echo '<loc>' . base_url() . $b['judul_seo'] . '</loc>';
            echo '<lastmod>' . $date . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.9</priority>';
            echo '</url>';
        }
        
        // Tag
        foreach($data['tag'] as $t) {
            echo '<url>';
            echo '<loc>' . base_url() . 'tag/detail/' . $t['tag_seo'] . '</loc>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>';
        }
        
        echo '</urlset>';
    }
}
