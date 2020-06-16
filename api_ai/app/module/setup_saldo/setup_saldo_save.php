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

		$sqlcount= "SELECT no_trskas FROM trs_kas ORDER BY no_trskas DESC";
        
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

    /*
	* Auto Number Untuk Jurnal Umum 
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
    $no_trskas	    = autonum(4,"99".date('y'));

    $no_jurnal	    = noJurnal(6,"JU".date('y'));

    $kode_kas       = strip_tags($_POST['kode_kas']);
    
    $periode 		= strip_tags($_POST['periode']);
    
    $saldo 	        = strip_tags($_POST['saldo']);
    
    $tgl_kas		= date('Y-m-d');
    
    $kode_akun 		= strip_tags($_POST['kode_akun']);

    $created		= date('Y-m-d h:m:s');

    $createdby      = $_SESSION['kode_petugas'];

    if($kode_akun=='111001'){

        $akun_counter='310001';

    }elseif($kode_akun=='111002'){

        $akun_counter='310002';

    }elseif($kode_akun=='111003'){
        
        $akun_counter='310003';

    }elseif($kode_akun=='111004'){
        
        $akun_counter='310004';

    }elseif($kode_akun=='111005'){
        
        $akun_counter='310005';

    }elseif($kode_akun=='111006'){
        
        $akun_counter='310006';

    }elseif($kode_akun=='111007'){
        
        $akun_counter='310007';

    }elseif($kode_akun=='111008'){
        
        $akun_counter='310008';

    }elseif($kode_akun=='111009'){
        
        $akun_counter='310009';
    }
    
    /* Validasi Kode */
    $sqlCekSetupSaldo = "SELECT * FROM trs_kas WHERE kode_kas='$kode_kas' AND periode='$periode' AND status='Aktif'";
    
    $exe_sqlSetupSaldo = $konek->query($sqlCekSetupSaldo);
    
    $cekSetupSaldo	= mysqli_num_rows($exe_sqlSetupSaldo);
    
    if($cekSetupSaldo > 0 ){
        
            $pesan 		= "Data Sudah Terdaftar";
        
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);
    } else {

        /* SQL Query Simpan */
        $sqlSetupSaldo = "INSERT INTO 
            trs_kas(
                no_trskas,
                kode_kas,
                periode,
                saldo_awal,
                status
            )VALUES(
                '$no_trskas',
                '$kode_kas',
                '$periode',
                '$saldo',
                'Aktif'
        )";

        $sqlSaldoDetail = "INSERT INTO 
        trs_kas_dtl(
            no_trskas,
            tgl_transaksi,
            debit,
            kredit,
            saldo,
            keterangan,
            ref_number,
            status
        )VALUES(
            '$no_trskas',
            '$tgl_kas',
            '$saldo',
            '0',
            '$saldo',
            'Setup Saldo Awal Periode',
            '$no_jurnal',
            'Aktif'
        )";

        $sqlJuHeader = "INSERT INTO 
            trs_juhdr(
                no_jurnal,
                periode,
                tgl_jurnal,
                keterangan,
                status,
                jenis,
                created,
                createdby,
                ref_number
            )VALUES(
                '$no_jurnal',
                '$periode',
                '$tgl_kas',
                'Setup Saldo Awal Periode',
                'Trial',
                'JU',
                '$created',
                '$createdby',
                '$no_trskas'
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
                '$kode_akun',
                '$saldo',
                '0',
                'Setup Saldo Awal Periode',
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
                '$akun_counter',
                '0',
                '$saldo',
                'Setup Saldo Awal Periode',
                'Trial'
            )";
        
        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
         
            $insertSetupSaldo  = $konek->query($sqlSetupSaldo);

            $insertSaldoDetail  = $konek->query($sqlSaldoDetail);
         
            $insertJuHeader    = $konek->query($sqlJuHeader);
         
            $insertJuDebit     = $konek->query($sqlJuDebit);
         
            $insertJuKredit    = $konek->query($sqlJuKredit);       
         
        $konek->commit();  
        
        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);

        echo json_encode($response);
    }

}