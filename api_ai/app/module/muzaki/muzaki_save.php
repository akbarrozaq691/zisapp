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

		$sqlcount= "SELECT npwz FROM tm_donatur ORDER BY npwz DESC";
        
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
    $npwz	        = autonum(4,date('y')."1111");

    $nama_donatur 	= strip_tags($_POST['nama_donatur']);

    $nik 	        = strip_tags($_POST['nik']);
    
    $alamat 		= strip_tags($_POST['alamat']);
    
    $no_hp 			= strip_tags($_POST['no_hp']);
    
    $kategori 		= strip_tags($_POST['kategori']);
    
    $status 		= strip_tags($_POST['status']);
    
    $kode_petugas	= strip_tags($_POST['kode_petugas']);
      
    $no_kantor 		= strip_tags($_POST['no_kantor']);
    
    /* Validasi Kode */
    $sqlCekNpwz     = "SELECT npwz FROM tm_donatur WHERE npwz='$npwz'";
    
    $exe_sqlNpwz    = $konek->query($sqlCekNpwz);
   
    $cekNpwz	    = mysqli_num_rows($exe_sqlNpwz);

    /* SQL Query Simpan */
    $sqlMuzaki = "INSERT INTO 
        tm_donatur(
            npwz,
            nik,
            nama_donatur,
            alamat,
            no_hp,
            kategori,
            status,
            kode_petugas,
            no_kantor
        )VALUES(
            '$npwz',
            '$nik',
            '$nama_donatur',
            '$alamat',
            '$no_hp',
            '$kategori',
            '$status',
            '$kode_petugas',
            '$no_kantor'
        )";
    
    if($cekNpwz > 0){
    
        $pesan 		= "Data Sudah Terdaftar";
    
        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);
    
        echo json_encode($response);
    } else {

        $insertMuzaki = $konek->query($sqlMuzaki);           

        $pesan 		= "Data Berhasil Disimpan";

        $response 	= array('pesan'=>$pesan, 'data'=>$_POST);

        echo json_encode($response);
    }

}
