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

        $blog_title = isset($_POST["blog-title"]) ? $_POST["blog-title"] : "";
        $blog_body = isset($_POST["blog-body"]) ? $_POST["blog-body"] : "";
        $blog_tags = isset($_POST["blog-tags"]) ? $_POST["blog-tags"] : "";

        $blog_tags = trim($blog_tags, ";");
        $blog_tags = explode(';', $blog_tags);

        // Move image if it exists

        $filePath = "";

        if (isset($_FILES['blog-image']) && $_FILES['blog-image']['error'] == 0) {
    
            // Get image details
            $fileTmpPath = $_FILES['blog-image']['tmp_name'];
            $fileName = $_FILES['blog-image']['name'];
            $fileType = $_FILES['blog-image']['type'];
    
            // Allowed File Types
            $allowedTypes = ['image/jpeg', 'image/png', "image/webp"];
    
            // Validate the image type
            if (in_array($fileType, $allowedTypes)) {

                $uploadDir = '../assets/imgs/blogs/';
                $newFileName = uniqid() . '_' . $fileName;
                $targetPath = $uploadDir . $newFileName;

                $filePath = "/assets/imgs/blogs/" . $newFileName;
    
                // Move the uploaded file to the target directory
                if (!move_uploaded_file($fileTmpPath, $targetPath)) {
                    echo "Error uploading image. Please try again.";
                }
            } else {
                echo "Invalid file type. Only JPEG and PNG are allowed.";
            }
        }

        $stmt = $conn -> prepare("INSERT INTO posts (post_title, post_content, post_author, post_image) VALUES (?, ?, ?, ?)");

        $stmt -> bind_param("ssis", $blog_title, $blog_body, $id, $filePath);

        if ($stmt -> execute()) {
            $_SESSION["post_success_msg"] = "Post created successfully";

            $post_id = $stmt -> insert_id;

            $insert_query = "INSERT INTO post_tags(post_id, tag_id) VALUES ";

            foreach($blog_tags as $tag) {
                $insert_query .= "($post_id, $tag),";
            }

            $insert_query = trim($insert_query, ",");

            $stmt = mysqli_query($conn, $insert_query);

        } else {
            $_SESSION["post_error_msg"] = "Could not process your request";
        }
    }

    header('Location: ../blogs.php');