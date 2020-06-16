<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";
    
    /** Variabel From Post */

    $no_disposisi 	    = strip_tags($_POST['no_disposisi']);
    
    $no_pengajuan 	    = strip_tags($_POST['no_pengajuan']);

    $rekomendasi 	    = strip_tags($_POST['rekomendasi']);
    
    $keterangan 		= strip_tags($_POST['keterangan']);
    
    $asnaf 			    = strip_tags($_POST['asnaf']);
    
    $sumber_dana 	    = strip_tags($_POST['sumber_dana']);

    $sqlCekData = "SELECT * FROM trs_pengajuan WHERE

                    no_pengajuan='$no_pengajuan' AND status='REKOMENDASI' 
                    
                    OR
                    
                    no_pengajuan='$no_pengajuan' AND status='REALISASI'";

    $sqlCekDataDisposisi = "SELECT * FROM trs_disposisi WHERE

                            no_disposisi='$no_disposisi' AND ISNULL(tgl_survey)
                            
                            OR 

                            no_disposisi='$no_disposisi' AND tgl_survey=''";

    $exe_sqlCekData = $konek->query($sqlCekData);

    $exe_sqlCekDataDisposisi = $konek->query($sqlCekDataDisposisi);

    $cekData	= mysqli_num_rows($exe_sqlCekData);

    $cekDataDisposisi	= mysqli_num_rows($exe_sqlCekDataDisposisi);
     
    /* SQL Query Simpan */    
    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                rekomendasi='$rekomendasi', 
                                keterangan='$keterangan', 
                                asnaf='$asnaf',
                                sumber_dana='$sumber_dana',
                                status='REKOMENDASI' 
                            WHERE no_pengajuan='$no_pengajuan'";
    
    $sqlUpdateDisposisi = "UPDATE trs_disposisi SET 
                                status='REKOMENDASI' 
                            WHERE no_disposisi='$no_disposisi'";
    
    if($cekData > 0 ){

        $pesan 		= "Data Sudah Direkomendasi / Direalisasi ";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }elseif($cekDataDisposisi > 0){

        $pesan 		= "Data Belum Masuk Antrian Survey";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    
    }else{

        $rekomendasiPengajuan = $konek->query($sqlUpdatePengajuan);

        $rekomendasiDisposisi = $konek->query($sqlUpdateDisposisi);
        
        $pesan 		= "Data Berhasil Disimpan";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

}