<?php
    setcookie('token', '', time() - 0, '/', 'localhost', false, true);

    header('Location: ../index.php');
?>