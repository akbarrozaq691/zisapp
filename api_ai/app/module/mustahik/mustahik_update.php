<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_mustahik	= $_POST['kode_mustahik'];

    $nama_mustahik 	= strip_tags($_POST['nama_mustahik']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $hp 			= strip_tags($_POST['hp']);
    
    $kategori 		= strip_tags($_POST['kategori']);
    
    $status 		= strip_tags($_POST['status']);
      
    $no_kantor 		= strip_tags($_POST['no_kantor']);

    /* SQL Query Update */
    $sqlMustahik = "UPDATE tm_mustahik SET

            nama_mustahik='$nama_mustahik',
    
            alamat='$alamat',
    
            hp='$hp',
    
            kategori='$kategori',

            no_kantor='$no_kantor',
    
            status='$status'
    
        WHERE kode_mustahik='$kode_mustahik' ";

    if($kode_mustahik!=""){

        $updateMustahik = $konek->query($sqlMustahik);    

        $pesan 		= "Data Berhasil Dirubah";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dirubah";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}