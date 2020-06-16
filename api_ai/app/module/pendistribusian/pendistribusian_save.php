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
	function noJurnal($lebar=0, $awalan=''){
        include "../../../bin/koneksi.php";

		$sqlcount= "SELECT no_jurnal FROM trs_juhdr ORDER BY no_jurnal DESC";
        
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
    $no_jurnal	    = noJurnal(6,"JU".date('y'));

    $no_pengajuan       = $_POST['no_pengajuan'];

    $no_trskas_kredit   = strip_tags($_POST['no_trskas_kredit']);
    
    $saldo_kredit 		= strip_tags($_POST['saldo_kredit']);

    $kode_akun_kredit   = $_POST['kode_akun_kredit'];

    $jml_realisasi      = strip_tags($_POST['jml_realisasi']);
    
    $tgl_pencairan 		= strip_tags($_POST['tgl_pencairan']);
    
    $periode 	        = strip_tags($_POST['periode']);
    
    $akun_counter 	    = strip_tags($_POST['akun_counter']);

    $created            = date('Y-m-d');

    $createdby          = $_SESSION['kode_petugas'];

    /* Validasi Kode */
    $sqlCekPendistribusian = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
    $exe_sqlPendistribusian = $konek->query($sqlCekPendistribusian);
    
    $cekPendistribusian	= mysqli_num_rows($exe_sqlPendistribusian);
    
    if($cekPendistribusian > 0 ){
        
            $pesan 		= "Data Sudah Terdaftar";
        
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);

    }else{

        $new_saldo = intval($saldo_kredit) - intval($jml_realisasi);
        
        /* SQL Query Simpan */
        $sqlJuHeader = "INSERT INTO 
            trs_juhdr(
                no_jurnal,
                periode,
                tgl_jurnal,
                keterangan,
                status,
                jenis,
                created,
                createdby
            )VALUES(
                '$no_jurnal',
                '$periode',
                '$tgl_pencairan',
                'Realisasi Pengajuan ".$no_pengajuan."',
                'Trial',
                'JU',
                '$created',
                '$createdby'
            )";
        
        $sqlJuDebit = "INSERT INTO 
            trs_judtl(
                no_jurnal,
                kode_akun,
                debit,
                kredit,
                keterangan,
                status
            )VALUES(
                '$no_jurnal',
                '$akun_counter',
                '$jml_realisasi',
                '0',
                'Realisasi Pengajuan ".$no_pengajuan."',
                'Trial'
            )";

        $sqlJuKredit = "INSERT INTO 
            trs_judtl(
                no_jurnal,
                kode_akun,
                debit,
                kredit,
                keterangan,
                status
            )VALUES(
                '$no_jurnal',
                '$kode_akun_kredit',
                '0',
                '$jml_realisasi',
                'Realisasi Pengajuan ".$no_pengajuan."',
                'Trial'
            )";
        
        $sqlUpdateSaldo = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo' WHERE no_trskas='$no_trskas_kredit'";
        
        $sqlUpdatePengajuan = "UPDATE trs_pengajuan SET status='Complete' WHERE no_pengajuan='$no_pengajuan'";

        if($no_trskas_kredit=='' OR $akun_counter==''){
            
            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_kredit);
    
            echo json_encode($response);
        
        }elseif($new_saldo < 0){

            $pesan 		= "Saldo Tidak Boleh Minus";
            
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_kredit);
    
            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                
                $insertJuHeader    = $konek->query($sqlJuHeader);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
                $updateSaldo       = $konek->query($sqlUpdateSaldo);

                $updatePengajuan   = $konek->query($sqlUpdatePengajuan);
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_kredit);

            echo json_encode($response);

        }

    }

}