<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'index';
?>
<?php require './header.php';?>
<div class="row">
    <!-- Area Chart -->
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-4 d-flex flex-row align-items-center justify-content-between">
                <div class="justify-content-center text-center p-5">
                    <h5 class="text-center mb-5">
                        SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN GITAR
                    </h5>
                    <p>
                        Sistem Pendukung Keputusan (SPK) atau Decision Support System (DSS) adalah sistem informasi yang
                        berbasis komputer yang fleksibel, interaktif dan dapat diadaptasi, yang dikembangkan untuk
                        mendukung solusi untuk masalah manajemen spesifik yang tidak terstruktur. Sistem Pendukung
                        Keputusan menggunakan data, memberikan antarmuka pengguna yang mudah dan dapat menggabungkan
                        pemikiran pengambilan keputusan (Turban et al., 2011).
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require './footer.php';?>