<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_pengajuan	= $_POST['no_pengajuan'];

    /* SQL Query Update */
    $sqlNoPengajuan= "UPDATE trs_pengajuan SET status='REJECT' WHERE no_pengajuan='$no_pengajuan' ";

    $sqlCekData = "SELECT * FROM trs_pengajuan WHERE no_pengajuan='$no_pengajuan' AND status='PENGAJUAN'";

    $exe_sqlCekData = $konek->query($sqlCekData);
    
    $cekPengajuan	= mysqli_num_rows($exe_sqlCekData);

    if($cekPengajuan){

        $deletePengajuan = $konek->query($sqlNoPengajuan);    

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Sudah Diproses Tidak Bisa Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}