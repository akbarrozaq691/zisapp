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

		$sqlcount= "SELECT no_kantor FROM tm_kantor_layanan ORDER BY no_kantor DESC";
        
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
    $no_kantor	    = autonum(4,"KL1307");

    $nama_kantor 	= strip_tags($_POST['nama_kantor']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $pimpinan 		= strip_tags($_POST['pimpinan']);
     
    /* Validasi Kode */
    $sqlCekNoKantor     = "SELECT no_kantor FROM tm_kantor_layanan WHERE no_kantor='$no_kantor'";
    
    $exe_sqlCekNoKantor = $konek->query($sqlCekNoKantor);

    $cekNoKantor	    = mysqli_num_rows($exe_sqlCekNoKantor);

    /* SQL Query Simpan */
    $sqlKantorLayanan = "INSERT INTO 
        tm_kantor_layanan(
            no_kantor,
            nama_kantor,
            alamat,
            phone,
            pimpinan,
            status
        )VALUES(
            '$no_kantor',
            '$nama_kantor',
            '$alamat',
            '$no_hp',
            '$pimpinan',
            'Aktif'
        )";
    
    if($cekNoKantor > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertNoKantor = $konek->query($sqlKantorLayanan);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}