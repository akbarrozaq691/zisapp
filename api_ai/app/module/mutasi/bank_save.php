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
    $no_jurnal	        = noJurnal(6,"JU".date('y'));

    $no_rekening_debit  = $_POST['no_rekening_debit'];

    $nama_bank_debit 	= strip_tags($_POST['nama_bank_debit']);

    $saldo_bank_debit 	= strip_tags($_POST['saldo_bank_debit']);

    $ka_bank_debit      = $_POST['ka_bank_debit'];

    $no_rekening_kredit = $_POST['no_rekening_kredit'];
    
    $nama_bank_kredit 	= strip_tags($_POST['nama_bank_kredit']);

    $saldo_bank_kredit 	= strip_tags($_POST['saldo_bank_kredit']);

    $ka_bank_kredit     = $_POST['ka_bank_kredit'];
    
    $jml_mutasi_bank	= strip_tags($_POST['jml_mutasi_bank']);
    
    $keterangan_bank    = $_POST['keterangan_bank'];
    
    $periode_bank 	    = strip_tags($_POST['periode_bank']);
    
    $tgl_transaksi_bank	= strip_tags($_POST['tgl_transaksi_bank']);
    
    $created            = date('Y-m-d');

    $createdby          = $_SESSION['kode_petugas'];

    /* Validasi Kode */
    $sqlCekBank = "SELECT no_jurnal FROM trs_juhdr WHERE no_jurnal='$no_jurnal'";
    
    $exe_sqlBank = $konek->query($sqlCekBank);
    
    $cekBank	= mysqli_num_rows($exe_sqlBank);
    
    if($cekBank > 0 ){
        
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
        
    }else{

        $new_saldo_debit    = intval($saldo_bank_debit) + intval($jml_mutasi_bank);

        $new_saldo_kredit   = intval($saldo_bank_kredit) - intval($jml_mutasi_bank);
        
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
                '$periode_bank',
                '$tgl_transaksi_bank',
                '$keterangan_bank',
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
                '$ka_bank_debit',
                '$jml_mutasi_bank',
                '0',
                '$keterangan_bank',
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
                '$ka_bank_kredit',
                '0',
                '$jml_mutasi_bank',
                '$keterangan_bank',
                'Trial'
            )";
        
        $sqlSaldoDebit = "INSERT INTO 
                        trs_bank(
                            no_rekening,
                            periode,
                            tgl_transaksi,
                            kode_transaksi,
                            keterangan,
                            debit, 
                            kredit,
                            saldo,
                            status,
                            created,
                            createdby,
                            ref_number
                    )VALUES(
                        '$no_rekening_debit',
                        '$periode_bank',
                        '$tgl_transaksi_bank',
                        '01',
                        '$keterangan_bank',
                        '$jml_mutasi_bank',
                        '0',
                        '$new_saldo_debit',
                        'Aktif',
                        '$created',
                        '$createdby',
                        '$no_jurnal'
                    )";

        $sqlSaldoKredit = "INSERT INTO 
                trs_bank(
                    no_rekening,
                    periode,
                    tgl_transaksi,
                    kode_transaksi,
                    keterangan,
                    debit, 
                    kredit,
                    saldo,
                    status,
                    created,
                    createdby,
                    ref_number
                )VALUES(
                    '$no_rekening_kredit',
                    '$periode_bank',
                    '$tgl_transaksi_bank',
                    '02',
                    '$keterangan_bank',
                    '0',
                    '$jml_mutasi_bank',
                    '$new_saldo_kredit',
                    'Aktif',
                    '$created',
                    '$createdby',
                    '$no_jurnal'
                )";
        
        if($no_rekening_debit=='' OR $no_rekening_kredit==''){
            
            $pesan 		= "Data Harus Diisi Tidak Boleh Kosong";
    
            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);
    
            echo json_encode($response);
        
        }elseif($new_saldo_kredit < 0){

            $pesan 		= "Saldo Tidak Boleh Minus";
            
            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);
    
            echo json_encode($response);

        }elseif($no_rekening_debit==$no_rekening_kredit){

            $pesan 		= "Bank Yang Dipilih Tidak Boleh Sama";
            
            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);
    
            echo json_encode($response);

        }else{

            /** Menggunakan Transaction Mysql */
            $konek->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                
                $insertJuHeader    = $konek->query($sqlJuHeader);
                
                $insertJuDebit     = $konek->query($sqlJuDebit);
                
                $insertJuKredit    = $konek->query($sqlJuKredit);   
                
                $insertBankSaldoDebit  = $konek->query($sqlSaldoDebit);

                $insertBankSaldoKredit = $konek->query($sqlSaldoKredit);
                
            $konek->commit();  
            
            $pesan 		= "Data Berhasil Disimpan";

            $response 	= array('pesan'=>$pesan, 'no_rekening_debit'=>$no_rekening_debit);

            echo json_encode($response);

        }

    }

}