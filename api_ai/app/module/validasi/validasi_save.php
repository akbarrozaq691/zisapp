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

    $kode_kategori  = $_POST['kode_kategori'];

    $petugas        = $_POST['petugas'];

    $no_trskas      = $_POST['no_trskas'];

    $no_kas_amil 	= strip_tags($_POST['no_kas_amil']);

    $saldo_kas 	    = strip_tags($_POST['saldo_kas']);

    $saldo_amil 	= strip_tags($_POST['saldo_amil']);
    
    $kode_akun 		= strip_tags($_POST['kode_akun']);

    $akun_counter 	= strip_tags($_POST['akun_counter']);

    $penerimaan	    = strip_tags($_POST['penerimaan']);

    $alokasi_amil	= strip_tags($_POST['alokasi_amil']);
    
    $periode 	    = strip_tags($_POST['periode']);
    
    $tgl_transaksi	= strip_tags($_POST['tgl_transaksi']);

    $tgl_validasi	= strip_tags($_POST['tgl_validasi']);

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    $new_saldo_kas  = intval($saldo_kas) + intval($penerimaan);

    $new_saldo_amil = intval($saldo_amil) + intval($alokasi_amil);

    $kas_after      = intval($new_saldo_kas) - intval($alokasi_amil);  
    
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
            '$tgl_transaksi',
            'Validasi Penerimaan Donasi Petugas ".$petugas." Tgl: ".$tgl_transaksi."',
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
            '$kode_akun',
            '$penerimaan',
            '0',
            'Validasi Penerimaan Donasi Petugas ".$petugas." Tgl: ".$tgl_transaksi."',
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
            '$penerimaan',
            'Validasi Penerimaan Donasi Petugas ".$petugas." Tgl: ".$tgl_transaksi."',
            'Trial'
        )";

    // $sqlJuHeader1 = "INSERT INTO 
    //     trs_juhdr(
    //         no_jurnal,
    //         periode,
    //         tgl_jurnal,
    //         keterangan,
    //         status,
    //         jenis,
    //         created,
    //         createdby
    //     )VALUES(
    //         '$no_jurnal',
    //         '$periode',
    //         '$tgl_transaksi',
    //         'Alokasi Dana Amil',
    //         'Trial',
    //         'JU',
    //         '$created',
    //         '$createdby'
    //     )";

    $sqlJuDebit1 = "INSERT INTO 
        trs_judtl(
            no_jurnal,
            kode_akun,
            debit,
            kredit,
            keterangan,
            status
        )VALUES(
            '$no_jurnal',
            '514001',
            '$alokasi_amil',
            '0',
            'Alokasi Dana Amil',
            'Trial'
        )";

    $sqlJuKredit1 = "INSERT INTO 
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
            '0',
            '$alokasi_amil',
            'Alokasi Dana Amil',
            'Trial'
        )";

    // $sqlJuHeader2 = "INSERT INTO 
    //     trs_juhdr(
    //         no_jurnal,
    //         periode,
    //         tgl_jurnal,
    //         keterangan,
    //         status,
    //         jenis,
    //         created,
    //         createdby
    //     )VALUES(
    //         '$no_jurnal',
    //         '$periode',
    //         '$tgl_transaksi',
    //         'Penerimaan Kas Amil',
    //         'Trial',
    //         'JU',
    //         '$created',
    //         '$createdby'
    //     )";

    $sqlJuDebit2 = "INSERT INTO 
        trs_judtl(
            no_jurnal,
            kode_akun,
            debit,
            kredit,
            keterangan,
            status
        )VALUES(
            '$no_jurnal',
            '111004',
            '$alokasi_amil',
            '0',
            'Penerimaan Kas Amil',
            'Trial'
        )";

    $sqlJuKredit2 = "INSERT INTO 
        trs_judtl(
            no_jurnal,
            kode_akun,
            debit,
            kredit,
            keterangan,
            status
        )VALUES(
            '$no_jurnal',
            '414001',
            '0',
            '$alokasi_amil',
            'Penerimaan Kas Amil',
            'Trial'
        )";
    
    $sqlUpdateSaldo     = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo_kas' WHERE no_trskas='$no_trskas'";

    $sqlUpdateSaldoAmil = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo_amil' WHERE no_trskas='$no_kas_amil'";
    
    $sqlSaldoAfter      = "UPDATE trs_kas SET saldo_berjalan = '$kas_after' WHERE no_trskas='$no_trskas'";
    
    if($no_trskas=='' OR $akun_counter=='' OR $penerimaan==''){
        
        $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";

        $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);

        echo json_encode($response);
    
    }elseif($new_saldo_kas < 0){

        $pesan 		= "Saldo Tidak Boleh Minus";
        
        $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);

        echo json_encode($response);

    }else{

        /** Menggunakan Transaction Mysql */
        $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            
            $insertJuHeader    = $konek->query($sqlJuHeader);
            
            $insertJuDebit     = $konek->query($sqlJuDebit);
            
            $insertJuKredit    = $konek->query($sqlJuKredit);
            
            $insertJuDebit1    = $konek->query($sqlJuDebit1);
            
            $insertJuKredit1   = $konek->query($sqlJuKredit1);
            
            $insertJuDebit2    = $konek->query($sqlJuDebit2);
            
            $insertJuKredit2   = $konek->query($sqlJuKredit2); 
            
            $updateSaldo       = $konek->query($sqlUpdateSaldo);

            $updateSaldoAmil   = $konek->query($sqlUpdateSaldoAmil);

            $updateSaldoAfter  = $konek->query($sqlSaldoAfter);
            
        $konek->commit();  
        
        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);

        echo json_encode($response);

    }

}