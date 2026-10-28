<?php 
class Model_app extends CI_model{
    public function view($table){
        return $this->db->get($table);
    }

    public function insert($table,$data){
        return $this->db->insert($table, $data);
    }

    public function edit($table, $data){
        return $this->db->get_where($table, $data);
    }
 
    public function update($table, $data, $where){
        return $this->db->update($table, $data, $where); 
    }

    public function delete($table, $where){
        return $this->db->delete($table, $where);
    }

    public function view_where($table,$data){
        $this->db->where($data);
        return $this->db->get($table);
    }

    public function view_ordering_limit($table,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get($table);
    }
    
    public function view_ordering($table,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    public function view_where_ordering($table,$data,$order,$ordering){
        $this->db->where($data);
        $this->db->order_by($order,$ordering);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function view_join_one($table1,$table2,$field,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    public function view_join_where($table1,$table2,$field,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    function umenu_akses($link,$id){
        return $this->db->query("SELECT * FROM modul,users_modul WHERE modul.id_modul=users_modul.id_modul AND users_modul.id_session='$id' AND modul.link='$link'")->num_rows();
    }

    public function cek_login($username,$password,$table){
        return $this->db->query("SELECT * FROM $table where username='".$this->db->escape_str($username)."' AND password='".$this->db->escape_str($password)."' AND blokir='N'");
    }

    public function find_user_by_email($email){
        $email = trim((string)$email);
        if ($email === '') {
            return null;
        }
        $q = $this->db->query("SELECT * FROM users WHERE LOWER(TRIM(email)) = LOWER('" . $this->db->escape_str($email) . "') LIMIT 1");
        return ($q && $q->num_rows() > 0) ? $q->row_array() : null;
    }

    public function is_email_taken($email, $exclude_username = ''){
        $email = trim((string)$email);
        if ($email === '') {
            return false;
        }
        $sql = "SELECT username FROM users WHERE LOWER(TRIM(email)) = LOWER('" . $this->db->escape_str($email) . "')";
        if ($exclude_username !== '') {
            $sql .= " AND username != '" . $this->db->escape_str($exclude_username) . "'";
        }
        $sql .= " LIMIT 1";
        $q = $this->db->query($sql);
        return ($q && $q->num_rows() > 0);
    }

    public function ensure_google_login_columns(){
        $fields = $this->db->list_fields('identitas');
        if (!in_array('google_login_enabled', $fields)) {
            $this->db->query("ALTER TABLE identitas ADD COLUMN google_login_enabled VARCHAR(5) NOT NULL DEFAULT '1'");
        }
        if (!in_array('google_login_mode', $fields)) {
            $this->db->query("ALTER TABLE identitas ADD COLUMN google_login_mode VARCHAR(30) NOT NULL DEFAULT 'both'");
        }
        if (!in_array('google_client_id', $fields)) {
            $this->db->query("ALTER TABLE identitas ADD COLUMN google_client_id VARCHAR(255) NOT NULL DEFAULT '463195727091-e56sq54ngljkhp1od2ou9bnhs00nn89g.apps.googleusercontent.com'");
        }
    }

    public function verify_google_id_token($idToken, $expectedClientId, &$errorMessage = ''){
        $idToken = trim((string)$idToken);
        $expectedClientId = trim((string)$expectedClientId);

        if ($idToken === '' || $expectedClientId === '') {
            $errorMessage = 'Token atau Google Client ID belum dikonfigurasi.';
            return null;
        }

        $verifyUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
        $response = false;
        $httpCode = 0;

        $host = strtolower(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
        $isLocalhost = ($host === 'localhost' || strpos($host, '127.0.0.1') === 0 || strpos($host, 'localhost:') === 0);

        if (function_exists('curl_init')) {
            $ch = curl_init($verifyUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $response = curl_exec($ch);
            $curlErrno = curl_errno($ch);
            $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (($response === false || $curlErrno === 60 || $curlErrno === 77) && $isLocalhost) {
                $ch = curl_init($verifyUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            }
        } else {
            $ctx = stream_context_create(array(
                'http' => array('timeout' => 10, 'ignore_errors' => true),
                'ssl'  => array('verify_peer' => !$isLocalhost, 'verify_peer_name' => !$isLocalhost)
            ));
            $response = @file_get_contents($verifyUrl, false, $ctx);
            $httpCode = ($response !== false) ? 200 : 0;
        }

        if ($response === false || $httpCode !== 200) {
            $errorMessage = 'Gagal memverifikasi kredensial dengan server Google.';
            return null;
        }

        $payload = json_decode((string)$response, true);
        if (!is_array($payload) || isset($payload['error'])) {
            $errorMessage = 'Token Google tidak valid atau telah kedaluwarsa.';
            return null;
        }

        $aud = isset($payload['aud']) ? $payload['aud'] : '';
        if ($aud !== $expectedClientId) {
            $errorMessage = 'Google Client ID tidak cocok dengan aplikasi ini.';
            return null;
        }

        $iss = isset($payload['iss']) ? $payload['iss'] : '';
        if (!in_array($iss, array('accounts.google.com', 'https://accounts.google.com'), true)) {
            $errorMessage = 'Penerbit token (issuer) tidak sah.';
            return null;
        }

        if (isset($payload['exp']) && (int)$payload['exp'] < time()) {
            $errorMessage = 'Sesi login Google telah kedaluwarsa. Silakan coba lagi.';
            return null;
        }

        $emailVerified = isset($payload['email_verified']) ? $payload['email_verified'] : false;
        if (empty($payload['email']) || ($emailVerified !== true && $emailVerified !== 'true' && $emailVerified !== '1')) {
            $errorMessage = 'Alamat email Google belum terverifikasi.';
            return null;
        }

        return array(
            'sub'     => isset($payload['sub']) ? (string)$payload['sub'] : '',
            'email'   => strtolower(trim((string)$payload['email'])),
            'name'    => isset($payload['name']) ? trim((string)$payload['name']) : '',
            'picture' => isset($payload['picture']) ? trim((string)$payload['picture']) : ''
        );
    }

    function grafik_kunjungan(){
        return $this->db->query("SELECT count(*) as jumlah, tanggal FROM statistik GROUP BY tanggal ORDER BY tanggal DESC LIMIT 10");
    }

    function kategori_populer($limit){
        return $this->db->query("SELECT * FROM (SELECT a.*, b.jum_dibaca FROM
                                    (SELECT * FROM kategori) as a left join
                                    (SELECT id_kategori, sum(dibaca) as jum_dibaca FROM berita GROUP BY id_kategori) as b on a.id_kategori=b.id_kategori) as c 
                                        where c.aktif='Y' ORDER BY c.jum_dibaca DESC LIMIT $limit");
    }
}