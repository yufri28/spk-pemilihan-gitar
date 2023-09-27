<?php 
session_start();
if(isset($_SESSION['login']) && $_SESSION['login'] == true && ($_SESSION['level'] == "sma" || $_SESSION['level'] == "pt")){
    header("Location: ./user/index.php");
}else if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['level'] == 1){
    header("Location: ./admin/index.php");
}
require_once './config.php';
// Memproses inputan dari form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $jenjang = $_POST["jenjang"];
  $password_hash = password_hash($password, PASSWORD_BCRYPT);
  // Mengecek apakah username dan password sesuai
  $sql = "INSERT INTO login_pelamar (id_login,username,password,jenjang) VALUES (null,'$username','$password_hash','$jenjang')";
  $result = $koneksi->query($sql);
  if ($result) {
    echo "<script>alert('Daftar berhasil!');</script>";
    header("Location: ./auth/login.php");
  } else {
    // Login gagal, tampilkan pesan error
    echo "<script>alert('Login gagal');</script>";
    header("Location: login.php");
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Prompt&family=Righteous&family=Roboto:wght@500&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="../assets/DataTables/datatables.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Section: Design Block -->
    <section class="">
        <!-- Jumbotron -->
        <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
            <div class="container" style="height:100vh;">
                <div class="row gx-lg-5 d-flex mt-5 justify-content-center align-items-center">
                    <div class="col-lg-5 mb-5 mb-lg-0">
                        <div class="card">
                            <div class="card-body py-3 px-md-5">
                                <h1 class="mt-2 text-center mb-3">REGISTRASI</h1>
                                <form method="post" action="">
                                    <!-- Email input -->
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="username">Username <small
                                                class="text-danger">*</small></label>
                                        <input type="text" id="username" placeholder="username" required name="username"
                                            class="form-control" />
                                    </div>
                                    <!-- Password input -->
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="password">Password <small
                                                class="text-danger">*</small></label>
                                        <input type="password" id="password" placeholder="******" required
                                            name="password" class="form-control" />
                                    </div>
                                    <div class="form-outline mb-3">
                                        <label class="form-label" for="password-konfirmation">Konfirmasi Password <small
                                                class="text-danger">*</small></label>
                                        <input type="password" id="password-konfirmation" placeholder="******" required
                                            name="password-konfirmation" class="form-control" />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="jenjang">Jenjang <small
                                                class="text-danger">*</small></label>
                                        <select class="form-select" name="jenjang" required
                                            aria-label="Default select example">
                                            <option value="">-- Pilih Jenjang --</option>
                                            <option value="sma">SMA/SMK Sederajat</option>
                                            <option value="pt">Perguruan Tinggi</option>
                                        </select>
                                    </div>
                                    <!-- Submit button -->
                                    <button type="submit" name="daftar" class="btn col-12 btn-primary btn-block mb-3">
                                        Register
                                    </button>
                                    <span>Sudah punya akun ?</span>
                                    <a href="./auth/login.php">
                                        Login disini
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jumbotron -->
    </section>
    <!-- Section: Design Block -->
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>

<script src="../assets/DataTables/jquery.js"></script>
<script src="../assets/DataTables/datatables.min.js"></script>
<script>
// Mendapatkan referensi elemen input password dan konfirmasi password
var passwordInput = document.getElementById("password");
var confirmPasswordInput = document.getElementById("password-konfirmation");

// Menambahkan event listener pada input konfirmasi password
confirmPasswordInput.addEventListener("input", validatePassword);

function validatePassword() {
    var password = passwordInput.value;
    var confirmPassword = confirmPasswordInput.value;

    // Memeriksa apakah password dan konfirmasi password sama
    if (password !== confirmPassword) {
        confirmPasswordInput.setCustomValidity("Konfirmasi password tidak sesuai");
    } else {
        confirmPasswordInput.setCustomValidity("");
    }
}
</script>

<!-- Sweet Alert -->
<script>