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
    $sqlNoDisposisi= "UPDATE trs_disposisi SET status='REJECT' WHERE no_disposisi='$no_disposisi' ";

    $sqlCekData = "SELECT * FROM trs_disposisi WHERE no_pengajuan='$no_pengajuan' AND status='REALISASI'";

    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                status='REJECT' 
                            WHERE no_pengajuan='$no_pengajuan'";
    
    $exe_sqlCekData = $konek->query($sqlCekData);
    
    $cekDisposisi	= mysqli_num_rows($exe_sqlCekData);

    if($cekDisposisi){

        $pesan 		= "Data Gagal Dihapus / Data Sudah Direalisasi";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    } else {
        
        $updatePengajuan = $konek->query($sqlUpdatePengajuan);

        $pesan 		= "Data Berhasil Dihapus";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);

    }

}