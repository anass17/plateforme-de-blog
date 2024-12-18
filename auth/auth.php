<?php

require_once "../connect/db-connect.php";
require_once "JWT.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get values

    $form_type = isset($_POST["form-type"]) ? $_POST["form-type"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // validate with PHP



    // Check if the user exists in the database

    if ($form_type == "login") {
        $stmt = $conn -> prepare("select email, password, user_id, role_name from users join roles on users.user_id = roles.role_id where email = ?");

        $stmt -> bind_param("s", $email);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $row = $result -> fetch_assoc();

            if ($row) {
                print_r($row);

                if (password_verify($password, $row["password"])) {
                    
                    $jwt = createJWT($row['user_id'], $row["email"], $row["role_name"]);

                    $options = [
                        'expires' => time() + (60 * 60 * 24),
                        'path' => '/',
                        'domain' => 'localhost',
                        'secure' => false,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ];

                    setcookie('token', $jwt, $options);
                }
            } else {
                echo 'Empty Result';
            }
        } else {
            echo 'Error';
        }
    }


}



?>