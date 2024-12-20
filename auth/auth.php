<?php
session_start();

require_once "../connect/db-connect.php";
require_once "JWT.php";

function createJWTCookie($id, $email, $role, $first_name, $last_name) {
    $jwt = createJWT($id, $email, $role, $first_name, $last_name);

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

function setLoginError($error_msg) {
    $_SESSION["error_msg"] = $error_msg;
    header("Location: login.php");
}

function setSignupError($error_msg) {
    $_SESSION["error_msg"] = $error_msg;
    header("Location: signup.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form_type = isset($_POST["form-type"]) ? $_POST["form-type"] : "";

    if ($form_type == "login") {

        // Get values

        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        // Check if user exists

        $stmt = $conn -> prepare("select first_name, last_name, email, password, user_id, role_name from users join roles on users.user_id = roles.role_id where email = ?");

        $stmt -> bind_param("s", $email);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $row = $result -> fetch_assoc();

            if ($row) {

                if (password_verify($password, $row["password"])) {
                    createJWTCookie($row['user_id'], $row["email"], $row["role_name"], $row["first_name"], $row["last_name"]);
                    header('Location: ../blogs.php');
                } else {
                    setLoginError("login credentials you provided were incorrect");
                }
            } else {
                setLoginError("login credentials you provided were incorrect");
            }
        } else {
            setLoginError("Could not process your request");
        }
    } else if ($form_type == "signup") {

        // Get values

        $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
        $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        $confirm_password = isset($_POST["confirm-password"]) ? $_POST["confirm-password"] : "";

        // Check if user exists

        $stmt = $conn -> prepare("select email from users where email = ?");

        $stmt -> bind_param("s", $email);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $row = $result -> fetch_assoc();

            if ($row) {
                setSignupError("This email already exists");
            } else {
                $first_name = ucfirst(strtolower($first_name));
                $last_name = ucfirst(strtolower($last_name));
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn -> prepare("INSERT INTO users (email, password, first_name, last_name, user_role) VALUES (?, ?, ?, ?, 2);");

                $stmt -> bind_param("ssss", $email, $password_hash, $first_name, $last_name);

                if($stmt -> execute()) {
                    $lastInsertedId = $conn -> insert_id;
                    createJWTCookie($lastInsertedId, $email, "user", $first_name, $last_name);
                    header('Location: ../blogs.php');
                } else {
                    setSignupError("Could not process your request");
                }
            }
        }
    } else {
        setLoginError("Could not process your request");
    }


}



?>