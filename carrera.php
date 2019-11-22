<?php
require('conexion.php');

function guardarCarrera(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);   

    $errors = [];

    if ( !$data["nombre"]){
        $errors[] = "Campo nombre es requerido";
    }
  
    if (count($errors) > 0){
        header("HTTP/1.1 400 Bad Request");
        $response = [
            "error" => true,
            "message" => "Campos requerido",
            "errors" => $errors
        ];
        echo json_encode($response);
        return;
    }   

    $cn = getConexion();
    $stm = $cn->prepare("INSERT INTO carrera (nombre) VALUES (:nombre)");
    $stm->bindParam(":nombre", $data["nombre"]);
    
    try{
        $data = $stm->execute();
        $response = ["error" => false];
        echo json_encode($response);
    }catch(Exception $e){
        $response = [
            "error" => true,
            "message" => $e->getMessage()
         ];
        echo json_encode($response);
    }
};

function buscarCarrera(){
    $cn = getConexion();
    $stm = $cn->query("SELECT * FROM carrera");
    $lista = $stm->fetchAll(PDO::FETCH_ASSOC);
    $data = json_encode($lista);
    echo $data;
};

function editarCarrera($id){
    if ($id == null){
        header("HTTP/1.1 400 Bad Request");
        $response = [
            "error" => true,
            "message" => "Campo id es requerido..."
        ];
        echo json_encode($response);
        return;
    }
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);

    $errors = [];

    if(!$data["nombre"]){
        $errors[] = "Campo nombre es requerido...";
    }
 
    if (count($errors) > 0){
        header("HTTP/1.1 400 Bad Request");
        $response = [
            "error" => true,
            "message" => "Campos requeridos",
            "errors" => $errors
        ];
        echo json_encode($response);
        return;
    }
    $cn = getConexion();
    $stm = $cn->prepare("UPDATE  carrera SET nombre = :nombre WHERE id = :id");
    $stm->bindParam(":nombre", $data["nombre"]);
    
    try{
        $data = $stm->execute();
        $response = ["error" => false];
        echo json_encode($response);
    }catch(Exception $e){
        $response = [
            "error" => true,
            "message" => $e->getMessage()
         ];
        echo json_encode($response);
    }
};

function eliminarCarrera($id){
    if ( $id == null) {
        header("HTTP/1.1 400 Badd Request");
        $response = [
            "error" => true,
            "message" => "Campo id es requerido"
        ];

        echo json_encode($response);
        return;
    }

    $cn = getConexion();
    $stm = $cn->prepare("DELETE FROM carrera WHERE id = :id");
    $stm->bindParam(":id", $id);

    try{
        $data = $stm->execute();
        $response = [ "error" => false ];
        echo json_encode($response);
    } catch(Exception $e){
        switch($e->getCode()){
            case 23000:
                $response = [
                    "error" => true,
                    "message" => "Esta carrera esta siendo usada..."
                ];

                echo json_encode($response);
            break;
            default:
            $response = [
                "error" => true,
                "message" => $e->getMessage
            ];

            echo json_encode($response);
        }
    }
}



$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        guardarCarrera();
    break;
    case 'GET':
        buscarCarrera();
    break;
    case 'DELETE':
        $id = $_GET["id"];
        eliminarCarrera($id);
    break;
    case 'PUT':
        $id = $_GET["id"];
        editarCarrera($id);
    break;
    default:
        echo "TO BE IMPLEMENTED";
    }