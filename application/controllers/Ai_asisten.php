<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Ai_asisten
 * Modul Asisten Virtual AI BAZNAS Kabupaten Sumbawa (Amil AI)
 * Menggunakan model Google Gemini 1.5 Flash API (Free Tier)
 * Dilengkapi dengan Intelligent Local Fallback Engine (berjalan otomatis jika offline/tanpa API key)
 */
class Ai_asisten extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load database dan library yang dibutuhkan
        $this->load->database();
    }

    public function index() {
        // Alihkan jika diakses langsung tanpa POST
        if ($this->input->method() !== 'post') {
            redirect(base_url());
            return;
        }
        $this->chat();
    }

    public function chat() {
        // Set header JSON response
        header('Content-Type: application/json; charset=utf-8');

        if ($this->input->method() !== 'post') {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Hanya metode POST yang diizinkan.'
            ));
            return;
        }

        // 1. Rate Limiting Protection (Maks 20 request per menit per IP)
        $ip = $this->input->ip_address();
        $rate_key = 'ai_rate_' . md5($ip);
        $rate_data = $this->session->userdata($rate_key);
        $now = time();

        if ($rate_data && isset($rate_data['count']) && $rate_data['count'] >= 20 && ($now - $rate_data['start']) < 60) {
            echo json_encode(array(
                'status' => 'error',
                'reply' => "Afwan, Anda mengirim pesan terlalu cepat. Mohon tunggu beberapa detik sebelum bertanya kembali.",
                'quick_actions' => array()
            ));
            return;
        }

        if (!$rate_data || ($now - $rate_data['start']) >= 60) {
            $this->session->set_userdata($rate_key, array('count' => 1, 'start' => $now));
        } else {
            $this->session->set_userdata($rate_key, array('count' => $rate_data['count'] + 1, 'start' => $rate_data['start']));
        }

        // 2. Ambil dan Sanitasi Pesan Pengguna
        $user_message = trim($this->input->post('message', TRUE));
        if (empty($user_message)) {
            echo json_encode(array(
                'status' => 'error',
                'reply' => "Silakan ketikkan pertanyaan atau topik yang ingin Anda tanyakan seputar ZIS dan layanan BAZNAS Sumbawa.",
                'quick_actions' => array()
            ));
            return;
        }

        // 3. Ambil Data Realtime dari Database BAZNAS Sumbawa
        $identitas = $this->db->get_where('identitas', array('id_identitas' => 1))->row_array();
        $nama_web  = !empty($identitas['nama_website']) ? $identitas['nama_website'] : 'BAZNAS Kabupaten Sumbawa';
        $no_telp   = !empty($identitas['no_telp']) ? $identitas['no_telp'] : '081936955747';
        $email     = !empty($identitas['email']) ? $identitas['email'] : 'baznaskab.sumbawa@baznas.go.id';
        $alamat_db = $this->db->get_where('mod_alamat', array('id_alamat' => 1))->row_array();
        $alamat_text = "Jl. Hasanuddin No. 01 Kelurahan Bugis Kecamatan Sumbawa (Eks kantor DPRD Lama)";
        if (!empty($alamat_db['alamat'])) {
            $clean_addr = strip_tags($alamat_db['alamat']);
            if (preg_match('/(Jl\.[^<&]+(?:\([^)]+\))?)/i', $clean_addr, $m_addr)) {
                $alamat_text = rtrim(trim($m_addr[1]), '. ');
            }
        }

        // Format WA
        $clean_wa = preg_replace('/[^0-9]/', '', $no_telp);
        if (substr($clean_wa, 0, 1) === '0') {
            $clean_wa = '62' . substr($clean_wa, 1);
        }

        // Rekening ZIS Realtime
        $rekening_list = $this->db->get('rekening_zakat')->result_array();
        $str_rekening = "";
        if (!empty($rekening_list)) {
            foreach ($rekening_list as $rk) {
                $b_name = $rk['nama_bank'] ?? 'Bank';
                $rek_z  = $rk['rek_zakat'] ?? '';
                $rek_i  = $rk['rek_infaq'] ?? '';
                if (!empty($rek_z) && !empty($rek_i) && $rek_z === $rek_i) {
                    $str_rekening .= "- " . $b_name . ": " . $rek_z . " (Zakat & Infaq)\n";
                } else {
                    if (!empty($rek_z)) $str_rekening .= "- " . $b_name . " (Zakat): " . $rek_z . "\n";
                    if (!empty($rek_i)) $str_rekening .= "- " . $b_name . " (Infaq/Sedekah): " . $rek_i . "\n";
                }
            }
        } else {
            $rek_fallback = !empty($identitas['rekening']) ? $identitas['rekening'] : '0042100208006';
            $str_rekening = "- Bank NTB Syariah / BSI: " . $rek_fallback . " a.n " . $nama_web . "\n";
        }

        // 4. Struktur Pimpinan & Organisasi Realtime dari Database
        $pimpinan_text = "";
        $this->db->select('isi_halaman');
        $this->db->from('halamanstatis');
        $this->db->where('id_halaman', 52); // ID Struktur Organisasi Resmi
        $q_struktur = $this->db->get()->row_array();
        if (!empty($q_struktur['isi_halaman'])) {
            $raw_struktur = strip_tags($q_struktur['isi_halaman']);
            $raw_struktur = preg_replace('/\s+/', ' ', $raw_struktur);
            $pimpinan_text = trim(mb_substr($raw_struktur, 0, 750));
        }

        // 5. Pencarian Konten Dinamis di Database Website (RAG Engine)
        $website_results = $this->_search_database_content($user_message);

        // 6. Periksa Kunci API Gemini
        $api_key = $this->config->item('gemini_api_key');
        $ai_reply = null;
        $quick_actions = array();

        if (!empty($api_key)) {
            // Panggil API Google Gemini dengan Context Injection (RAG)
            $ai_reply = $this->_call_gemini_api($api_key, $user_message, $nama_web, $no_telp, $email, $alamat_text, $str_rekening, $pimpinan_text, $website_results);
        }

        // 7. Jika API Key belum disetel atau API gagal/offline, jalankan Intelligent Local Fallback Engine
        if (empty($ai_reply)) {
            $fallback_result = $this->_intelligent_local_engine($user_message, $nama_web, $no_telp, $clean_wa, $email, $alamat_text, $str_rekening, $pimpinan_text, $website_results);
            $ai_reply = $fallback_result['reply'];
            $quick_actions = $fallback_result['quick_actions'];
        }

        // 7. Jika ditemukan artikel/halaman relevan dari website, tambahkan tombol chip akses langsung di awal quick_actions
        if (!empty($website_results)) {
            $content_chips = array();
            $max_chips = 2;
            $c_count = 0;
            foreach ($website_results as $wr) {
                if ($c_count >= $max_chips) break;
                $prefix = ($wr['type'] === 'Berita') ? '📰 Baca: ' : '📄 ';
                $short_title = mb_substr($wr['title'], 0, 26) . '...';
                $content_chips[] = array(
                    'label' => $prefix . $short_title,
                    'action' => 'url',
                    'url' => $wr['url']
                );
                $c_count++;
            }
            $quick_actions = array_merge($content_chips, $quick_actions);
        }

        // 8. Default Quick Actions jika belum ada
        if (empty($quick_actions)) {
            $quick_actions = array(
                array('label' => '🕌 Hitung Zakat', 'action' => 'kalkulator'),
                array('label' => '💳 Rekening Resmi', 'action' => 'rekening'),
                array('label' => '📋 5 Program BAZNAS', 'action' => 'program'),
                array('label' => '💬 Chat WA Petugas', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa . '?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa')
            );
        }

        if (ob_get_length()) {
            ob_clean();
        }

        echo json_encode(array(
            'status' => 'success',
            'reply' => $ai_reply,
            'quick_actions' => $quick_actions
        ));
        exit;
    }

    /**
     * Integrasi Google Gemini API via cURL dengan Multi-Model Fallback Chain & RAG Context Injection
     * Model utama: gemini-3.5-flash-lite (respon tercepat ~1.5 detik & bebas kendala overload)
     * Model cadangan: gemini-3.6-flash & gemini-flash-latest
     */
    private function _call_gemini_api($api_key, $user_message, $nama_web, $no_telp, $email, $alamat, $str_rekening, $pimpinan_text = '', $website_results = array()) {
        $system_prompt = "Anda adalah 'Amil Virtual BAZNAS Sumbawa', asisten AI resmi yang bertugas melayani masyarakat Kabupaten Sumbawa, NTB, seputar Zakat, Infak, Sedekah (ZIS), dan program kemaslahatan umat.

Karakter & Etika:
1. Awali sapaan dengan ramah dan islami (contoh: Assalamu’alaikum Warahmatullahi Wabarakatuh, bismillah).
2. Bersikap santun, amanah, profesional, transparan, dan menyejukkan.
3. Berikan jawaban yang tepat, berbobot, mudah dipahami, dan langsung menjawab pertanyaan spesifik masyarakat Sumbawa.

Data Resmi Lembaga:
- Nama Lembaga: {$nama_web}
- Alamat Kantor: {$alamat}
- Jam Operasional: Senin - Jumat (08.00 - 16.00 WITA)
- Layanan WhatsApp Resmi: {$no_telp}
- Email Resmi: {$email}
- Susunan Pimpinan & Struktur Organisasi Terkini (Realtime Database):
{$pimpinan_text}
- Nomor Rekening Resmi Transfer ZIS:
{$str_rekening}

5 Program Unggulan BAZNAS Kabupaten Sumbawa:
1. Sumbawa Makmur: Bantuan modal usaha produktif UMKM, sarana kerja, insentif marbot/imam masjid dan guru ngaji TPQ.
2. Sumbawa Cerdas: Beasiswa pendidikan, bantuan biaya sekolah bagi siswa prasejahtera jenjang TK/SD/MI/SMP/MTs, dan dukungan bagi guru honorer (GTT/PTT).
3. Sumbawa Sehat: Bantuan biaya pengobatan, layanan kesehatan dhuafa, dan bantuan obat-obatan.
4. Sumbawa Taqwa: Bantuan sarana ibadah/masjid, pembinaan dakwah islamiyah, santri dan dai fisabilillah.
5. Sumbawa Peduli: Tanggap darurat bencana alam, kebakaran, bedah rumah layak huni (RTLH), dan bantuan sosial darurat kemanusiaan.

Pedoman Fiqih Zakat BAZNAS:
- Nisab Zakat Maal/Penghasilan setara 85 gram emas per tahun (kadar 2,5%).
- Nisab Zakat Pertanian (padi, jagung) setara 5 wasaq (653 kg gabah/hasil panen bersih). Kadar: 5% jika menggunakan pengairan berbayar (pompa air/irigasi), 10% jika mengandalkan air hujan/tadah hujan alami. Dikeluarkan setiap panen.
- Zakat Fitrah wajib ditunaikan sebelum shalat Idul Fitri (beras 2,5 kg atau 3,5 liter per jiwa).
- 8 Golongan Asnaf Penerima Zakat: Fakir, Miskin, Amil, Mualaf, Riqab, Gharimin, Fisabilillah, Ibnu Sabil.";

        // Injeksi Hasil Pencarian Database Website (RAG)
        if (!empty($website_results)) {
            $system_prompt .= "\n\nARSIP KONTEN & BERITA RELEVAN DARI WEBSITE BAZNAS KABUPATEN SUMBAWA (DATABASE CRAWL):\n";
            foreach ($website_results as $i => $item) {
                $num = $i + 1;
                $tgl_str = !empty($item['date']) ? " (Tanggal: {$item['date']})" : "";
                $system_prompt .= "[{$num}] {$item['type']}: \"{$item['title']}\"{$tgl_str}\n";
                $system_prompt .= "    Ringkasan: {$item['excerpt']}\n";
                $system_prompt .= "    Tautan Resmi: {$item['url']}\n";
            }
            $system_prompt .= "\nInstruksi Rekomendasi Link & Konten Website:\n" .
                              "- Jika arsip di atas relevan dengan topik pertanyaan pengguna, manfaatkan informasi faktual tersebut untuk menjawab.\n" .
                              "- Wajib sertakan tautan resmi dalam format markdown [Judul Halaman/Berita](URL) dan rekomendasikan kepada pengguna untuk membaca selengkapnya di website BAZNAS Sumbawa.\n";
        }

        $system_prompt .= "\nAturan Penting:
- Jangan pernah mengarang nomor rekening atau nomor kontak selain yang tertera di atas.
- Jawablah pertanyaan secara kontekstual, tuntas, dan berikan tautan rujukan jika relevan.";

        // Model chain: gemini-3.5-flash-lite (~1.5s, tidak overload) -> gemini-3.6-flash -> gemini-flash-latest
        $model_chain = array('gemini-3.5-flash-lite', 'gemini-3.6-flash', 'gemini-flash-latest');

        $payload = array(
            'contents' => array(
                array(
                    'role' => 'user',
                    'parts' => array(
                        array('text' => $system_prompt . "\n\nPertanyaan Pengguna: " . $user_message)
                    )
                )
            ),
            'generationConfig' => array(
                'temperature' => 0.4,
                'maxOutputTokens' => 1500,
            )
        );

        $json_payload = json_encode($payload);

        foreach ($model_chain as $model) {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/" . $model . ":generateContent?key=" . urlencode($api_key);

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200 && !empty($response)) {
                $data = json_decode($response, true);
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($data['candidates'][0]['content']['parts'][0]['text']);
                }
            }
        }

        return null;
    }

    /**
     * Intelligent Local Rule Engine (Fallback cerdas tanpa kuota/saat offline)
     */
    private function _intelligent_local_engine($msg, $nama_web, $no_telp, $clean_wa, $email, $alamat, $str_rekening, $pimpinan_text = '', $website_results = array()) {
        $lower = strtolower($msg);
        $quick_actions = array();

        // 1. Rekening / Transfer / Bank / Setor
        if (preg_match('/(nomor rekening|no rekening|rekening|rek bank|nama bank|transfer zis|qris|setor zis)/i', $lower)) {
            $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\nBerikut adalah **Nomor Rekening Resmi Layanan ZIS {$nama_web}**:\n\n" .
                     $str_rekening . "\n" .
                     "Semua rekening atas nama **{$nama_web}**.\n\n" .
                     "Setelah melakukan transfer, Anda dapat mengonfirmasi bukti setoran melalui layanan WhatsApp resmi kami di **{$no_telp}** agar tercatat secara sah dan diterbitkan Bukti Setor Zakat (BSZ).";
            
            $quick_actions = array(
                array('label' => '💬 Konfirmasi via WhatsApp', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa . '?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa%2C%20saya%20sudah%20transfer%20dan%20ingin%20konfirmasi%20ZIS'),
                array('label' => '🕌 Hitung Zakat Saya', 'action' => 'kalkulator', 'url' => base_url('kalkulator-zakat'))
            );
            return array('reply' => $reply, 'quick_actions' => $quick_actions);
        }

        // 2. Kalkulator / Hitung / Nisab / Berapa Zakat
        if (preg_match('/(hitung|kalkulator|nisab|berapa zakat|zakat profesi|zakat maal|zakat emas|gaji|penghasilan|pertanian|jagung|padi)/i', $lower)) {
            $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\n" .
                     "Untuk menghitung kewajiban Zakat Anda:\n" .
                     "• **Zakat Profesi & Maal:** Nisabnya setara **85 gram emas per tahun**. Kadar zakat yang wajib dikeluarkan adalah **2,5%**.\n" .
                     "• **Zakat Pertanian (Padi/Jagung):** Nisabnya **653 kg hasil panen bersih (5 wasaq)**. Kadar zakatnya **5%** jika ada biaya irigasi/pompa air, atau **10%** jika tadah hujan alami.\n" .
                     "• **Zakat Fitrah:** Sebesar **2,5 kg beras** atau 3,5 liter per jiwa sebelum shalat Idul Fitri.\n\n" .
                     "Anda juga dapat menghitung secara otomatis dan akurat melalui halaman **Kalkulator Zakat Resmi BAZNAS Sumbawa**.";

            $quick_actions = array(
                array('label' => '🧮 Buka Kalkulator Zakat', 'action' => 'kalkulator', 'url' => base_url('kalkulator-zakat')),
                array('label' => '💳 Rekening ZIS Resmi', 'action' => 'rekening'),
                array('label' => '💬 Tanya Petugas Amil', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa . '?text=Assalamu%27alaikum%20BAZNAS%2C%20saya%20ingin%20konsultasi%20perhitungan%20zakat')
            );
            return array('reply' => $reply, 'quick_actions' => $quick_actions);
        }

        // 3. Program BAZNAS / Bantuan / Mustahik
        if (preg_match('/(program|bantuan|syarat|mustahik|beasiswa|cerdas|makmur|sehat|taqwa|peduli|modal|pengobatan)/i', $lower)) {
            $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\n" .
                     "{$nama_web} memiliki **5 Program Unggulan** penyaluran dana Zakat, Infak, dan Sedekah amanah umat:\n\n" .
                     "1. 🌾 **Sumbawa Makmur:** Bantuan modal usaha produktif UMKM, sarana kerja, serta insentif marbot, imam masjid, dan guru ngaji TPQ.\n" .
                     "2. 🎓 **Sumbawa Cerdas:** Bantuan biaya pendidikan untuk siswa/siswi prasejahtera (TK/SD/MI/SMP/MTs) dan dukungan honor bagi Guru Tidak Tetap (GTT/PTT).\n" .
                     "3. 🩺 **Sumbawa Sehat:** Bantuan biaya pengobatan dan pendampingan kesehatan bagi warga dhuafa.\n" .
                     "4. 🕌 **Sumbawa Taqwa:** Dukungan sarana tempat ibadah, kegiatan dakwah, pembinaan santri dan dai fisabilillah.\n" .
                     "5. 🤝 **Sumbawa Peduli:** Bantuan tanggap bencana, korban kebakaran, bedah rumah tidak layak huni (RTLH), dan santunan kemanusiaan.\n\n" .
                     "**Syarat Pengajuan Bantuan:**\n" .
                     "Surat Permohonan, FC KTP/KK Kabupaten Sumbawa, Surat Keterangan Tidak Mampu (SKTM) dari Desa/Kelurahan, dan dokumen pendukung sesuai jenis program (rincian biaya obat/sekolah/proposal usaha).";

            $quick_actions = array(
                array('label' => '💬 Konsultasi Syarat via WA', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa . '?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa%2C%20saya%20ingin%20konsultasi%20pengajuan%20bantuan'),
                array('label' => '📍 Alamat Kantor', 'action' => 'alamat')
            );
            return array('reply' => $reply, 'quick_actions' => $quick_actions);
        }

        // 4. Alamat / Lokasi / Jam Buka / Kantor
        if (preg_match('/(alamat|lokasi|kantor|buka|jam|dimana|posisi)/i', $lower)) {
            $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\n" .
                     "🏛️ **Kantor Resmi {$nama_web}:**\n" .
                     "{$alamat}\n\n" .
                     "⏰ **Jam Layanan:**\n" .
                     "Senin - Jumat: Pukul 08.00 - 16.00 WITA\n" .
                     "(Sabtu, Minggu, dan Hari Libur Nasional tutup)\n\n" .
                     "📞 **Kontak Resmi:**\n" .
                     "WhatsApp: {$no_telp}\n" .
                     "Email: {$email}";

            $quick_actions = array(
                array('label' => '💬 Hubungi WhatsApp', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa),
                array('label' => '💳 Rekening Resmi', 'action' => 'rekening')
            );
            return array('reply' => $reply, 'quick_actions' => $quick_actions);
        }

        // 5. Struktur Pimpinan / Ketua / Kepengurusan
        if (preg_match('/(ketua|pimpinan|wakil ketua|struktur|pengurus|kepengurusan|direktur|pejabat|siapa ketua|siapa pimpinan)/i', $lower)) {
            $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\n" .
                     "Berikut adalah susunan **Pimpinan & Struktur Organisasi {$nama_web}** terkini yang tercatat resmi di database kami:\n\n" .
                     (!empty($pimpinan_text) ? $pimpinan_text . "\n\n" : "") .
                     "Untuk rincian profil lengkap dan bagan tupoksi, Anda dapat membaca tautan resmi [Struktur Organisasi BAZNAS Sumbawa](" . base_url('halaman/detail/struktur-organisasi-baznas-kabupaten-sumbawa') . ").";

            $quick_actions = array(
                array('label' => '👥 Struktur Organisasi', 'action' => 'url', 'url' => base_url('halaman/detail/struktur-organisasi-baznas-kabupaten-sumbawa')),
                array('label' => '💬 Hubungi WhatsApp Petugas', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa . '?text=Assalamu%27alaikum%20BAZNAS%20Sumbawa')
            );
            return array('reply' => $reply, 'quick_actions' => $quick_actions);
        }

        // 6. Jika ada arsip/berita relevan dari pencarian database website (saat AI offline/fallback)
        if (!empty($website_results)) {
            $top = $website_results[0];
            $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\n" .
                     "Berdasarkan publikasi resmi di website {$nama_web}, kami menemukan informasi yang berkaitan dengan pertanyaan Anda:\n\n" .
                     "📰 **" . $top['title'] . "**\n" .
                     $top['excerpt'] . "\n\n" .
                     "👉 **Baca artikel/halaman selengkapnya:**\n" .
                     "[" . $top['title'] . "](" . $top['url'] . ")\n\n" .
                     "Jika membutuhkan konfirmasi atau konsultasi lebih lanjut, silakan menghubungi layanan WhatsApp resmi kami.";

            $local_chips = array();
            foreach ($website_results as $wr) {
                $local_chips[] = array(
                    'label' => '📰 ' . mb_substr($wr['title'], 0, 24) . '...',
                    'action' => 'url',
                    'url' => $wr['url']
                );
            }
            $local_chips[] = array('label' => '💬 Chat WA Petugas', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa);
            return array('reply' => $reply, 'quick_actions' => $local_chips);
        }

        // 7. Default Friendly Welcome Response
        $reply = "Assalamu’alaikum Warahmatullahi Wabarakatuh.\n\n" .
                 "Terima kasih telah menghubungi **Amil Virtual {$nama_web}**.\n\n" .
                 "Kami siap membantu Anda seputar:\n" .
                 "• Perhitungan dan konsultasi Zakat (Profesi, Maal, Emas, Pertanian, Perniagaan)\n" .
                 "• Informasi Nomor Rekening resmi penyaluran ZIS\n" .
                 "• 5 Program BAZNAS Sumbawa (Makmur, Cerdas, Sehat, Taqwa, Peduli)\n" .
                 "• Informasi Struktur Organisasi dan Pimpinan BAZNAS Sumbawa\n" .
                 "• Prosedur dan syarat pengajuan bantuan bagi mustahik\n\n" .
                 "Silakan pilih topik di bawah ini atau ketikkan pertanyaan Anda secara langsung:";

        $quick_actions = array(
            array('label' => '🕌 Hitung Zakat', 'action' => 'kalkulator', 'url' => base_url('kalkulator-zakat')),
            array('label' => '💳 Rekening Bank Resmi', 'action' => 'rekening'),
            array('label' => '👥 Struktur Pimpinan', 'action' => 'url', 'url' => base_url('halaman/detail/struktur-organisasi-baznas-kabupaten-sumbawa')),
            array('label' => '📋 5 Program BAZNAS', 'action' => 'program'),
            array('label' => '💬 Chat WhatsApp Petugas', 'action' => 'wa', 'url' => 'https://wa.me/' . $clean_wa)
        );

        return array('reply' => $reply, 'quick_actions' => $quick_actions);
    }

    /**
     * RAG Engine: Pencarian Cerdas Konten Database Website BAZNAS Sumbawa
     * Menelusuri tabel berita, halamanstatis, agenda, dan laporan_keuangan secara realtime
     */
    private function _search_database_content($user_query) {
        $stop_words = array(
            'yang', 'untuk', 'pada', 'ke', 'para', 'namun', 'menurut', 'antara', 'dia', 'dua', 'ia', 'seperti',
            'jika', 'sehingga', 'kembali', 'dan', 'ini', 'karena', 'oleh', 'saat', 'harus', 'sementara', 'setelah',
            'belum', 'kami', 'sekitar', 'bagi', 'serta', 'di', 'dari', 'telah', 'sebagai', 'masih', 'hal', 'ketika',
            'adalah', 'itu', 'dengan', 'sampai', 'juga', 'sudah', 'saya', 'apakah', 'ada', 'bisa', 'bagaimana',
            'apa', 'tolong', 'info', 'tentang', 'terbaru', 'kapan', 'siapa', 'dimana', 'kah', 'dong', 'sih', 'bapak', 'ibu'
        );

        $site_words = array('baznas', 'kabupaten', 'sumbawa');

        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', strtolower($user_query));
        $raw_words = array_filter(explode(' ', $clean));
        $all_keywords = array();
        $specific_keywords = array();

        foreach ($raw_words as $w) {
            $w = trim($w);
            if (mb_strlen($w) >= 3 && !in_array($w, $stop_words)) {
                $all_keywords[] = $w;
                if (!in_array($w, $site_words)) {
                    $specific_keywords[] = $w;
                }
            }
        }

        // Jika ada kata kunci spesifik (misal 'ketua', 'beasiswa', 'mustahik'), abaikan nama umum website agar hasil lebih akurat
        $keywords = !empty($specific_keywords) ? $specific_keywords : $all_keywords;
        $is_latest_news_query = preg_match('/(berita|kegiatan|terkini|terbaru|kabar|rilis)/i', $clean);

        if (empty($keywords) && !$is_latest_news_query) {
            return array();
        }

        $results = array();

        // 1. Pencarian di Tabel Berita (Status 'Y' / Publish)
        $this->db->select('id_berita, judul, judul_seo, tanggal, isi_berita');
        $this->db->from('berita');
        $this->db->where('status', 'Y');
        if (!empty($keywords)) {
            $this->db->group_start();
            foreach ($keywords as $k) {
                $this->db->or_like('judul', $k);
                $this->db->or_like('isi_berita', $k);
                $this->db->or_like('tag', $k);
            }
            $this->db->group_end();
        }
        $this->db->order_by('id_berita', 'DESC');
        $this->db->limit(3);
        $query_berita = $this->db->get();

        if ($query_berita && $query_berita->num_rows() > 0) {
            foreach ($query_berita->result_array() as $b) {
                $excerpt = strip_tags($b['isi_berita']);
                $excerpt = preg_replace('/\s+/', ' ', $excerpt);
                $excerpt = mb_substr($excerpt, 0, 450) . '...';
                $results[] = array(
                    'type' => 'Berita',
                    'title' => $b['judul'],
                    'date' => $b['tanggal'],
                    'excerpt' => $excerpt,
                    'url' => base_url('berita/detail/' . $b['judul_seo'])
                );
            }
        }

        // 2. Pencarian di Tabel Halaman Statis
        if (!empty($keywords)) {
            $this->db->select('id_halaman, judul, judul_seo, isi_halaman');
            $this->db->from('halamanstatis');
            $this->db->group_start();
            foreach ($keywords as $k) {
                $this->db->or_like('judul', $k);
                $this->db->or_like('isi_halaman', $k);
            }
            $this->db->group_end();
            $this->db->order_by('id_halaman', 'DESC');
            $this->db->limit(2);
            $query_halaman = $this->db->get();

            if ($query_halaman && $query_halaman->num_rows() > 0) {
                foreach ($query_halaman->result_array() as $h) {
                    $excerpt = strip_tags($h['isi_halaman']);
                    $excerpt = preg_replace('/\s+/', ' ', $excerpt);
                    $excerpt = mb_substr($excerpt, 0, 500) . '...';
                    $results[] = array(
                        'type' => 'Halaman Profil/Layanan',
                        'title' => $h['judul'],
                        'date' => null,
                        'excerpt' => $excerpt,
                        'url' => base_url('halaman/detail/' . $h['judul_seo'])
                    );
                }
            }
        }

        // 3. Pencarian di Agenda jika ada indikasi kegiatan/acara
        if (preg_match('/(agenda|kegiatan|acara|jadwal)/i', $clean)) {
            $this->db->select('id_agenda, tema, tema_seo, tempat, tgl_mulai, isi_agenda');
            $this->db->from('agenda');
            $this->db->group_start();
            foreach ($keywords as $k) {
                $this->db->or_like('tema', $k);
                $this->db->or_like('isi_agenda', $k);
            }
            $this->db->group_end();
            $this->db->order_by('id_agenda', 'DESC');
            $this->db->limit(2);
            $query_agenda = $this->db->get();
            if ($query_agenda && $query_agenda->num_rows() > 0) {
                foreach ($query_agenda->result_array() as $a) {
                    $results[] = array(
                        'type' => 'Agenda',
                        'title' => $a['tema'],
                        'date' => $a['tgl_mulai'],
                        'excerpt' => 'Tempat: ' . $a['tempat'] . ' - ' . mb_substr(strip_tags($a['isi_agenda']), 0, 120),
                        'url' => base_url('agenda/detail/' . $a['tema_seo'])
                    );
                }
            }
        }

        // 4. Pencarian di Laporan Keuangan jika ada kata laporan/keuangan/audit/transparansi
        if (preg_match('/(laporan|keuangan|audit|transparansi|saldo)/i', $clean)) {
            $results[] = array(
                'type' => 'Laporan Keuangan',
                'title' => 'Transparansi & Laporan Keuangan BAZNAS Sumbawa',
                'date' => null,
                'excerpt' => 'Publikasi berkas laporan pertanggungjawaban keuangan dan penyaluran ZIS BAZNAS Kabupaten Sumbawa secara transparan.',
                'url' => base_url('laporan')
            );
        }

        return $results;
    }
}
