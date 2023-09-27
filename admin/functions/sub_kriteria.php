<?php 
require_once '../config.php';
class Sub_Kriteria{

    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    public function getSubKriteria()
    {
        return $this->db->query("SELECT sk.id_sub_kriteria,sk.nama_sub_kriteria,sk.bobot_sub_kriteria,k.id_kriteria,k.nama_kriteria FROM sub_kriteria sk JOIN kriteria k ON sk.f_id_kriteria = k.id_kriteria ORDER BY sk.id_sub_kriteria DESC");
    }
    public function getKriteria()
    {
        return $this->db->query("SELECT * FROM `kriteria`");
    }
    public function tambahSubKriteria($dataSubKiteria)
    {
        $cekData = $this->db->query("SELECT * FROM `sub_kriteria` WHERE LOWER(nama_sub_kriteria) = '".strtolower($dataSubKiteria['nama_sub_kriteria'])."'");
        if ($cekData->num_rows > 0) {
            return $_SESSION['error'] = 'Data sudah ada!';
        } else {
            $stmtInsertSub = $this->db->prepare("INSERT INTO sub_kriteria(nama_sub_kriteria,bobot_sub_kriteria,f_id_kriteria) VALUES (?,?,?)");
            $stmtInsertSub->bind_param("sis", $dataSubKiteria['nama_sub_kriteria'], $dataSubKiteria['bobot_sub_kriteria'], $dataSubKiteria['id_kriteria']);
            $stmtInsertSub->execute();
            if ($stmtInsertSub->affected_rows > 0) {
                return $_SESSION['success'] = 'Data berhasil disimpan!';
            } else {
                return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
            }
            $stmtInsertSub->close();
        }
    }
    public function editSubKriteria($dataSubKiteria)
    {
        $stmtUpdateSub = $this->db->prepare("UPDATE sub_kriteria  SET nama_sub_kriteria=?,bobot_sub_kriteria=?,f_id_kriteria=? WHERE id_sub_kriteria=?");
        $stmtUpdateSub->bind_param("sisi", $dataSubKiteria['nama_sub_kriteria'],$dataSubKiteria['bobot_sub_kriteria'],$dataSubKiteria['id_kriteria'],$dataSubKiteria['id_sub_kriteria']);
        $stmtUpdateSub->execute();
        if ($stmtUpdateSub) {
            return $_SESSION['success'] = 'Data berhasil diedit!';
        } else {
            return $_SESSION['error'] = 'Terjadi kesalahan dalam mengedit data.';
        }
        $stmtUpdateSub->close();
    }
    public function hapusSubKriteria($idSubKriteria)
    {
        $stmtDelete = $this->db->prepare("DELETE FROM sub_kriteria WHERE id_sub_kriteria=?");
        $stmtDelete->bind_param("i",$idSubKriteria);
        $stmtDelete->execute();
        if ($stmtDelete->affected_rows > 0) {
            return $_SESSION['success'] = 'Data berhasil dihapus!';
        } else{
            return $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
        }
        $stmtDelete->close();
        
    }
}


$Sub_Kriteria = new Sub_Kriteria();


?>