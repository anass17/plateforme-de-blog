<?php
    require_once "auth/JWT.php";

    $id = null;
    $email = null;
    $role = null;

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">

    <?php 
        include "inc/header.php";
    ?>
    
    <div class="flex">
        <div class="py-10 px-3 w-full">
            <h1 class="text-2xl font-semibold text-green-500 text-center mb-10">Dive into the blogs we post</h1>
            <div class="grid grid-cols-3 gap-4 max-w-7xl mx-auto px-3">
                <div class="blog shadow rounded-lg overflow-hidden">
                    <div class="h-56 bg-[url('assets/imgs/test.jpg')] bg-cover bg-center">
                    </div>
                    <div class="blog-header border border-gray-200 px-5 py-4">
                        <h2 class="text-center mb-4 text-xl font-semibold text-green-600"><a href="#">The colorful season</a></h2>
                        <p class="text-gray-600 text-center"><span class="font-semibold">Anass Boutaib</span> • <span class="text-sm text-gray-500">17 Jul 2024 - 16:11</span></p>
                        <p class="mt-4 text-gray-700">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sit, ab officiis adipisci nulla, accusamus consectetur labore totam id soluta sapiente dolorum architecto tempora odio, quisquam non beatae iste eos nobis?<p>
                        <div class="tags mt-5 flex gap-2">
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">Nature</button>
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">Season</button>
                        </div>
                    </div>
                </div>
                <div class="blog shadow rounded-lg overflow-hidden">
                    <div class="h-56 bg-[url('assets/imgs/test2.jpg')] bg-cover bg-center">
                    </div>
                    <div class="blog-header border border-gray-200 px-5 py-4">
                        <h2 class="text-center mb-4 text-xl font-semibold text-green-600"><a href="#">The power of programming languages</a></h2>
                        <p class="text-gray-600 text-center"><span class="font-semibold">Ahmed Taoudi</span> • <span class="text-sm text-gray-500">10 Jul 2024 - 23:40</span></p>
                        <p class="mt-4 text-gray-700">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sit, ab officiis adipisci nulla, accusamus consectetur labore totam id soluta sapiente dolorum architecto tempora odio, quisquam non beatae iste eos nobis?<p>
                        <div class="tags mt-5 flex gap-2">
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">IT</button>
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">Programming</button>
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">PHP</button>
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">JS</button>
                        </div>
                    </div>
                </div>
                <div class="blog shadow rounded-lg overflow-hidden">
                    <div class="h-56 bg-[url('assets/imgs/test3.jpg')] bg-cover bg-center">
                    </div>
                    <div class="blog-header border border-gray-200 px-5 py-4">
                        <h2 class="text-center mb-4 text-xl font-semibold text-green-600"><a href="#">How to plan ahead?</a></h2>
                        <p class="text-gray-600 text-center"><span class="font-semibold">AbdelHafid Ait Lmkhtar</span> • <span class="text-sm text-gray-500">6 Jun 2024 - 12:31</span></p>
                        <p class="mt-4 text-gray-700">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sit, ab officiis adipisci nulla, accusamus consectetur labore totam id soluta sapiente dolorum architecto tempora odio, quisquam non beatae iste eos nobis?<p>
                        <div class="tags mt-5 flex gap-2">
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">Planning</button>
                            <button type="button" class="inline-block px-3 py-2 bg-gray-800 text-white rounded-md">Life</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>