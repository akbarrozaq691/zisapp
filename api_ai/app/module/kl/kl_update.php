<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_kantor	    = $_POST['no_kantor'];

    $nama_kantor 	= strip_tags($_POST['nama_kantor']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $pimpinan 		= strip_tags($_POST['pimpinan']);

    /* SQL Query Update */
    $sqlKantorLayanan = "UPDATE tm_kantor_layanan SET

            nama_kantor='$nama_kantor',
    
            alamat='$alamat',
    
            phone='$no_hp',
    
            pimpinan='$pimpinan'
    
        WHERE no_kantor='$no_kantor' ";

    if($no_kantor!=""){

        $updateKantorLayanan = $konek->query($sqlKantorLayanan);    

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}