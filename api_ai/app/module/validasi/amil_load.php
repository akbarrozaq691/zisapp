<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $kode_amil  = $_GET['kode_amil'];

    $periode    = $_GET['periode'];

    $sqlFind 	= "SELECT * FROM trs_kas WHERE kode_kas='$kode_amil' AND periode='$periode' AND status='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}