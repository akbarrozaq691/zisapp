<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_rekening	= $_POST['no_rekening'];

    $nama_bank 	    = strip_tags($_POST['nama_bank']);
    
    $status 		= strip_tags($_POST['status']);
    
    $no_kantor 		= strip_tags($_POST['no_kantor']);

    /* SQL Query Update */
    $sqlBank = "UPDATE tm_bank_kl SET

            nama_bank='$nama_bank',
    
            status='$status',
    
            no_kantor='$no_kantor'
    
        WHERE no_rekening='$no_rekening' ";

    if($no_rekening!=""){

        $updateBank = $konek->query($sqlBank);    

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}