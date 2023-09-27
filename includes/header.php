<?php 
require_once '../config.php';
require_once '../user/functions/pesan.php';
require_once './functions/notifikasi.php';
if(!isset($_SESSION['login']) && $_SESSION['login'] != true){
    header("Location: ../index.php");
}
else if($_SESSION['jenjang'] != 'sma' && $_SESSION['jenjang'] != 'pt'){
    header("Location: ../404.php");
    exit;
}
$countBelumDibaca = $Pesan->countBelumDibaca($_SESSION['id_user']);
$getPesan = $Pesan->getPesan($_SESSION['id_user']);
$dataPesan = mysqli_fetch_assoc($getPesan);
$jumlah_pesan =  mysqli_num_rows($countBelumDibaca);

function modifWaktu($tanggal=null){
    date_default_timezone_set('Asia/Taipei');
    // Menghitung selisih waktu dalam detik antara waktu asli dan waktu sekarang
    $now = date('Y-m-d H:i:s');
    $diff = abs(strtotime($now) - strtotime($tanggal));

    // Mengonversi selisih waktu ke detik, menit, jam, hari, bulan, dan tahun
    $seconds = $diff;
    $minutes = round($diff / 60);
    $hours = round($diff / 3600);
    $days = round($diff / 86400);
    $months = round($diff / 2592000);
    $years = round($diff / 31536000);

    // Membentuk pesan dengan format yang sesuai
    $message = '';
    if ($seconds < 60) {
        $message = $seconds . " detik yang lalu";
    } elseif ($minutes < 60) {
        $message = $minutes . " menit yang lalu";
    } elseif ($hours < 24) {
        $message = $hours . " jam yang lalu";
    } elseif ($days < 30) {
        $message = $days . " hari yang lalu";
    } elseif ($months < 12) {
        $message = $months . " bulan yang lalu";
    } else {
        $message = $years . " tahun yang lalu";
    }
    return $message;
}
// update pesan
if(isset($_POST['dibuka'])){
    $id_pesan = htmlspecialchars($_POST['id_pesan']);
    $Pesan->updatePesan($id_pesan);
}
$getNotifikasi = $Notifikasi->getNotifikasi((int)$_SESSION['id_user']);
$showNotif = $Notifikasi->getNotifikasiBelumDibuka((int)$_SESSION['id_user']);
$fetchGetNotifikasi = mysqli_fetch_assoc($getNotifikasi);
$countBelumDibaca = mysqli_num_rows($Notifikasi->countBelumDibaca((int)$_SESSION['id_user']));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>SPK Seleksi Beasiswa</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Manrope:wght@400;700;800&family=Prompt&family=Public+Sans:wght@200&family=Righteous&family=Roboto:wght@500&display=swap"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet" />
    <!-- Custom styles for this page -->
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../assets/lightbox2/css/lightbox.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    #accordionSidebar {
        background-color: #D49B54;
    }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <div class="sidebar-brand-icon rotate-n-15" style="display: none">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Pengguna <sup><?=$_SESSION['username'];?></sup></div>
            </a>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= $_SESSION['menu'] == 'index' ? 'active':'';?>">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item <?= $_SESSION['menu'] == 'data-diri' ? 'active':'';?>">
                <a class="nav-link" href="data_diri.php">
                    <i class="fa fa-table" aria-hidden="true"></i>
                    <span>Data diri</span></a>
            </li>
            <li class="nav-item <?= $_SESSION['menu'] == 'pengajuan' ? 'active':'';?>">
                <a class="nav-link" href="pengajuan.php">
                    <i class="fa fa-table" aria-hidden="true"></i>
                    <span>Pengajuan Beasiswa</span></a>
            </li>
            <!-- <li class="nav-item <?= $_SESSION['menu'] == 'pesan' ? 'active':'';?>">
                <a class="nav-link" href="pesan.php">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <span>Pesan</span></a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter"><?=$jumlah_pesan;?></span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Pesan</h6>
                                <?php foreach ($getPesan as $key => $pesan):?>
                                <button
                                    class="dropdown-item d-flex align-items-center <?=$pesan['dibuka'] == '0' ? 'bg-light':'bg-white'?>"
                                    bs-target="button" data-toggle="modal"
                                    data-target="#lihatPesan<?=$pesan['id_pesan'];?>">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="../assets/img/undraw_profile_2.svg"
                                            alt="..." />
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="<?=$pesan['dibuka'] == '0' ? 'font-weight-bold':''?>">
                                        <div class="text-truncate">
                                            <?=$pesan['pesan']?>
                                        </div>
                                        <div class="small text-gray-500"><?=$pesan['username']?> ·
                                            <?=modifWaktu($pesan['tanggal_kirim']);?></div>
                                    </div>
                                </button>
                                <?php endforeach;?>
                                <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a> -->
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter"><?=$countBelumDibaca?></span>
                            </a>
                            <!-- notifikasi -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">Notifikasi</h6>
                                <?php if(mysqli_num_rows($showNotif) > 0):?>
                                <?php foreach ($showNotif as $key => $notifikasi) :?>
                                <a class="dropdown-item d-flex align-items-center"
                                    href="./pengajuan.php?n=<?=base64_encode($notifikasi['id_notif']);?>">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500"><?=modifWaktu($notifikasi['tanggal']);?></div>
                                        <span
                                            class="<?=$notifikasi['dibuka'] == '0'?'font-weight-bold':'';?>"><?=$notifikasi['isi_notifikasi'];?>.</span>
                                    </div>
                                </a>
                                <?php endforeach;?>
                                <?php else:?>
                                <div class="d-flex justify-content-center mt-2">
                                    <p class="text-center"><i>Tidak ada notifikasi.</i></p>
                                </div>
                                <?php endif;?>
                                <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> -->
                            </div>
                        </li>
                        <!-- end notifikasi -->
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-none d-lg-inline text-gray-600 small"><?=$_SESSION['username'];?></span>
                                <img class="rounded-circle ml-2" style="width:30px; height:30px;"
                                    src="../assets/img/undraw_profile_2.svg" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../auth/logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>

                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <!-- <h1 class="h3 mb-0 text-gray-800">Dashboard</h1> -->
                        <!-- <a
                href="#"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                ><i class="fas fa-download fa-sm text-white-50"></i> Generate
                Report</a
              > -->
                    </div>
                    <!-- Content Row -->

                    <!-- modal pesan -->
                    <?php foreach ($getPesan as $key => $pesan):?>
                    <div class="modal fade" id="lihatPesan<?=$pesan['id_pesan'];?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">PESAN</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-body" style="font-family: 'Lato', sans-serif;">
                                        <label class="">
                                            Dari : <strong><?=$pesan['username']?></strong>
                                        </label><br>
                                        <label for="isi_pesan">Isi Pesan : </label>
                                        <div class="alert alert-info" role="alert">
                                            <p class="text-end"><?=$pesan['pesan']?></p>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <small
                                                class="text-end"><i><?=modifWaktu($pesan['tanggal_kirim']);?></i></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php if($pesan['dibuka'] == '0'):?>
                                    <form action="" method="post">
                                        <input type="hidden" name="id_pesan" value="<?=$pesan['id_pesan'];?>">
                                        <button type="submit" name="dibuka" class="btn btn-primary">OK</button>
                                    </form>
                                    <?php else:?>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>