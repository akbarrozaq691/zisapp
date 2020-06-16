<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_mustahik	= $_POST['kode_mustahik'];

    /* SQL Query Update */
    $sqlMustahik= "UPDATE tm_mustahik SET status='REJECT' WHERE kode_mustahik='$kode_mustahik' ";

    if($kode_mustahik!=""){

        $deleteMustahik = $konek->query($sqlMustahik);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}