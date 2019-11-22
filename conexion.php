<?php

function getConexion(){
    try {
        $pdo = new \PDO("mysql:host=localhost;dbname=Escuela", "root", "password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e){
        return null;
    }
}











//  // $stm = $pdo->query("SELECT VERSION()");
//         // $version = $stm->fetch();
//         // echo $version[0] . PHP_EOL;
    
//         $nombre = "Juan de los palotes";
//         $matricula = "DC-2562";
    
//         $stm = $pdo->prepare("INSERT INTO estudiante (nombre, matricula) VALUES (:nombre, :matricula)");
//         $stm->bindParam(":nombre", $nombre);
//         $stm->bindParam(":matricula", $matricula);
    
//         $data = $stm->execute();
//         print_r($data);
    
//         echo "working";


