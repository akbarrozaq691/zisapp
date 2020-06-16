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

    $no_trskas_debit    = $_POST['no_trskas_debit'];

    $saldo_debit 		= strip_tags($_POST['saldo_debit']);

    $kode_akun_debit 	= strip_tags($_POST['kode_akun_debit']);

    $no_trskas_kredit   = $_POST['no_trskas_kredit'];
    
    $saldo_kredit		= strip_tags($_POST['saldo_kredit']);

    $kode_akun_kredit 	= strip_tags($_POST['kode_akun_kredit']);

    $jml_mutasi	        = strip_tags($_POST['jml_mutasi']);
    
    $keterangan         = $_POST['keterangan'];
    
    $periode 	        = strip_tags($_POST['periode']);
    
    $tgl_transaksi	    = strip_tags($_POST['tgl_transaksi']);
    
    $created            = date('Y-m-d');

    $createdby          = $_SESSION['kode_petugas'];

    /* Validasi Kode */
    $sqlCekKas = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
    $exe_sqlKas = $konek->query($sqlCekKas);
    
    $cekKas	= mysqli_num_rows($exe_sqlKas);
    
    if($cekKas > 0 ){
        
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        
    }else{

        $new_saldo_debit    = intval($saldo_debit) + intval($jml_mutasi);

        $new_saldo_kredit   = intval($saldo_kredit) - intval($jml_mutasi);
        
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
                '$keterangan',
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
                '$kode_akun_debit',
                '$jml_mutasi',
                '0',
                '$keterangan',
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
                '$jml_mutasi',
                '$keterangan',
                'Trial'
            )";
        
        $sqlUpdateSaldoDebit = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo_debit' WHERE no_trskas='$no_trskas_debit'";

        $sqlUpdateSaldoKredit = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo_kredit' WHERE no_trskas='$no_trskas_kredit'";
        
        if($no_trskas_debit=='' OR $no_trskas_debit==''){
            
            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_debit);
    
            echo json_encode($response);
        
        }elseif($new_saldo_kredit < 0){

            $pesan 		= "Saldo Tidak Boleh Minus";
            
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_debit);
    
            echo json_encode($response);

        }elseif($kode_akun_debit==$kode_akun_kredit){

            $pesan 		= "Kas Yang Dipilih Tidak Boleh Sama";
            
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_debit);
    
            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                
                $insertJuHeader    = $konek->query($sqlJuHeader);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
                $updateSaldoDebit  = $konek->query($sqlUpdateSaldoDebit);

                $updateSaldoKredit = $konek->query($sqlUpdateSaldoKredit);
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas_debit);

            echo json_encode($response);

        }

    }

}