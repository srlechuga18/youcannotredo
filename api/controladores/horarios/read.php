<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once __DIR__."/../../config/database.php";
    include_once __DIR__."/../../modelo/horario.php";

    $database = new Database();
    $db = $database->getConnection();
    $horario = new Horario($db);

    $stmt = $horario->readProf();
    $num = $stmt->rowCount();
    if ($num>0) {
        $horario_arr=array();
        $horario_arr["records"]=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $prof_item=array(
                "id" => $id,
                "foto" => $foto,
                "nombre" => $nombre,
                "cursos" => array()
            );
            $horario->profesor = $id;
            $stmt2 = $horario->readCursoByProf();
            $num2 = $stmt2->rowCount();
            if ($num2>0) {
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    extract($row2);
                    $curso_item = array(
                        "id" => $id,
                        "nombre" => $nombre,
                        "grupos" => array()
                    );

                    array_push($prof_item["cursos"],$curso_item);
                }
            }

            array_push($horario_arr["records"],$prof_item);
        }
        http_response_code(200);
        echo json_encode($horario_arr);
    }else{
        http_response_code(404);
 
        echo json_encode(
            array("message" => "No users found.")
        );
    }

?>