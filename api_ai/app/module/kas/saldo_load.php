<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    $no_trskas = $_GET['no_trskas'];

    $sqlFind 	= "SELECT * FROM trs_kas_dtl WHERE no_trskas = '$no_trskas' ORDER BY id_kas_dtl DESC";
    
    $hasilFind	= $konek->query($sqlFind);

    $data['data'] = $hasilFind->fetch_assoc();
    
    echo json_encode($data['data']);

}