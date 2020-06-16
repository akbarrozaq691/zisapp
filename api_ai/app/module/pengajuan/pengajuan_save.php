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

		$sqlcount= "SELECT no_pengajuan FROM trs_pengajuan ORDER BY no_pengajuan DESC";
        
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
    $no_pengajuan	    = autonum(5,"PJN-".date('y'));

    $kode_mustahik 	    = strip_tags($_POST['kode_mustahik']);
    
    $kegiatan 	        = strip_tags($_POST['kegiatan']);
    
    $jml_pengajuan 		= strip_tags($_POST['jml_pengajuan']);
    
    $jenis 			    = strip_tags($_POST['jenis']);

    $asnaf 	            = strip_tags($_POST['asnaf']);

    $no_kantor          = $_SESSION['no_kantor'];
     
    /* Validasi Kode */
    $sqlCekNoPengajuan     = "SELECT no_pengajuan FROM trs_pengajuan WHERE no_pengajuan='$no_pengajuan'";
    
    $exe_sqlCekNoPengajuan = $konek->query($sqlCekNoPengajuan);

    $cekNoPengajuan	    = mysqli_num_rows($exe_sqlCekNoPengajuan);

    /* SQL Query Simpan */
    $sqlPengajuan = "INSERT INTO 
        trs_pengajuan(
            no_pengajuan,
            kode_mustahik,
            kegiatan,
            jml_pengajuan,
            jenis,
            no_kantor,
            asnaf,
            status
        )VALUES(
            '$no_pengajuan',
            '$kode_mustahik',
            '$kegiatan',
            '$jml_pengajuan',
            '$jenis',
            '$no_kantor',
            '$asnaf',
            'PENGAJUAN'
        )";
    
    if($cekNoPengajuan > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertNoPengajuan = $konek->query($sqlPengajuan);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}