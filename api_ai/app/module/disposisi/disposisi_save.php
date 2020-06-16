<?php
session_start();

if(!$_SESSION){

    $pesan = "Your Access Not Authorized";

    echo json_encode($pesan);

} else {    

    include "../../../bin/koneksi.php";

    /*
	* Auto Number Untuk Code Buku 
	*/
	function autonum($lebar=0, $awalan=''){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_disposisi FROM trs_disposisi ORDER BY no_disposisi DESC";
        
        $hasil= $konek->query($sqlcount);
        
        $jumlahrecord = mysqli_num_rows($hasil);

		if($jumlahrecord == 0)
			$nomor=1;
		else {
			$nomor = $jumlahrecord+1;
		}

		if($lebar>0)
			$angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
		else
			$angka = $awalan.$nomor;
		return $angka;
    }
    
    /** Variabel From Post */
    $no_disposisi	    = autonum(6,"DSP-".date('y'));

    $no_pengajuan 	    = strip_tags($_POST['no_pengajuan']);

    $pengirim 	        = strip_tags($_POST['pengirim']);
    
    $no_surat 		    = strip_tags($_POST['no_surat']);
    
    $tgl_surat 			= strip_tags($_POST['tgl_surat']);
    
    $tertanggal_surat 	= strip_tags($_POST['tertanggal_surat']);

    $deliver_to 		= strip_tags($_POST['deliver_to']);

    $perihal 		    = strip_tags($_POST['perihal']);

    $catatan_penerima   = strip_tags($_POST['catatan_penerima']);
     
    /* Validasi Kode */
    $sqlCekNoDisposisi     = "SELECT no_disposisi FROM trs_disposisi WHERE no_disposisi='$no_disposisi'";
    
    $exe_sqlCekNoDisposisi = $konek->query($sqlCekNoDisposisi);

    $cekNoDisposisi	    = mysqli_num_rows($exe_sqlCekNoDisposisi);

    /* SQL Query Simpan */
    $sqlDisposisi = "INSERT INTO 
        trs_disposisi(
            no_disposisi,
            pengirim,
            no_surat,
            tgl_surat,
            tertanggal_surat,
            deliver_to,
            perihal,
            catatan_penerima,
            status
        )VALUES(
            '$no_disposisi',
            '$pengirim',
            '$no_surat',
            '$tgl_surat',
            '$tertanggal_surat',
            '$deliver_to',
            '$perihal',
            '$catatan_penerima',
            'PROSES'
        )";
    
    $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET 
                                no_disposisi='$no_disposisi', 
                                status='PROSES' 
                            WHERE no_pengajuan='$no_pengajuan'";
    
    if($cekNoDisposisi > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertNoDisposisi = $konek->query($sqlDisposisi); 
        
        $updatePengajuan = $konek->query($sqlUpdatePengajuan);

        $pesan 		= $no_disposisi." | Pengajuan Telah Didisposisikan";

        $response 	= array('pesan'=>$pesan, 'no_disposisi'=>$no_disposisi);

        echo json_encode($response);
    }

}