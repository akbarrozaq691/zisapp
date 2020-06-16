<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_petugas = $_SESSION['kode_petugas'];

    $sqlFind 	= "SELECT nama_petugas FROM view_user WHERE kode_petugas = '$kode_petugas'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}