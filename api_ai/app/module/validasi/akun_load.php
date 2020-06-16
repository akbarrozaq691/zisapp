<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_kategori = $_GET['kode_kategori'];

    $sqlFind 	= "SELECT * FROM tm_kategori WHERE kode_kategori = '$kode_kategori'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}