<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $npwz	= $_POST['npwz'];

    /* SQL Query Update */
    $sqlNpwz= "UPDATE tm_donatur SET status='REJECT' WHERE npwz='$npwz' ";

    if($npwz!=""){

        $deleteMuzaki = $konek->query($sqlNpwz);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}