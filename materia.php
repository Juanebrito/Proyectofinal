<?php
require('conexion.php');

function guardarMateria(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);   

    $errors = [];

    if ( !$data["nombre"]){
        $errors[] = "Campo nombre es requerido";
    }
  
    if ( !$data["credito"]){
        $errors[] = "Campo credito es requerido";
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
    $stm = $cn->prepare("INSERT INTO materia1 (nombre,credito) VALUES (:nombre,:credito)");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":credito", $data["credito"]);
    
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

function buscarMateria(){
    $cn = getConexion();
    $stm = $cn->query("SELECT * FROM materia1");
    $lista = $stm->fetchAll(PDO::FETCH_ASSOC);
    $data = json_encode($lista);
    echo $data;
};

function editarMateria($id){
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
    if(!$data["credito"]){
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
    $stm = $cn->prepare("UPDATE  materia1 SET nombre = :nombre, credito = :credito WHERE id = :id");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":credito", $data["credito"]);
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

function eliminarMateria($id){
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
    $stm = $cn->prepare("DELETE FROM materia1 WHERE id = :id");
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
                    "message" => "Esta materia esta siendo usada..."
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
        guardarMateria();
    break;
      case 'GET':
        buscarMateria();
    break;
    case 'DELETE':
        $id = $_GET["id"];
        eliminarMateria($id);
    break;
    case 'PUT':
        $id = $_GET["id"];
        editarMateria($id);
    break;
    default:
        echo "TO BE IMPLEMENTED";
    }
    