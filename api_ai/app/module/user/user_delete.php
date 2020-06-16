<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $kode_petugas	= $_POST['kode_petugas'];

    /* SQL Query Update */
    $sqlPetugas = "UPDATE tm_petugas SET status='REJECT' WHERE kode_petugas='$kode_petugas' ";

    $sqlUser = "UPDATE sys_users SET active='REJECT' WHERE kode_petugas='$kode_petugas' ";

    if($kode_petugas!=""){

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $deletePetugas = $konek->query($sqlPetugas);
        
            $deletePetugas = $konek->query($sqlUser);

        $konek->commit();        

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}