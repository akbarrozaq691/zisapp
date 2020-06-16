<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";
    
    /** Variabel From Post */

    $no_disposisi 	    = strip_tags($_POST['no_disposisi']);
    
    $tgl_survey 	    = strip_tags($_POST['tgl_survey']);

    $sqlCekDataDisposisi = "SELECT * FROM trs_disposisi WHERE

                            no_disposisi='$no_disposisi' AND tgl_survey!=''";

    $exe_sqlCekDataDisposisi = $konek->query($sqlCekDataDisposisi);

    $cekDataDisposisi	= mysqli_num_rows($exe_sqlCekDataDisposisi);
     
    /* SQL Query Simpan */    
    
    $sqlUpdateDisposisi = "UPDATE trs_disposisi SET 
                                tgl_survey='$tgl_survey' 
                            WHERE no_disposisi='$no_disposisi'";
    
    if($cekDataDisposisi > 0){

        $pesan 		= "Data Sudah Ada Diantrian Survey / Data Telah Disurvey";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }else{

        $rekomendasiDisposisi = $konek->query($sqlUpdateDisposisi);
        
        $pesan 		= "Data Telah Masuk Dalam Antrian Survey";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}