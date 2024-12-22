<?php
    session_start();

    function setError($error_msg, $id = '') {
        $_SESSION["post_error_msg"] = $error_msg;
        
        $id_param = empty($id) ? '' : '?id=' . $id;

        header('Location: ../view.php' . $id_param);
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

        $post_id = isset($_POST["post-id"]) ? htmlspecialchars($_POST["post-id"]) : "";
        $blog_title = isset($_POST["blog-title"]) ? htmlspecialchars($_POST["blog-title"]) : "";
        $blog_body = isset($_POST["blog-body"]) ? htmlspecialchars($_POST["blog-body"]) : "";
        $blog_tags = isset($_POST["blog-tags"]) ? $_POST["blog-tags"] : "";

        // Data Validation
    
        if (preg_match('/^[1-9][0-9]*$/', $post_id) == 0) {
            setError("Could not process your request");
        }

        if (strlen($blog_title) < 3) {
            setError("Post Title was too short", $post_id);
        }

        if (strlen($blog_body) < 20) {
            setError("Post Body was too short", $post_id);
        }

        if (preg_match('/^([1-9][0-9]*;)+$/', $blog_tags) == 0) {
            setError("Please add at least one tag to your post", $post_id);
        }

        // Tags splitting

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
                    setError("Error uploading image. Please try again", $post_id);
                }
            } else {
                setError("Invalid file type. Only JPEG, PNG and WEBP are allowed.", $post_id);
            }
        }

        if ($filePath != "") {
            $stmt = $conn -> prepare("UPDATE posts SET post_title = ?, post_content = ?, post_image = ?, is_modified = 1 WHERE post_id = ?");
            $stmt -> bind_param("sssi", $blog_title, $blog_body, $filePath, $post_id);
        } else {
            $stmt = $conn -> prepare("UPDATE posts SET post_title = ?, post_content = ?, is_modified = 1 WHERE post_id = ?");
            $stmt -> bind_param("ssi", $blog_title, $blog_body, $post_id);
        }

        if ($stmt -> execute()) {
            $_SESSION["post_success_msg"] = "Post updated successfully";

            $delete_query = "DELETE FROM post_tags WHERE post_id = ?";

            $stmt = $conn -> prepare($delete_query);
            $stmt -> bind_param('i', $post_id);
            $stmt -> execute();

            $insert_query = "INSERT INTO post_tags(post_id, tag_id) VALUES ";

            foreach($blog_tags as $tag) {
                $insert_query .= "($post_id, $tag),";
            }

            $insert_query = trim($insert_query, ",");

            $stmt = mysqli_query($conn, $insert_query);

        } else {
            setError("Could not process your request", $post_id);
        }
    }

    $id_param = isset($post_id) ? '?id=' . $post_id : '';

    header('Location: ../view.php' . $id_param);