<?php 

require './config.php';
$post = false;

$C1 = 0;
$C2 = 0;
$C3 = 0;
$C4 = 0;
$harga = 0;
$jenis_gitar = 0;
$bahan_kayu = 0;
$bentuk = 0;
$totalBobot = 0;
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $data_bobot = $_POST['t_bobot_kriteria'];
    $C1 = htmlspecialchars($data_bobot[0]);
    $C2 = htmlspecialchars($data_bobot[1]);
    $C3 = htmlspecialchars($data_bobot[2]);
    $C4 = htmlspecialchars($data_bobot[3]);

    $totalBobot = $C1+$C2+$C3+$C4;

    if($totalBobot == 0){
        $totalBobot = 1;
        $harga = 1;
        $jenis_gitar = 1;
        $bahan_kayu = 1;
        $bentuk = 1;
    }else{
        $harga = $C1/$totalBobot;
        $jenis_gitar = $C2/$totalBobot;
        $bahan_kayu = $C3/$totalBobot;
        $bentuk = $C4/$totalBobot;
    }
    $post = true;
}


$data = $koneksi->query(
    "SELECT 
    a.nama_gitar,  
    a.id_alternatif, 
    a.jenis_senar, 
    a.merek, 
    a.gambar, 
    a.nama_toko,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
    MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
    MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS sub_C1,
    MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS sub_C2,
    MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.nama_sub_kriteria END) AS sub_C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS sub_C4,
    ((MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT SUM(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) 
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * $harga) + (MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) / (SELECT SUM(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) 
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * $jenis_gitar) + (MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT SUM(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * $bahan_kayu)) AS S_plus,
    (MIN(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END)/(SELECT SUM(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * $bentuk) AS S_min
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
    GROUP BY a.nama_gitar
    UNION ALL
    SELECT 'jumlah',
    NULL AS jenis_senar,
    NULL AS id_alternatif,
    NULL AS merek,
    NULL AS gambar,
    NULL AS nama_toko,
    SUM(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
    SUM(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
    SUM(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
    SUM(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
    NULL AS sub_C1,
    NULL AS sub_C2,
    NULL AS sub_C3,
    NULL AS sub_C4,
    NULL AS div_C1,
    NULL AS div_C2
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria;");

    // hitung jumlah cost
    $jumlah_cost = 0;
    foreach ($data as $key => $value) {
        $jumlah_cost += $value['S_min'];
    }

    // end hitung jumlah cost
    // 6.	Hitung bobot relatif tiap alternatif dengan menggunakan Persamaan 2.5

    $total_bobot_rel = 0; 
    $total_S_i = 0; 
    $maxQ = 0;
    $Qn = array();
    $Ui = 0;
    $hasil_perengkingan = array();
    
    if($post != true){
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $relatif = 1 / 1;
                $total_bobot_rel += $relatif;
            }
        }
         // Hitung S-i × total dari 1/S-i
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $total_S_i = $value['S_min'] * 1;
            }
        }

         // menampilkan data S_plus
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $total_S_i = $value['S_min'] * $total_bobot_rel;
                array_push($Qn,($value['S_plus']+0));
            }
        }

        $maxQ = max($Qn);

        // 7.	Hitung utilitas kuantitatif (Ui) untuk setiap alternatif dengan menggunakan Persamaan 2.6
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $total_S_i = $value['S_min'] * $total_bobot_rel;
                $Ui = (0);

                $hasil_perengkingan [] = [
                    'id_alternatif' => $value['id_alternatif'],
                    'nama_gitar' => $value['nama_gitar'],
                    'UI' => $Ui,
                    'jenis_senar' => $value['jenis_senar'],
                    'merek' => $value['merek'],
                    'gambar' => $value['gambar'],
                    'nama_toko' => $value['nama_toko'],
                    'sub_C1' => $value['sub_C1'],
                    'sub_C2' => $value['sub_C2'],
                    'sub_C3' => $value['sub_C3'],
                    'sub_C4' => $value['sub_C4'],
                ];
            }
        }
    }else{
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $relatif = $value['S_min'] != 0 ? 1 / $value['S_min']:0;
                $total_bobot_rel += $relatif;
            }
        }
        
        // Hitung S-i × total dari 1/S-i
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $total_S_i = $value['S_min'] * $total_bobot_rel;
            }
        }

        // menampilkan data S_plus
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $total_S_i = $value['S_min'] * $total_bobot_rel;
                array_push($Qn,($value['S_plus']+($total_S_i != 0 ? ($jumlah_cost/$total_S_i):0)));
            }
        }
        $maxQ = max($Qn);
        // 7.	Hitung utilitas kuantitatif (Ui) untuk setiap alternatif dengan menggunakan Persamaan 2.6
        foreach ($data as $key => $value) {
            if ($value['nama_gitar'] != 'jumlah') {
                $total_S_i = $value['S_min'] * $total_bobot_rel;
                $Ui = (($value['S_plus']+($total_S_i != 0 ? ($jumlah_cost/$total_S_i):0)) / $maxQ) * 100;

                $hasil_perengkingan [] = [
                    'id_alternatif' => $value['id_alternatif'],
                    'nama_gitar' => $value['nama_gitar'],
                    'UI' => $Ui,
                    'jenis_senar' => $value['jenis_senar'],
                    'merek' => $value['merek'],
                    'gambar' => $value['gambar'],
                    'nama_toko' => $value['nama_toko'],
                    'sub_C1' => $value['sub_C1'],
                    'sub_C2' => $value['sub_C2'],
                    'sub_C3' => $value['sub_C3'],
                    'sub_C4' => $value['sub_C4'],
                ];
            }
        }
    }
    function compareNilaiAkhir($a, $b) {
        if ($a['UI'] == $b['UI']) {
            return 0;
        }
        return ($a['UI'] > $b['UI']) ? -1 : 1;
    }

    // Menggunakan fungsi usort untuk mengurutkan array berdasarkan UI
    usort($hasil_perengkingan, 'compareNilaiAkhir');
