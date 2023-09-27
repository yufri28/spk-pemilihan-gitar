<?php 

    require_once '../config.php';
    class Gitar{
        private $db;
        public function __construct()
        {
            $this->db = connectDatabase();
        }

        public function getGitar(){
            return $this->db->query("SELECT a.nama_gitar, a.id_alternatif, a.gambar, kak.id_alt_kriteria, a.jenis_senar, a.merek, a.nama_toko,
                MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.id_alt_kriteria END) AS id_alt_C1,
                MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.id_alt_kriteria END) AS id_alt_C2,
                MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.id_alt_kriteria END) AS id_alt_C3,
                MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.id_alt_kriteria END) AS id_alt_C4,
                MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.f_id_sub_kriteria END) AS id_sub_C1,
                MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.f_id_sub_kriteria END) AS id_sub_C2,
                MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.f_id_sub_kriteria END) AS id_sub_C3,
                MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.f_id_sub_kriteria END) AS id_sub_C4,
                MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS nama_C1,
                MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS nama_C2,
                MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.nama_sub_kriteria END) AS nama_C3,
                MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS nama_C4
                FROM alternatif a
                JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
                JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
                JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
                GROUP BY a.nama_gitar ORDER BY a.id_alternatif DESC;
            ");
        }

        public function getSubHarga()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C1'"
           );
        }

        public function getSubJenisGitar()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C2'"
           );
        }
        public function getSubBahanKayu()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C3'"
           );
        }
        public function getSubBentuk()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C4'"
           );
        }
               

        // CRUD
        public function addDataGitar($dataAlternatif = [], $dataKecAltKrit = [])
        {
            if (empty($dataAlternatif) && empty($dataKecAltKrit)) {
                return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
            }

            $nama_gitar = $dataAlternatif['nama_gitar'];
            $jenis_senar = $dataAlternatif['jenis_senar'];
            $merek = $dataAlternatif['merek'];
            $nama_toko = $dataAlternatif['nama_toko'];
            $gambar = $dataAlternatif['gambar'];

            $cekData = $this->db->query("SELECT * FROM `alternatif` WHERE LOWER(nama_gitar) = '" . strtolower($dataAlternatif['nama_gitar']) . "'");
            if ($cekData->num_rows > 0) {
                return $_SESSION['error'] = 'Data sudah ada!';
            }

            $insertAlternatif = $this->db->query(
                "INSERT INTO alternatif (id_alternatif, nama_gitar, merek, jenis_senar, nama_toko, gambar) VALUES (NULL, '$nama_gitar', '$merek', '$jenis_senar', '$nama_toko', '$gambar')"
            );

            if ($insertAlternatif) {
                $id_alternatif = $this->db->insert_id;
                foreach ($dataKecAltKrit as $key => $id_sub_kriteria) {
                    $insertKecAltKrit = $this->db->query("INSERT INTO kecocokan_alt_kriteria (id_alt_kriteria, f_id_alternatif, f_id_kriteria, f_id_sub_kriteria) VALUES (NULL, '$id_alternatif', '$key', '$id_sub_kriteria')");
                }
                if ($insertKecAltKrit && $this->db->affected_rows > 0) {
                    return $_SESSION['success'] = 'Data berhasil disimpan!';
                } else {
                    return $_SESSION['error'] = 'Data gagal disimpan!';
                }
            } else {
                return $_SESSION['error'] = 'Data gagal disimpan!';
            }
        }

        public function editDataGitar($dataAlternatif = [], $dataKecAltKrit = [])
        {
            if (empty($dataAlternatif) && empty($dataKecAltKrit)) {
                return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
            }
            $id_alternatif = $dataAlternatif['id_alternatif'];
            $nama_gitar = $dataAlternatif['nama_gitar'];
            $jenis_senar = $dataAlternatif['jenis_senar'];
            $merek = $dataAlternatif['merek'];
            $nama_toko = $dataAlternatif['nama_toko'];
            $gambar = $dataAlternatif['gambar'];

            $updateAlternatif = $this->db->query(
                "UPDATE alternatif SET nama_gitar = '$nama_gitar', merek='$merek', jenis_senar='$jenis_senar', nama_toko='$nama_toko', gambar='$gambar' WHERE id_alternatif = $id_alternatif"
            );

            if ($updateAlternatif) {
                // Update data kecocokan_alt_kriteria
                foreach ($dataKecAltKrit as $key => $id_sub_kriteria) {
                    $updateKecAltKrit = $this->db->query("UPDATE kecocokan_alt_kriteria SET f_id_sub_kriteria = '$id_sub_kriteria' WHERE f_id_alternatif = '$id_alternatif' AND f_id_kriteria = '$key'");
                }
                if ($updateKecAltKrit || $this->db->affected_rows > 0) {
                    return $_SESSION['success'] = 'Data berhasil diupdate!';
                } 
                else {
                    return $_SESSION['error'] = 'Data gagal diupdate!';
                }
            } else {
                return $_SESSION['error'] = 'Data gagal diupdate!';
            }
        }

        public function hapusDataGitar($id_alternatif)
        {
            $stmtDelete = $this->db->prepare("DELETE FROM alternatif WHERE id_alternatif=?");
            $stmtDelete->bind_param("i", $id_alternatif);
            $stmtDelete->execute();

            if ($stmtDelete->affected_rows > 0) {
                $_SESSION['success'] = 'Data berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
            }
            $stmtDelete->close();
        }

        // End CRUD

    }

    $Gitar = new Gitar();

?>