<?php
session_start();

require_once "../connect/db-connect.php";
require_once "JWT.php";

function createJWTCookie($id, $email, $role, $first_name, $last_name, $image) {
    $jwt = createJWT($id, $email, $role, $first_name, $last_name, $image);

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
    exit();
}

function setSignupError($error_msg) {
    $_SESSION["error_msg"] = $error_msg;
    header("Location: signup.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form_type = isset($_POST["form-type"]) ? $_POST["form-type"] : "";

    if ($form_type == "login") {

        // Get values

        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        // Validated entered data

        if (preg_match("/^[a-zA-Z0-9.-_]{3,}@[a-zA-Z.]{2,}\.[a-z]{2,}$/", $email) == 0) {
            setLoginError("Login credentials you provided were incorrect");
        }

        if (preg_match("/^.{8,}$/", $password) == 0) {
            setLoginError("Login credentials you provided were incorrect");
        }

        // Check if user exists

        $stmt = $conn -> prepare("select first_name, last_name, email, password, user_id, role_name, user_image from users join roles on users.user_role = roles.role_id where email = ?");

        $stmt -> bind_param("s", $email);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $row = $result -> fetch_assoc();

            if ($row) {

                if (password_verify($password, $row["password"])) {
                    createJWTCookie($row['user_id'], $row["email"], $row["role_name"], $row["first_name"], $row["last_name"], $row["user_image"]);
                    if ($row["role_name"] == "admin") {
                        header('Location: ../pages/dashboard.php');
                    } else {
                        header('Location: ../blogs.php');
                    }
                    exit;
                } else {
                    setLoginError("Login credentials you provided were incorrect");
                }
            } else {
                setLoginError("Login credentials you provided were incorrect");
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

        // Data to be included in signup form, in case of errors

        $_SESSION["signup-f-name"] = $first_name;
        $_SESSION["signup-l-name"] = $last_name;
        $_SESSION["signup-email"] = $email;

        // Validated entered data

        if (preg_match("/^[a-z A-Z]{2,}$/", $first_name) == 0) {
            setSignupError("Please enter a valid first name");
        }

        if (preg_match("/^[a-z A-Z]{2,}$/", $last_name) == 0) {
            setSignupError("Please enter a valid last name");
        }

        if (preg_match("/^[a-zA-Z0-9.-_]{3,}@[a-zA-Z.]{2,}\.[a-z]{2,}$/", $email) == 0) {
            setSignupError("Please enter a valid email address");
        }

        if (preg_match("/^.{8,}$/", $password) == 0) {
            setSignupError("Your password must contain at least 8 characters");
        }

        if ($password != $confirm_password) {
            setSignupError("The two passwords does not match");
        }

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
                    createJWTCookie($lastInsertedId, $email, "user", $first_name, $last_name, '');
                    header('Location: ../pages/profile.php');
                } else {
                    setSignupError("Could not process your request");
                }
            }
        }
    } else {
        setLoginError("Could not process your request");
    }

    // Clear the session if data is valid

    session_destroy();
}



?>