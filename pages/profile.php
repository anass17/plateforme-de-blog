<?php
    require_once "../connect/db-connect.php";
    require_once "../auth/JWT.php";

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
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">

    <?php 
        include "../inc/header.php";
    ?>
    
    <div class="max-w-7xl mx-auto px-3 py-10 flex gap-4 items-start">
        <h1 class="sr-only">Profile</h1>
        <div class="border border-gray-200 rounded-lg px-3 py-7 text-center w-[35%]">
            <div>
                <div class="w-16 h-16 rounded-full border-2 border-green-500 bg-gray-300 mx-auto mb-5">
                    <img src="/assets/imgs/users/default.webp" class="w-full rounded-full" alt="">
                </div>
                <h2 class="text-green-500 font-semibold mb-1 text-xl">Ahmed Taoudi</h2>
                <span class="text-gray-500 text-sm">Joined On: 16 Apr 2024</span>
                <span class="h-[1px] w-3/6 bg-gray-200 mx-auto block my-5"></span>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">Statistics</h3>
                <ul>
                    <li class="mb-1">3 Posts</li>
                    <li class="mb-1">5 Comments</li>
                    <li class="mb-1">21 Reaction</li>
                </ul>
                <span class="h-[1px] w-3/6 bg-gray-200 mx-auto block my-5"></span>
            </div>
            <div class="text-center">
                <button type="button" class="block mx-auto font-semibold mb-1.5">Published Posts</button>
                <button type="button" class="block mx-auto font-semibold mb-1.5">Liked Posts</button>
                <button type="button" class="block mx-auto font-semibold mb-1.5">Settings</button>
                <button type="button" class="block mx-auto font-semibold mb-1.5">Log Out</button>
                <button type="button" class="block mx-auto font-semibold text-red-500">Delete Account</button>
            </div>
        </div>
        <div class="border border-gray-200 rounded-lg flex-1 py-8 px-8 hidden">
            <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Published Posts</h2>
            <div class="mb-6">
                <h3 class="font-semibold text-blue-500 text-lg"><a href="#">Top 5 Frameworks you must learn</a></h3>
                <span class="text-gray-400 text-md">20 Dec 2024 - 23:40</span>
                <p class="mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam animi itaque
                voluptate ipsa perferendis quae alias</p>
            </div>
            <div class="mb-6">
                <h3 class="font-semibold text-blue-500 text-lg"><a href="#">Top 5 Frameworks you must learn</a></h3>
                <span class="text-gray-400 text-md">20 Dec 2024 - 23:40</span>
                <p class="mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam animi itaque
                voluptate ipsa perferendis quae alias</p>
            </div>
        </div>
        <div class="border border-gray-200 rounded-lg flex-1 py-8 px-8 hidden">
            <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Liked Posts</h2>
            <div class="mb-6">
                <h3 class="font-semibold text-blue-500 text-lg"><a href="#">Top 5 Frameworks you must learn</a></h3>
                <span class="text-gray-400 text-md">20 Dec 2024 - 23:40</span>
                <p class="mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam animi itaque
                voluptate ipsa perferendis quae alias</p>
            </div>
            <div class="mb-6">
                <h3 class="font-semibold text-blue-500 text-lg"><a href="#">Top 5 Frameworks you must learn</a></h3>
                <span class="text-gray-400 text-md">20 Dec 2024 - 23:40</span>
                <p class="mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam animi itaque
                voluptate ipsa perferendis quae alias</p>
            </div>
        </div>
        <div class="border border-gray-200 rounded-lg flex-1 py-8 px-12 hidden">
            <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Settings</h2>
            <form action="">
                <div class="mb-3">
                    <label class="block mb-1 font-semibold" for="picture">Profile Picture</label>
                    <div>
                        <img src="/assets/imgs/users/default.webp" class="w-24 rounded">
                    </div>
                    <input type="file" id="picture" name="picture" class="hidden" disabled>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-semibold" for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="Ahmed" placeholder="Write your first name" disabled>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-semibold" for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="Taoudi" placeholder="Write your last name" disabled>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-semibold" for="email">Email</label>
                    <input type="text" id="email" name="email" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="ahmed.taoudi@gmail.com" placeholder="Write your email" disabled>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-semibold" for="password">Password</label>
                    <input type="password" id="password" name="password" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="********" placeholder="Write your password" disabled>
                </div>
                <div class="text-center">
                    <button class="bg-green-500 text-white rounded px-7 py-2">Edit</button>
                </div>
            </form>
        </div>
        <div class="border border-gray-200 rounded-lg flex-1 py-8 px-12">
            <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Delete Account</h2>
            <p class="font-semibold mb-5">If you delete your account, all your data will be removed and this cannot be undone.</p>
            <p class="font-semibold mb-5">Are you sure you want to delete your account? </p>
            <form action="" method="POST">
                <input type="hidden" value="" name="user_id">
                <button type="submit" class="px-7 py-2 bg-red-500 rounded text-white">Confirm Delete</button>
            </form>
        </div>
    </div>

</body>
</html>