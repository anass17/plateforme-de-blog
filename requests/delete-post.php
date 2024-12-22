<?php
    session_start();

    function setError($error_msg, $role) {
        $_SESSION["post_error_msg"] = $error_msg;
        if ($role == "admin" || $role == "super_admin") {
            header('Location: ../pages/dashboard.php');
        } else {
            header('Location: ../blogs.php');
        }
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        require_once "../connect/db-connect.php";
        require_once "../auth/JWT.php";

        $id = null;
        $email = null;
        $role = null;

        // Validate Token

        if (isset($_COOKIE['token'])) {

            $validation_result = validateJWT($_COOKIE['token']);

            if ($validation_result) {
                $id = $validation_result -> id;
                $email = $validation_result -> email;
                $role = $validation_result -> role;
            } else {
                setcookie('token', '', 0);
            }
        }

        // Get Details

        $post_id = isset($_POST["post_id"]) ? $_POST["post_id"] : "";

        if (preg_match('/^[0-9]+$/', $post_id) == 0) {
            setError('Could not process your request');
        }

        if ($role == "admin" || $role == "super_admin") {
            $stmt = $conn -> prepare("DELETE FROM posts WHERE post_id = ?");

            $stmt -> bind_param("i", $post_id);

            if ($stmt -> execute()) {
                $_SESSION["post_success_msg"] = "Post was deleted successfully";
                if ($role == "admin" || $role == "super_admin") {
                    header('Location: ../pages/dashboard.php');
                } else {
                    header('Location: ../blogs.php');
                }
                exit;
            } else {
                setError('Could not process your request');
            }
        } else if ($role == "user") {
            $stmt = $conn -> prepare("DELETE FROM posts WHERE post_id = ? AND post_author = ?");

            $stmt -> bind_param("ii", $post_id, $id);

            if ($stmt -> execute()) {
                if ($stmt -> affected_rows > 0) {
                    $_SESSION["post_success_msg"] = "Post was deleted successfully";
                    if ($role == "admin" || $role == "super_admin") {
                        header('Location: ../pages/dashboard.php');
                    } else {
                        header('Location: ../blogs.php');
                    }
                    exit;
                } else {
                    setError('Could not process your request');
                }
            } else {
                setError('Could not process your request');
            }
        } else {
            setError('Could not process your request');
        }
    } else {
        header('Location: ../blogs.php');
    }

?>