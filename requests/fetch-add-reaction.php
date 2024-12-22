<?php

    if ($_SERVER["REQUEST_METHOD"] = "POST") {

        require_once "../connect/db-connect.php";
        require_once "../auth/JWT.php";


        $rawData = file_get_contents("php://input");

        $data = json_decode($rawData, true);

        if ($data === null) {
            echo json_encode(['result' => '0']);
            exit;
        }

        $id = null;

        // Validate Token

        if (isset($_COOKIE['token'])) {

            $validation_result = validateJWT($_COOKIE['token']);

            if ($validation_result) {
                $id = $validation_result -> id;
            } else {
                setcookie('token', '', 0);
                echo json_encode(["result" => "0"]);
                exit;
            }
        }

        $post_id = $data['id'];

        $stmt = $conn -> prepare("INSERT INTO post_reactions (react_user, react_post, type) VALUES (?, ?, 1)");
        $stmt -> bind_param("ii", $id, $post_id);

        try {
            $stmt -> execute();
            echo json_encode(["result" => "1"]);
        } catch (Exception) {
            echo json_encode(["result" => "0"]);
        }

    } else {
        echo json_encode(["result" => "No"]);
    }