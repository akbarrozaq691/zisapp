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

    $keterangan 	    = strip_tags($_POST['keterangan']);
    
    $status 		    = strip_tags($_POST['status']);
    
    $jml_realisasi      = $_POST['jml_realisasi'];
    
    $tgl_realisasi 	    = strip_tags($_POST['tgl_realisasi']);

    $approved           = date('Y-m-d');
    
    $approved_by        = $_SESSION['kode_petugas'];    
     
    /* SQL Query Simpan */
    $sqlUpdateDisposisi = "UPDATE trs_disposisi SET 
                                status='$status'
                            WHERE no_disposisi='$no_disposisi'";

    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                status='$status'
                            WHERE no_pengajuan='$no_pengajuan'";

    $sqlRealisasi = "UPDATE trs_pengajuan SET 
                                keterangan='$keterangan', 
                                status='$status', 
                                jml_realisasi='$jml_realisasi',
                                tgl_realisasi='$tgl_realisasi',
                                approved='$approved',
                                approved_by='$approved_by' 
                            WHERE no_pengajuan='$no_pengajuan'";
    
    // $rekomendasiPengajuan = $konek->query($sqlUpdatePengajuan);

    if($status!='REALISASI'){

        $konek->query($sqlUpdatePengajuan);

        $konek->query($sqlUpdateDisposisi);

        $pesan 		= "Pengajuan Telah Diproses";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }else{

        $konek->query($sqlRealisasi);

        $konek->query($sqlUpdateDisposisi);
    
        $pesan 		= "Pengajuan Telah Diproses";
        
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);

    }

   

}