?>

<!DOCTYPE html>
<html>

<head>
    <title>SPK Pemilihan Gitar</title>
    <style>
    .navbar-transparent {
        background-color: hsl(0, 0%, 96%);
    }

    @media (min-width: 992px) {
        .navbar-transparent {
            margin-bottom: -40px;
        }
    }

    .navbar-brand {
        font-family: 'Rubik', sans-serif;
    }

    .nav-link {
        font-family: 'Prompt', sans-serif;
    }

    .input-search {
        border-radius: 0.3rem;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let button_like_link = document.getElementById('btn-like-link');

        button_like_link.addEventListener('click', function() {
            Swal.fire({
                title: 'Panduan',
                text: 'Masukkan Range Bobot Kriteria Dimana Range Bobot Setiap Kriteria Adalah 0 Sampai 100 dan Bobot Terbesar Menunjukan Kriteria Yang Diprioritaskan.',
                icon: 'warning',
                confirmButtonText: 'Paham'
            });
        });
    });
    </script>

    <style>
    .button-like-link {
        background: none;
        border: none;
        color: blue;
        /* Warna teks mirip tautan */
        text-decoration: none;
        /* Garis bawah mirip tautan */
        cursor: pointer;
        /* Jika ingin menyesuaikan tampilan saat digerakkan mouse */
    }

    .button-like-link:hover {
        text-decoration: none;
        /* Menghilangkan garis bawah saat digerakkan mouse */
        /* Sesuaikan tampilan hover sesuai keinginan */
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Prompt&family=Righteous&family=Roboto:wght@500&family=Rubik:wght@600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="./assets/vendor/fontawesome-free/css/all.min.css">
    <script src="./assets/vendor/fontawesome-free/js/all.min.js"></script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SPK Pemilihan Gitar</title>
    <link href="./assets/DataTables/datatables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    #mapid,
    .teks {
        height: 70vh;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <section class="">
        <!-- Section: Design Block -->
        <nav class="navbar fixed-top navbar-transparent shadow-sm navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand fw-bolder" href="#">SPK Pem Gitar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="./home.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="./hasil.php">Rekomendasi</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="btn btn-outline-secondary fw-bolder" aria-current="page"
                                href="./auth/login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <hr>
        <hr class="navbar-transparent">
        <div class="container mt-5 pt-3" style="font-family: 'Prompt', sans-serif;">
            <div class="row">
                <!-- <div class="d-md-flex"> -->
                <div class="col-md-4 mt-3 mb-4">
                    <div class="card shadow-lg">
                        <div class="card-header" style="background-color:#BD9354">
                            <h5 class="text-center text-white pt-2 col-12">
                                Masukkan Range Bobot
                            </h5>
                        </div>
                        <form method="post" id="editKriteriaForm" action="">
                            <div class="card-body">
                                <button type="button" id="btn-like-link"
                                    class="button-like-link col-lg-12 d-flex justify-content-end"><small
                                        class="">Panduan?</small></button>
                                <script>
                                function updateWeight1(value) {
                                    document.getElementById('bobotValue1').innerText = 'Bobot Harga: ' +
                                        value;
                                }

                                function updateWeight2(value) {
                                    document.getElementById('bobotValue2').textContent = 'Bobot Jenis Gitar: ' + value;
                                }

                                function updateWeight3(value) {
                                    document.getElementById('bobotValue3').textContent =
                                        'Bobot Bahan Kayu: ' + value;
                                }

                                function updateWeight4(value) {
                                    document.getElementById('bobotValue4').textContent =
                                        'Bobot Bentuk: ' + value;
                                }


                                // Inisialisasi bobot saat halaman dimuat
                                window.onload = function() {
                                    var initialValue1 = document.querySelector('.bobot-kriteria1').value;
                                    var initialValue2 = document.querySelector('.bobot-kriteria2').value;
                                    var initialValue3 = document.querySelector('.bobot-kriteria3').value;
                                    var initialValue4 = document.querySelector('.bobot-kriteria4').value;
                                    updateWeight1(initialValue1);
                                    updateWeight2(initialValue2);
                                    updateWeight3(initialValue3);
                                    updateWeight4(initialValue4);
                                };
                                </script>
                                <hr>
                                <i><small>Range bobot setiap Kriteria : 0 - 100</small></i>
                                <div class="mb-3 mt-3">
                                    <span id="bobotValue1">Bobot <label for="bobot_kriteria"
                                            class="form-label">Harga</label>: 0</span>
                                    <input type="range" min="0" max="100" oninput="updateWeight1(this.value)"
                                        onload="updateWeight1(this.value)" class="form-range bobot-kriteria1"
                                        name="t_bobot_kriteria[]" value="<?=$C1;?>">
                                </div>
                                <div class="mb-3 mt-3">

                                    <span id="bobotValue2">Bobot <label for="bobot_kriteria" class="form-label">Jenis
                                            Gitar</label>: 0</span>
                                    <input type="range" min="0" max="100" oninput="updateWeight2(this.value)"
                                        onload="updateWeight2(this.value)" class="form-range bobot-kriteria2"
                                        name="t_bobot_kriteria[]" value="<?=$C2?>">
                                </div>
                                <div class="mb-3 mt-3">

                                    <span id="bobotValue3">Bobot <label for="bobot_kriteria" class="form-label">Bahan
                                            Kayu</label>: 0</span>
                                    <input type="range" min="0" max="100" oninput="updateWeight3(this.value)"
                                        onload="updateWeight3(this.value)" class="form-range bobot-kriteria3"
                                        name="t_bobot_kriteria[]" value="<?=$C3?>">
                                </div>
                                <div class="mb-3 mt-3">

                                    <span id="bobotValue4">Bobot <label for="bobot_kriteria"
                                            class="form-label">Bentuk</label>: 0</span>
                                    <input type="range" min="0" max="100" oninput="updateWeight4(this.value)"
                                        onload="updateWeight4(this.value)" class="form-range bobot-kriteria4"
                                        name="t_bobot_kriteria[]" value="<?=$C4?>">
                                </div>
                                <button type="submit" name="simpan" class="btn col-12 btn-outline-primary">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8 mt-3 mb-3">
                    <div class="card shadow-lg">
                        <div class="card-header" style="background-color:#BD9354">
                            <h5 class="text-start text-white">DAFTAR GITAR</h5>
                            <hr>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row d-flex justify-content-center" id="cardContainer">
                                    <div class="mb-3 col-lg-10">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                                    </div>
                                    <?php foreach($hasil_perengkingan as $key => $value):?>
                                    <div class="card card-gitar col-lg-3 m-2" style="width: 20rem;">
                                        <a class="card-img-top" style="margin-left: -12px; margin-rigth:-12px;"
                                            href="./assets/images/<?= $value['gambar'] == '-' || $value['gambar'] == '' || $value['gambar'] == NULL ? 'default.png': $value['gambar'];?>"
                                            data-lightbox="image-1" data-title="<?= $value['nama_gitar']; ?>"><img
                                                style="width:318px; height:200px;"
                                                src="./assets/images/<?= $value['gambar'] == '-' || $value['gambar'] == ''|| $value['gambar'] == NULL ? 'default.png': $value['gambar']; ?>"
                                                alt="Gambar <?= $value['nama_gitar']; ?>"></a>

                                        <div class="card-body">
                                            <small class="card-title"><?= $key + 1; ?>. <?= $value['nama_gitar']; ?>
                                            </small>
                                            <!-- <div id="detail<?= $key; ?>" class="collapse"> -->
                                            <table class="table table-borderless" id="guitarTable"
                                                style="font-size: 10pt;">
                                                <tbody>
                                                    <tr>
                                                        <td><small>Jenis Senar</small></td>
                                                        <td>:</td>
                                                        <td><small><?= $value['jenis_senar']; ?></small></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Merek</small></td>
                                                        <td>:</td>
                                                        <td><small>
                                                                <?= $value['merek']; ?></small></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>UI</small></td>
                                                        <td>:</td>
                                                        <td><small><?= $value['UI']; ?></small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Nama Toko</small></td>
                                                        <td>:</td>
                                                        <td><small>
                                                                <?= $value['nama_toko']; ?></small></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Range Harga</small></td>
                                                        <td>:</td>
                                                        <td><small>
                                                                <?= $value['sub_C1']; ?></small></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Jenis Gitar</small></td>
                                                        <td>:</td>
                                                        <td><small>
                                                                <?= $value['sub_C2']; ?></small></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Bahan Kayu</small></td>
                                                        <td>:</td>
                                                        <td><small>
                                                                <?= $value['sub_C3']; ?></small></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Bentuk</small></td>
                                                        <td>:</td>
                                                        <td><small>
                                                                <?= $value['sub_C4']; ?></small></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!-- </div> -->
                                            <!-- <div class="">
                                                <button type="button" class="btn col-lg-12 btn-sm btn-primary"
                                                    data-toggle="collapse"
                                                    data-target="#detail<?= $key; ?>">Detail</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            // Ambil elemen input pencarian
            const $searchInput = $("#searchInput");

            // Tambahkan event handler untuk input pencarian
            $searchInput.on("input", function() {
                const searchTerm = $searchInput.val().toLowerCase();

                // Loop melalui semua card gitar
                $("#cardContainer .card").each(function() {
                    const $card = $(this);
                    const cardText = $card.text().toLowerCase();

                    // Periksa apakah teks dalam card mengandung kata kunci pencarian
                    if (cardText.includes(searchTerm)) {
                        // Tampilkan card jika cocok
                        $card.show();
                    } else {
                        // Sembunyikan card jika tidak cocok
                        $card.hide();
                    }
                });
            });
        });
        </script>
        <style>
        .custom-icon {
            text-align: center;
            color: #EB455F;
            font-size: 16pt;
            font-weight: bold;
        }
        </style>
        <footer class="bg-white text-center text-lg-start">
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: #F0F0F0;">
                © 2023 Copyright:
                <a class="text-dark" href="https://www.instagram.com/ilkom19_unc/">Intel'19</a>
            </div>
            <!-- Copyright -->
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
        <script src="./assets/DataTables/jquery.js"></script>
        <script src="./assets/DataTables/datatables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
        AOS.init();
        </script>
        <!-- <script>
        // Ambil semua tombol "Go Somewhere"
        var buttons = document.querySelectorAll(".btn.btn-primary");

        // Tambahkan event listener ke masing-masing tombol
        buttons.forEach(function(button) {
            button.addEventListener("click", function() {
                // Ambil target collapse ID yang sesuai dari tombol
                var targetCollapseId = button.getAttribute("data-target");

                // Toggle tampilan detail
                var targetCollapse = document.querySelector(targetCollapseId);
                if (targetCollapse.style.display === "none") {
                    targetCollapse.style.display = "block";
                } else {
                    targetCollapse.style.display = "none";
                }
            });
        });
        </script> -->
</body>

</html>