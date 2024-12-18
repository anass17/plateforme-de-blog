<?php
    require_once "validate-token.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen flex-col">

    <?php 
        include "../inc/header.php";
    ?>
    
    <div class="bg-gray-200 flex items-center justify-center h-full">
        <form action="auth.php" method="POST" class="bg-white rounded-lg shadow-lg px-7 py-6 w-full max-w-lg">
            <h2 class="text-center mb-7 text-xl font-bold text-green-500">LOG IN</h2>
            <div class="form-input mb-5">
                <label for="email" class="block mb-1">Email</label>
                <input type="text" placeholder="Write your email" class="border border-gray-300 rounded w-full px-3 py-2" id="email" name="email">
            </div>
            <div class="form-input mb-4">
                <label for="password" class="block mb-1">Password</label>
                <input type="password" placeholder="Write your password" class="border border-gray-300 rounded w-full px-3 py-2" id="password" name="password">
            </div>
            <p class="text-sm text-gray-500">You don't have an account? <a href="signup.php" class="font-semibold text-blue-500">Create one</a></p>
            <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white font-semibold mt-5" name="form-type" value="login">Log In</button>
        </form>
    </div>

</body>
</html>