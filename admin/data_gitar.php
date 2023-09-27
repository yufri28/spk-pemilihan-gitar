<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'gitar';
require_once './header.php';
require_once './functions/data_gitar.php';
$dataAlternatif = $Gitar->getGitar();
$dataSubJenisGitar = $Gitar->getSubJenisGitar();
$dataSubBahanKayu = $Gitar->getSubBahanKayu();
$dataSubHarga = $Gitar->getSubHarga();
$dataSubBentuk = $Gitar->getSubBentuk();

// tambah alternatif/gitar
if(isset($_POST['tambah'])){
    $nama_gitar = htmlspecialchars($_POST['nama_gitar']);
    $jenis_senar = htmlspecialchars($_POST['jenis_senar']);
    $merek = htmlspecialchars($_POST['merek']);
    $nama_toko = htmlspecialchars($_POST['nama_toko']);
    
    // Pastikan ada file gambar yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['gambar']['name'];
        $lokasiSementara = $_FILES['gambar']['tmp_name'];
        
        // Tentukan lokasi tujuan penyimpanan
        $targetDir = '../assets/images/';
        $targetFilePath = $targetDir . $namaFile;

        // Cek apakah nama file sudah ada dalam direktori target
        if (file_exists($targetFilePath)) {
            $fileInfo = pathinfo($namaFile);
            $baseName = $fileInfo['filename'];
            $extension = $fileInfo['extension'];
            $counter = 1;

            // Loop hingga menemukan nama file yang unik
            while (file_exists($targetFilePath)) {
                $namaFile = $baseName . '_' . $counter . '.' . $extension;
                $targetFilePath = $targetDir . $namaFile;
                $counter++;
            }
        }

        // Pindahkan file gambar dari lokasi sementara ke lokasi tujuan
        if (move_uploaded_file($lokasiSementara, $targetFilePath)) {
            $harga = htmlspecialchars($_POST['harga']);
            $jenis_gitar = htmlspecialchars($_POST['jenis_gitar']);
            $bahan_kayu = htmlspecialchars($_POST['bahan_kayu']);
            $bentuk = htmlspecialchars($_POST['bentuk']);
           
            $dataGitar = [
                'nama_gitar' => $nama_gitar,
                'jenis_senar' => $jenis_senar,
                'merek' => $merek,
                'nama_toko' => $nama_toko,
                'gambar' => $namaFile        
            ];
            
            $dataKecAltKrit = [
                'C1' => $harga,
                'C2' => $jenis_gitar,
                'C3' => $bahan_kayu,
                'C4' => $bentuk,
            ];
            $Gitar->addDataGitar($dataGitar,$dataKecAltKrit);
        } else {
            return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
        }
    } else {
        return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
    }    
}

// edit alternatif/Gitar
if(isset($_POST['edit'])){
    $id_alternatif = htmlspecialchars($_POST['id_alternatif']);
    $nama_gitar = htmlspecialchars($_POST['nama_gitar']);
    $jenis_senar = htmlspecialchars($_POST['jenis_senar']);
    $merek = htmlspecialchars($_POST['merek']);
    $nama_toko = htmlspecialchars($_POST['nama_toko']);


    // Pastikan ada file gambar yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['gambar']['name'];
        $lokasiSementara = $_FILES['gambar']['tmp_name'];
        
        // Tentukan lokasi tujuan penyimpanan
        $targetDir = '../assets/images/';
        $targetFilePath = $targetDir . $namaFile;

        // Cek apakah nama file sudah ada dalam direktori target
        if (file_exists($targetFilePath)) {
            $fileInfo = pathinfo($namaFile);
            $baseName = $fileInfo['filename'];
            $extension = $fileInfo['extension'];
            $counter = 1;

            // Loop hingga menemukan nama file yang unik
            while (file_exists($targetFilePath)) {
                $namaFile = $baseName . '_' . $counter . '.' . $extension;
                $targetFilePath = $targetDir . $namaFile;
                $counter++;
            }
        }

        // Pindahkan file gambar dari lokasi sementara ke lokasi tujuan
        if (move_uploaded_file($lokasiSementara, $targetFilePath)) {
            // Hapus file gambar lama jika ada
            $gambarLama = $_POST['gambar_lama'];
            $pathGambarLama = $targetDir . $gambarLama;
            if (file_exists($pathGambarLama) && is_file($pathGambarLama)) {
                unlink($pathGambarLama); // Hapus file gambar lama
            }

            $harga = htmlspecialchars($_POST['harga']);
            $jenis_gitar = htmlspecialchars($_POST['jenis_gitar']);
            $bahan_kayu = htmlspecialchars($_POST['bahan_kayu']);
            $bentuk = htmlspecialchars($_POST['bentuk']);
        
            $dataGitar = [
                'id_alternatif' => $id_alternatif,
                'nama_gitar' => $nama_gitar,
                'jenis_senar' => $jenis_senar,
                'merek' => $merek,
                'nama_toko' => $nama_toko,
                'gambar' => $namaFile
            ];
            
            $dataKecAltKrit = [
                'C1' => $harga,
                'C2' => $jenis_gitar,
                'C3' => $bahan_kayu,
                'C4' => $bentuk
            ];
            $Gitar->editDataGitar($dataGitar,$dataKecAltKrit);
        } else {
            return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
        }
    } else {
        $harga = htmlspecialchars($_POST['harga']);
        $jenis_gitar = htmlspecialchars($_POST['jenis_gitar']);
        $bahan_kayu = htmlspecialchars($_POST['bahan_kayu']);
        $bentuk = htmlspecialchars($_POST['bentuk']);
        
        $dataGitar = [
            'id_alternatif' => $id_alternatif,
            'nama_gitar' => $nama_gitar,
            'jenis_senar' => $jenis_senar,
            'merek' => $merek,
            'nama_toko' => $nama_toko,
            'gambar' => $_POST['gambar_lama']
        ];
        
        $dataKecAltKrit = [
            'C1' => $harga,
            'C2' => $jenis_gitar,
            'C3' => $bahan_kayu,
            'C4' => $bentuk
        ];
        $Gitar->editDataGitar($dataGitar,$dataKecAltKrit);
    }
}

