<?php
    session_start();

    function setError($error_msg) {
        $_SESSION["user_error_msg"] = $error_msg;

        header('Location: ../pages/profile.php');
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
                setError("Could not process your request");
            }
        } else {
            setError("Could not process your request");
        }

        // Get Details

        $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
        $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        // Data Validation
    
        if (empty($first_name)) {
            setError("Enter a valid first name");
        }
        if (empty($last_name)) {
            setError("Enter a valid last name");
        }
        if (empty($email)) {
            setError("Enter a valid email address");
        }
        if (empty($password)) {
            setError("Enter a valid password");
        }

        $password = trim($password, '*');

        // Move image if it exists

        $filePath = "";

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    
            // Get image details
            $fileTmpPath = $_FILES['picture']['tmp_name'];
            $fileName = $_FILES['picture']['name'];
            $fileType = $_FILES['picture']['type'];
    
            // Allowed File Types
            $allowedTypes = ['image/jpeg', 'image/png', "image/webp"];

    
            // Validate the image type
            if (in_array($fileType, $allowedTypes)) {

                $uploadDir = '../assets/imgs/users/';
                $newFileName = uniqid() . '_' . $fileName;
                $targetPath = $uploadDir . $newFileName;

                $filePath = "/assets/imgs/users/" . $newFileName;
    
                // Move the uploaded file to the target directory
                if (!move_uploaded_file($fileTmpPath, $targetPath)) {
                    setError("Error uploading image. Please try again");
                }
            } else {
                setError("Invalid file type. Only JPEG, PNG and WEBP are allowed.");
            }
        }
        
        if ($filePath != "") {
            if ($password != "") {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn -> prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, user_image = ? WHERE user_id = ?");
                $stmt -> bind_param("sssssi", $first_name, $last_name, $email, $password_hash, $filePath, $id);
            } else {
                $stmt = $conn -> prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, user_image = ? WHERE user_id = ?");
                $stmt -> bind_param("ssssi", $first_name, $last_name, $email, $filePath, $id);
            }
        } else {
            if ($password != "") {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn -> prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE user_id = ?");
                $stmt -> bind_param("ssssi", $first_name, $last_name, $email, $password_hash, $id);
            } else {
                $stmt = $conn -> prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
                $stmt -> bind_param("sssi", $first_name, $last_name, $email, $id);
            }
        }

        if ($stmt -> execute()) {
            $_SESSION["user_success_msg"] = "User edited successfully";
        } else {
            setError("Could not process your request");
        }
    }

    header('Location: ../pages/profile.php');