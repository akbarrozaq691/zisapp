<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_kantor	= $_POST['no_kantor'];

    /* SQL Query Update */
    $sqlNoKantor= "UPDATE tm_kantor_layanan SET status='REJECT' WHERE no_kantor='$no_kantor' ";

    if($no_kantor!=""){

        $deleteKantorLayanan = $konek->query($sqlNoKantor);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}