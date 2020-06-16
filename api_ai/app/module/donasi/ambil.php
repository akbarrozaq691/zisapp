<?php
session_start();
if(!$_SESSION){
    $pesan = "Your Access Not Authorized";
    header("Content-type:application/json");
    echo json_encode($pesan);
} else {
    include "../../../bin/koneksi.php";
    $sql	         = "SELECT * FROM view_donasi ORDER BY 'no_donasi' DESC";
    $hasil	         = $konek->query($sql);
    $data['data']    = [];          

    while($row 	= $hasil->fetch_assoc()){
        $r      = $row;
        array_push($data["data"], $r);
    }

    header("Content-type:application/json");
    echo json_encode($data, true);
}
?>