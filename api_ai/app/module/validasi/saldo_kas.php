<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_trskas  = $_GET['no_trskas'];

    $periode    = $_GET['periode'];

    $sqlFind 	= "SELECT * FROM trs_kas WHERE no_trskas='$no_trskas' AND periode='$periode' AND status='Aktif'";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}