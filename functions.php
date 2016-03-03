<?php
include 'instancias.php';
class Functions{
    public function teste(){
        echo 'chegou em teste';
        // die();
    }
    public function lista(){
        // include 'instancias.php';
        $sqlList = "SELECT * from cfop";
        $regimes = $db->fetchAll($sqlList);

        $json = json_encode($regimes);

        echo $json;
        echo 'chegou em lista';
        // die();
    }
    // public function list(){
    //     // print_r('ta aki');
    //     // include 'instancias.php';
    //     $sqlList = "SELECT * from cfop";
    //     $regimes = $db->fetchAll($sqlList);
    //
    //     $json = json_encode($regimes);
    //
    //     echo $json;
    //
    // }
    // public function updateCell(){
    //     echo 'chegou em updateCell';
    //     die();
    //
    // }
}
?>
