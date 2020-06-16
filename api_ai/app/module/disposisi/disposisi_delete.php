<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

  
    /** Variabel From Post */
    $no_disposisi	= $_POST['no_disposisi'];

    /* SQL Query Update */
    $sqlNoDisposisi= "UPDATE trs_disposisi SET status='REJECT' WHERE no_disposisi='$no_disposisi' ";

    $sqlCekData = "SELECT * FROM trs_disposisi WHERE no_disposisi='$no_disposisi' AND status='PROSES'";

    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                no_disposisi='', 
                                status='Pengajuan' 
                            WHERE no_disposisi='$no_disposisi'";
    
    $exe_sqlCekData = $konek->query($sqlCekData);
    
    $cekDisposisi	= mysqli_num_rows($exe_sqlCekData);

    if($cekDisposisi){

        $deleteDisposisi = $konek->query($sqlNoDisposisi);
        
        $updatePengajuan = $konek->query($sqlUpdatePengajuan);

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    } else {

        $pesan 		= "Data Gagal Dihapus";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}