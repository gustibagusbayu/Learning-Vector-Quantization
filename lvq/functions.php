<?php 

$koneksi = mysqli_connect("localhost", "root", "", "kidney_disease");
if (mysqli_connect_errno()) {
    echo "Koneksi Database Gagal : " . mysqli_connect_error();
}


?>