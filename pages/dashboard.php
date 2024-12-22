<?php
    require_once "../connect/db-connect.php";
    require_once "../auth/JWT.php";
    require_once "../inc/functions.php";

    $id = null;
    $email = null;
    $role = null;
    $first_name = null;
    $last_name = null;
    $image_url = null;

    if (isset($_COOKIE['token'])) {

        $validation_result = validateJWT($_COOKIE['token']);

        if ($validation_result) {
            $id = $validation_result -> id;
            $email = $validation_result -> email;
            $role = $validation_result -> role;
            $first_name = $validation_result -> first_name;
            $last_name = $validation_result -> last_name;
            $image_url = $validation_result -> image_url;
        } else {
            setcookie('token', '', 0);
            header('Location: ../auth/login.php');
            exit;
        }
    } else {
        header('Location: ../auth/login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar {
            width: 0px;
        }
    </style>
</head>
<body class="overflow-y-scroll">

    <?php 
        include "../inc/header.php";
    ?>

</body>
</html>