<?php

    require_once "JWT.php";

    if (isset($_COOKIE['token'])) {

        $validation_result = validateJWT($_COOKIE['token']);

        if ($validation_result) {
            header('Location: ../blogs.php');
        } else {
            setcookie('token', '', 0);
        }
    }

?>