<?php 

require './config.php';
global $koneks;
$dataGitar = $koneksi->query("SELECT * FROM alternatif");
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
    <style>
    #mapid,
    .teks {
        height: 70vh;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
                            <a class="nav-link fw-bolder active" aria-current="page" href="./home.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bolder" aria-current="page" href="./hasil.php">Rekomendasi</a>
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
        <div class="container d-flex mt-5 pt-3" style="font-family: 'Prompt', sans-serif;">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-12 p-lg-4 m-lg-3">
                    <div class="row d-flex justify-content-center p-lg-5">
                        <?php foreach ($dataGitar as $key => $gitar):?>
                        <div class="card col-lg-12 m-2" style="width: 20rem;"
                            data-aos="fade<?=$key % 3 == 0?'-rigth':'-left';?>" data-aos-easing="linear"
                            data-aos-duration="1500">
                            <img src="./assets/images/Screenshot (7).png" style="width: 320px; margin-left: -12px;"
                                class="card-img-top" alt="Gambar <?=$gitar['nama_gitar'];?>">
                            <div class="card-body">
                                <div class="text">
                                    <h6 class="card-text fs-5 fw-bold"><?= $gitar['nama_gitar'];?></h6>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
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
        <!-- jquery datatables -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
        AOS.init();
        </script>
</body>

</html>