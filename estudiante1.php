<?php
require('conexion.php');

function guardarEstudiante(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);   

    $errors = [];

    if ( !$data["nombre"]){
        $errors[] = "Campo nombre es requerido";
    }
    if ( !$data["matricula"]){
        $errors[] = "Campo matricula es requerido";
    }
    if ( !$data["edad"]){
        $errors[] = "Campo edad es requerido";
    }
    if ( !$data["carrera"]){
        $errors[] = "Campo carrera es requerido";
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
    $stm = $cn->prepare("INSERT INTO estudiante (nombre, matricula, edad) VALUES (:nombre, :matricula, :edad)");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":matricula", $data["matricula"]);
    $stm->bindParam(":edad", $data["edad"]);
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

function buscarEstudiante(){
    $cn = getConexion();
    $stm = $cn->query("SELECT * FROM estudiante");
    $lista = $stm->fetchAll(PDO::FETCH_ASSOC);
    $data = json_encode($lista);
    echo $data;
};

function editarEstudiante($id){
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
    if(!$data["matricula"]){
        $errors[] = "Campo matricula es requerido...";
    }
    if(!$data["edad"]){
        $errors[] = "Campo edad es requerido...";
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
    $stm = $cn->prepare("UPDATE estudiante SET nombre = :nombre,  matricula = :matricula, edad = :edad WHERE id = :id");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":matricula", $data["matricula"]);
    $stm->bindParam(":edad", $data["edad"]);

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

function eliminarEstudiante($id){
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
    $stm = $cn->prepare("DELETE FROM estudiante WHERE id = :id");
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
                    "message" => "Esta estudiante esta siendo usada..."
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
        guardarEstudiante();
    break;
    case 'GET':
        buscarEstudiante();
    break;
    case 'DELETE':
        $id = $_GET["id"];
        eliminarEstudiante($id);
    break;
    case 'PUT':
        $id = $_GET["id"];
        editarEstudiante($id);
    break;
    default:
        echo "TO BE IMPLEMENTED";
    }