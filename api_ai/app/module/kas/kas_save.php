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

    $no_trskas      = $_POST['no_trskas'];

    $saldo 	        = strip_tags($_POST['saldo']);
    
    $kode_akun 		= strip_tags($_POST['kode_akun']);
    
    $periode 	    = strip_tags($_POST['periode']);
    
    $tgl_transaksi	= strip_tags($_POST['tgl_transaksi']);
    
    $akun_counter 	= strip_tags($_POST['akun_counter']);
    
    $jml_transaksi	= strip_tags($_POST['jml_transaksi']);

    $jenis          = $_POST['jenis'];

    $keterangan     = $_POST['keterangan'];

    $created        = date('Y-m-d');

    $createdby      = $_SESSION['kode_petugas'];

    /* Validasi Kode */
    $sqlCekKas = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
    $exe_sqlKas = $konek->query($sqlCekKas);
    
    $cekKas	= mysqli_num_rows($exe_sqlKas);
    
    if($cekKas > 0 ){
        
            $pesan 		= "Data Sudah Terdaftar";
        
            $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
        
            echo json_encode($response);

    }elseif($jenis =='Pemasukan'){

        $new_saldo = intval($saldo) + intval($jml_transaksi);

        /* SQL Query Simpan */
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
            '$tgl_transaksi',
            '$jml_transaksi',
            '0',
            '$new_saldo',
            '$keterangan',
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
                '$kode_akun',
                '$jml_transaksi',
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
                '$akun_counter',
                '0',
                '$jml_transaksi',
                '$keterangan',
                'Trial'
            )";
        
        // $sqlUpdateSaldo = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo' WHERE no_trskas='$no_trskas'";

        if($no_trskas=='' OR $akun_counter=='' OR $tgl_transaksi=='' OR $jml_transaksi==''){

            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);
    
            echo json_encode($response);
        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

                $insertTransaksi   = $konek->query($sqlSaldoDetail);
                
                $insertJuHeader    = $konek->query($sqlJuHeader);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);

            echo json_encode($response);

        }
        
    }else{

        $new_saldo = intval($saldo) - intval($jml_transaksi);
        
        /* SQL Query Simpan */
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
            '$tgl_transaksi',
            '0',
            '$jml_transaksi',
            '$new_saldo',
            '$keterangan',
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
                '$akun_counter',
                '$jml_transaksi',
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
                '$kode_akun',
                '0',
                '$jml_transaksi',
                '$keterangan',
                'Trial'
            )";
        
        $sqlUpdateSaldo = "UPDATE trs_kas SET saldo_berjalan = '$new_saldo' WHERE no_trskas='$no_trskas'";
        
        if($no_trskas=='' OR $akun_counter=='' OR $tgl_transaksi=='' OR $jml_transaksi==''){
            
            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);
    
            echo json_encode($response);
        
        }elseif($new_saldo < 0){

            $pesan 		= "Saldo Tidak Boleh Minus";
            
            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);
    
            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

                $insertTransaksi   = $konek->query($sqlSaldoDetail);
                
                $insertJuHeader    = $konek->query($sqlJuHeader);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_trskas'=>$no_trskas);

            echo json_encode($response);

        }

    }

}