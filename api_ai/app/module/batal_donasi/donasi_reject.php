<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_donasi	= $_POST['no_donasi'];

    /* SQL Query Update */
    $sqlReject = "UPDATE trs_donasi SET status='REJECT' WHERE no_donasi='$no_donasi' ";

    if($no_donasi!=""){

        $updateReject = $konek->query($sqlReject);    

        $pesan 		= "Donasi Berhasil Dibatalkan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Donasi Gagal Dibatalkan";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}