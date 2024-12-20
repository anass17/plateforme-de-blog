<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        require_once "../connect/db-connect.php";
        require_once "../auth/JWT.php";

        print_r($_POST);

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

        $comment_email = isset($_POST["comment-email"]) ? $_POST["comment-email"] : "";
        $comment_body = isset($_POST["comment-body"]) ? $_POST["comment-body"] : "";
        $post_id = isset($_POST["post-id"]) ? $_POST["post-id"] : "";

        if ($role == null) {
            $stmt = $conn -> prepare("INSERT INTO users (first_name, last_name, email, password, user_role) VALUES ('', '', ?, '', 3)");

            $stmt -> bind_param("s", $comment_email);

            if ($stmt -> execute()) {
                echo "Added successfully";

                $user_id = $stmt -> insert_id;

                $stmt = $conn -> prepare("INSERT INTO `comments` (comment_content, comment_author, comment_post) VALUES (?, ?, ?)");

                $stmt -> bind_param("sii", $comment_body, $user_id, $post_id);

                $stmt -> execute();
            } else {
                echo 'Error! Could not process your request';
            }
            
        }

        

    }

    header('Location: ../blogs.php');