if(isset($_POST['hapus'])){
    $id_alternatif = htmlspecialchars($_POST['id_alternatif']);
    $Gitar->hapusDataGitar($id_alternatif);
}

?>
<?php if (isset($_SESSION['success'])): ?>
<script>
var successfuly = '<?php echo $_SESSION["success"]; ?>';
Swal.fire({
    title: 'Sukses!',
    text: successfuly,
    icon: 'success',
    confirmButtonText: 'OK'
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = '';
    }
});
</script>
<?php unset($_SESSION['success']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    title: 'Error!',
    text: '<?php echo $_SESSION['error']; ?>',
    icon: 'error',
    confirmButtonText: 'OK'
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = '';
    }
});
</script>
<?php unset($_SESSION['error']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>
<div class="row">
    <!-- Area Chart -->
    <!-- Button trigger modal -->
    <div class="col-lg-12">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
            + Tambah data
        </button>
        <div class="card">
            <!-- <div class="card-header">
                Featured
            </div> -->
            <div class="card-body">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Gitar</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered nowrap" id="dataGitar" style="width:100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Gitar</th>
                                        <th>Gambar</th>
                                        <th>Jenis Senar</th>
                                        <th>Merek</th>
                                        <th>Nama Toko</th>
                                        <th>Harga</th>
                                        <th>Jenis Gitar</th>
                                        <th>Bahan Kayu</th>
                                        <th>Bentuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataAlternatif as $key => $alternatif):?>
                                    <tr>
                                        <td><?=$key+1?></td>
                                        <td><?=$alternatif['nama_gitar'];?></td>
                                        <td><a href="../assets/images/<?= $alternatif['gambar'] == '-' || $alternatif['gambar'] == '' || $alternatif['gambar'] == NULL ? 'default.png': $alternatif['gambar'];?>"
                                                data-lightbox="image-1" data-title="<?=$alternatif['nama_gitar'];?>">
                                                <img style="width: 50px; height: 50px;"
                                                    src="../assets/images/<?= $alternatif['gambar'] == '-' || $alternatif['gambar'] == '' || $alternatif['gambar'] == NULL ? 'default.png': $alternatif['gambar'];?>"
                                                    alt="Gambar <?=$alternatif['nama_gitar'];?>">
                                            </a></td>

                                        <td><?=$alternatif['jenis_senar'];?></td>
                                        <td><?=$alternatif['merek'];?></td>
                                        <td><?=$alternatif['nama_toko'];?></td>
                                        <td><?=$alternatif['nama_C1'];?></td>
                                        <td><?=$alternatif['nama_C2'];?></td>
                                        <td><?=$alternatif['nama_C3'];?></td>
                                        <td><?=$alternatif['nama_C4'];?></td>
                                        <td>
                                            <button data-toggle="modal"
                                                data-target="#edit<?=$alternatif['id_alternatif'];?>" type="button"
                                                class="btn btn-sm btn-primary">Edit</button>
                                            <button data-toggle="modal"
                                                data-target="#hapus<?=$alternatif['id_alternatif'];?>" type="button"
                                                class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <small class="text-danger">(*) Wajib</small>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Nama Gitar <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" required name="nama_gitar" id="exampleFormControlInput1"
                            placeholder="Nama Gitar" />
                    </div>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Jenis Senar <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" required name="jenis_senar"
                            id="exampleFormControlInput1" placeholder="Jenis Senar" />
                    </div>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Merek <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" required name="merek" id="exampleFormControlInput1"
                            placeholder="Merek" />
                    </div>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Nama Toko <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" required name="nama_toko" id="exampleFormControlInput1"
                            placeholder="Nama Toko" />
                    </div>
                    <div class="card-body">
                        <label for="gambar" class="form-label">Gambar <small class="text-danger">*</small></label>
                        <input type="file" accept=".jpg, .jpeg, .png" class="form-control" name="gambar" id="gambar"
                            required placeholder="Gambar" />
                    </div>
                    <div class="card-body">
                        <label for="harga" class="form-label">Harga <small class="text-danger">*</small></label>
                        <select class="form-control" name="harga" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubHarga as $key => $harga):?>
                            <option value="<?=$harga['id_sub_kriteria'];?>">
                                <?=$harga['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="jenis_gitar" class="form-label">Jenis Gitar <small
                                class="text-danger">*</small></label>
                        <select class="form-control" name="jenis_gitar" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubJenisGitar as $key => $gitar):?>
                            <option value="<?=$gitar['id_sub_kriteria'];?>">
                                <?=$gitar['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="bahan_kayu" class="form-label">Bahan Kayu <small
                                class="text-danger">*</small></label>
                        <select class="form-control" name="bahan_kayu" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubBahanKayu as $key => $kayu):?>
                            <option value="<?=$kayu['id_sub_kriteria'];?>">
                                <?=$kayu['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="bentuk" class="form-label">Bentuk <small class="text-danger">*</small></label>
                        <select class="form-control" name="bentuk" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubBentuk as $key => $bentuk):?>
                            <option value="<?=$bentuk['id_sub_kriteria'];?>">
                                <?=$bentuk['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($dataAlternatif as $alternatif):?>
<div class="modal fade" id="edit<?=$alternatif['id_alternatif'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <small class="text-danger">(*) Wajib</small>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Nama Alternatif <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" required name="nama_gitar"
                            value="<?=$alternatif['nama_gitar'];?>" id="exampleFormControlInput1"
                            placeholder="Nama Alternatif" />
                    </div>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Jenis Senar <small
                                class="text-danger">*</small></label>
                        <input type="text" value="<?=$alternatif['jenis_senar'];?>" class="form-control" required
                            name="jenis_senar" id="exampleFormControlInput1" placeholder="Jenis Senar" />
                    </div>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Merek <small
                                class="text-danger">*</small></label>
                        <input type="text" value="<?=$alternatif['merek'];?>" class="form-control" required name="merek"
                            id="exampleFormControlInput1" placeholder="Merek" />
                    </div>
                    <div class="card-body">
                        <label for="exampleFormControlInput1" class="form-label">Nama Toko <small
                                class="text-danger">*</small></label>
                        <input type="text" value="<?=$alternatif['nama_toko'];?>" class="form-control" required
                            name="nama_toko" id="exampleFormControlInput1" placeholder="Nama Toko" />
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="gambar_lama" value="<?=$alternatif['gambar'];?>">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" accept=".jpg, .jpeg, .png" class="form-control"
                            value="<?=$alternatif['gambar'];?>" name="gambar" id="gambar" placeholder="Gambar" />
                        <small><i>Jika gambar tidak diubah, maka tidak perlu diupload lagi.</i></small>
                    </div>

                    <div class="card-body">

                        <label for="harga" class="form-label">Harga <small class="text-danger">*</small></label>
                        <select class="form-control" name="harga" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubHarga as $key => $harga):?>
                            <option <?= $harga['id_sub_kriteria'] == $alternatif['id_sub_C1'] ? 'selected':'';?>
                                value="<?=$harga['id_sub_kriteria'];?>">
                                <?=$harga['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="jenis_gitar" class="form-label">Jenis Gitar <small
                                class="text-danger">*</small></label>
                        <select class="form-control" name="jenis_gitar" required aria-label="Default select example">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubJenisGitar as $key => $gitar):?>
                            <option <?= $gitar['id_sub_kriteria'] == $alternatif['id_sub_C2'] ? 'selected':'';?>
                                value="<?=$gitar['id_sub_kriteria'];?>">
                                <?=$gitar['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="bahan_kayu" class="form-label">Bahan Kayu <small
                                class="text-danger">*</small></label>
                        <select class="form-control" name="bahan_kayu" required aria-label="Default select example">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubBahanKayu as $key => $kayu):?>
                            <option <?= $kayu['id_sub_kriteria'] == $alternatif['id_sub_C3'] ? 'selected':'';?>
                                value="<?=$kayu['id_sub_kriteria'];?>">
                                <?=$kayu['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="bentuk" class="form-label">Bentuk <small class="text-danger">*</small></label>
                        <select class="form-control" name="bentuk" required aria-label="Default select example">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($dataSubBentuk as $key => $bentuk):?>
                            <option <?= $bentuk['id_sub_kriteria'] == $alternatif['id_sub_C4'] ? 'selected':'';?>
                                value="<?=$bentuk['id_sub_kriteria'];?>">
                                <?=$bentuk['nama_sub_kriteria'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php foreach ($dataAlternatif as $alternatif):?>
<div class="modal fade" id="hapus<?=$alternatif['id_alternatif'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus alternatif <strong>
                            <?=$alternatif['nama_gitar'];?></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="hapus" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php require_once './footer.php';?>