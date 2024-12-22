<?php
    session_start();

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

        $counts = mysqli_query($conn, "SELECT count(*) AS count FROM users GROUP BY user_role ORDER BY user_role");

        $counts_result = array();

        while($row = mysqli_fetch_assoc($counts)) {
            array_push($counts_result, $row["count"]);
        }
        
    ?>

    <h1 class="mt-8 text-center text-gray-600 font-semibold text-3xl">Dashboard</h1>
    <?php 
        if (isset($_SESSION["post_error_msg"])) {
            echo 
            "<div class='max-w-lg mx-auto bg-red-200 border border-red-300 px-8 py-5 rounded-md mt-10 text-center'>
                <h2 class='mb-3 font-bold text-lg'>Error!</h2>
                <p class='text-center font-semibold'>{$_SESSION["post_error_msg"]}</p>
            </div>";
            
        } else if (isset($_SESSION["post_success_msg"])) {
            echo 
            "<div class='max-w-lg mx-auto bg-green-200 border border-green-300 px-8 py-5 rounded-md mt-10 text-center'>
                <h2 class='mb-3 font-bold text-lg'>Awesome!</h2>
                <p class='text-center font-semibold'>{$_SESSION["post_success_msg"]}</p>
            </div>";
        }
        session_destroy();
    ?>
    <div class="max-w-7xl mx-auto px-3 py-10 grid gap-4 grid-cols-[25%_1fr] items-start">
        <div class="border border-gray-200 rounded-md px-5 py-7 shadow">
            <div class="dashboard-options">
                <button type="button" data-target="statistics" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64L0 400c0 44.2 35.8 80 80 80l400 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L80 416c-8.8 0-16-7.2-16-16L64 64zm406.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L320 210.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L240 221.3l57.4 57.4c12.5 12.5 32.8 12.5 45.3 0l128-128z"/></svg>
                    <span>Statistics</span>
                </button>
                <button type="button" data-target="users" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>
                    <span>Users</span>
                </button>
                <button type="button" data-target="posts" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 64c0 17.7-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l224 0c17.7 0 32 14.3 32 32zm0 256c0 17.7-14.3 32-32 32L32 352c-17.7 0-32-14.3-32-32s14.3-32 32-32l224 0c17.7 0 32 14.3 32 32zM0 192c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 224c-17.7 0-32-14.3-32-32zM448 448c0 17.7-14.3 32-32 32L32 480c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>
                    <span>Posts</span>
                </button>
                <button type="button" data-target="tags" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 80L0 229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7L48 32C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                    <span>Tags</span>
                </button>
                <button type="button" data-target="reported-posts" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 32C64 14.3 49.7 0 32 0S0 14.3 0 32L0 64 0 368 0 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-128 64.3-16.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30l0-247.7c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L64 48l0-16z"/></svg>
                    <span>Reported Posts</span>
                </button>
            </div>
        </div>
        <div id="option-blocks" class="shadow rounded-md">
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8" id="statistics">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Statistics</h2>

                <?php
                    $post_stats = mysqli_query($conn, "SELECT count(*) as count FROM posts");
                    $tag_stats = mysqli_query($conn, "SELECT count(*) as count FROM tags");
                ?>

                <div class="mb-5">
                    <h3 class="font-semibold mb-3 text-lg">Posts</h3>
                    <div class="grid grid-cols-3 gap-5">
                        <div class="bg-gradient-to-tr from-blue-800 to-blue-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Last 30 Days</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="bg-gradient-to-tr from-green-800 to-green-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Total</h4>
                            <span class="inline-block text-xl font-bold"><?php echo (mysqli_fetch_assoc($post_stats)["count"]); ?></span>
                        </div>
                        <div class="bg-gradient-to-tr from-gray-800 to-gray-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Reported</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h3 class="font-semibold mb-3 text-lg">Users</h3>
                    <div class="grid grid-cols-3 gap-5">
                        <div class="bg-gradient-to-tr from-orange-800 to-orange-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Users</h4>
                            <span class="inline-block text-xl font-bold"><?php echo $counts_result[1]; ?></span>
                        </div>
                        <div class="bg-gradient-to-tr from-violet-800 to-violet-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Admins</h4>
                            <span class="inline-block text-xl font-bold"><?php echo $counts_result[0] + $counts_result[3]; ?></span>
                        </div>
                        <div class="bg-gradient-to-tr from-red-800 to-red-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Total</h4>
                            <span class="inline-block text-xl font-bold"><?php echo $counts_result[0] + $counts_result[1] + $counts_result[3]; ?></span>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h3 class="font-semibold mb-3 text-lg">Reactions</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-2 border-green-500 py-5 text-center rounded-lg">
                            <h4 class="mb-1 font-semibold">Likes</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="border-2 border-orange-500 py-5 text-center rounded-lg">
                            <h4 class="mb-1 font-semibold">Dislikes</h4>
                            <span class="inline-block text-xl font-bold">14</span>
                        </div>
                    </div>
                </div>

                <div class="">
                    <h3 class="font-semibold mb-3 text-lg">Others</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-2 border-blue-500 py-5 text-center rounded-lg">
                            <h4 class="mb-1 font-semibold">Tags</h4>
                            <span class="inline-block text-xl font-bold"><?php echo (mysqli_fetch_assoc($tag_stats)["count"]); ?></span>
                        </div>
                        <div class="border-2 border-pink-500 py-5 text-center rounded-lg">
                            <h4 class="mb-1 font-semibold">Comments</h4>
                            <span class="inline-block text-xl font-bold">14</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8 hidden" id="users">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Users</h2>
                <h3 class="font-semibold text-lg mb-4"><span class="w-32 inline-block">Super Admins</span> <span class="text-gray-500 ml-7 w-7 h-7 text-sm bg-gray-200 rounded-full inline-flex justify-center items-center"><?php echo $counts_result[3]; ?></span></h3>
                <div class="mb-8">
                    <?php
                        $super_admins = mysqli_query($conn, "SELECT * FROM users WHERE user_role = 4");

                        while ($row = mysqli_fetch_assoc($super_admins)) {
                            $formated_date = format_date($row["registration_date"]);
                            echo 
                            "<div class='user-row flex justify-between items-center mb-5'>
                                <div class='flex items-center gap-5'>
                                    <img src='/assets/imgs/users/default.webp' class='w-16 h-16 rounded'>
                                    <div class=''>
                                        <h4 class=''><a href='/pages/user.php?id={$row["user_id"]}' class='text-blue-500 font-bold'>{$row["first_name"]} {$row["last_name"]}</a><span class='ml-5 text-gray-600'>&lt;{$row["email"]}&gt;</span></h4>
                                        <span class='text-sm text-gray-400'><span class='font-medium'>Joined On</span>: $formated_date</span>
                                    </div>
                                </div>
                            </div>";
                        }
                    ?>
                    
                </div>
                <h3 class="font-semibold text-lg mb-4"><span class="w-32 inline-block">Admins</span> <span class="text-gray-500 ml-7 w-7 h-7 text-sm bg-gray-200 rounded-full inline-flex justify-center items-center"><?php echo $counts_result[0]; ?></span></h3>
                <div class="mb-8">
                    <?php
                        $admins = mysqli_query($conn, "SELECT * FROM users WHERE user_role = 1");

                        if (mysqli_num_rows($admins) > 0) {
                            while ($row = mysqli_fetch_assoc($admins)) {
                                $formated_date = format_date($row["registration_date"]);
                                echo 
                                "<div class='user-row flex justify-between items-center mb-5'>
                                    <div class='flex items-center gap-5'>
                                        <img src='/assets/imgs/users/default.webp' class='w-16 h-16 rounded'>
                                        <div class=''>
                                            <h4 class=''><a href='' class='text-blue-500 font-bold'>{$row["first_name"]} {$row["last_name"]}</a><span class='ml-5 text-gray-600'>&lt;{$row["email"]}&gt;</span></h4>
                                            <span class='text-sm text-gray-400'><span class='font-medium'>Joined On</span>: $formated_date</span>
                                        </div>
                                    </div>";

                                    if ($role == "super_admin") {
                                        echo "<div class='flex gap-3'>
                                            <button type='button' class='px-4 py-1.5 rounded bg-gradient-to-tr from-blue-800 to-blue-400 flex items-center justify-center gap-2 text-white w-32'>
                                                <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 fill-white' viewBox='0 0 320 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M2 334.5c-3.8 8.8-2 19 4.6 26l136 144c4.5 4.8 10.8 7.5 17.4 7.5s12.9-2.7 17.4-7.5l136-144c6.6-7 8.4-17.2 4.6-26s-12.5-14.5-22-14.5l-72 0 0-288c0-17.7-14.3-32-32-32L128 0C110.3 0 96 14.3 96 32l0 288-72 0c-9.6 0-18.2 5.7-22 14.5z'/></svg>
                                                <span class='text-sm'>Downgrade</span>
                                            </button>
                                            <button type='button' class='px-4 py-1.5 rounded bg-gradient-to-tr from-red-800 to-red-400 flex items-center justify-center gap-2 text-white w-28'>
                                                <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 fill-white' viewBox='0 0 512 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z'/></svg>
                                                <span class='text-sm'>Block</span>
                                            </button>
                                        </div>";
                                    }
                                    
                                echo "</div>";
                            }
                        } else {
                            echo '<p class="mb-5 text-gray-600 ml-8">No admins were found. Try to upgrade users to become admins</p>';
                        }
                    ?>
                    
                </div>
                <h3 class="font-semibold text-lg mb-4"><span class="w-32 inline-block">Users</span> <span class="text-gray-500 ml-7 w-7 h-7 text-sm bg-gray-200 rounded-full inline-flex justify-center items-center"><?php echo $counts_result[1]; ?></span></h3>
                <div>
                    <?php
                        $users = mysqli_query($conn, "SELECT * FROM users WHERE user_role = 2");

                        if (mysqli_num_rows($users) > 0) {
                            while ($row = mysqli_fetch_assoc($users)) {
                                $formated_date = format_date($row["registration_date"]);
                                echo 
                                "<div class='user-row flex justify-between items-center mb-5'>
                                    <div class='flex items-center gap-5'>
                                        <img src='/assets/imgs/users/default.webp' class='w-16 h-16 rounded'>
                                        <div class=''>
                                            <h4 class=''><a href='' class='text-blue-500 font-bold'>{$row["first_name"]} {$row["last_name"]}</a><span class='ml-5 text-gray-600'>&lt;{$row["email"]}&gt;</span></h4>
                                            <span class='text-sm text-gray-400'><span class='font-medium'>Joined On</span>: $formated_date</span>
                                        </div>
                                    </div>
                                    <div class='flex gap-3'>";
                                        
                                        if ($role == "super_admin") {
                                            echo 
                                            "<button type='button' class='px-4 py-1.5 rounded bg-gradient-to-tr from-green-800 to-green-400 flex items-center justify-center gap-2 text-white w-28'>
                                                <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 fill-white' viewBox='0 0 320 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M318 177.5c3.8-8.8 2-19-4.6-26l-136-144C172.9 2.7 166.6 0 160 0s-12.9 2.7-17.4 7.5l-136 144c-6.6 7-8.4 17.2-4.6 26S14.4 192 24 192l72 0 0 288c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-288 72 0c9.6 0 18.2-5.7 22-14.5z'/></svg>
                                                <span class='text-sm'>Upgrade</span>
                                            </button>";
                                        }
                                        
                                        echo "<button type='button' class='px-4 py-1.5 rounded bg-gradient-to-tr from-red-800 to-red-400 flex items-center justify-center gap-2 text-white w-28'>
                                            <svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 fill-white' viewBox='0 0 512 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z'/></svg>
                                            <span class='text-sm'>Block</span>
                                        </button>
                                    </div>
                                </div>";
                            }
                        } else {
                            echo '<p class="mb-5 text-gray-600 ml-8">No registred users were found.</p>';
                        }
                    ?>
                </div>
            </div>
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8 hidden" id="posts">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Published Posts</h2>
                <div>
                    <?php
                        $posts = mysqli_query($conn, "SELECT * FROM posts join users on posts.post_author = users.user_id ORDER by post_date DESC");

                        while ($row = mysqli_fetch_assoc($posts)) {
                            $formated_datetime = format_datetime($row["post_date"]);
                            echo
                            "<div class='mb-8'>
                                <div class='flex justify-between items-center'>
                                    <div>
                                        <h3 class='font-semibold text-lg text-blue-500'><a href='/view.php?id={$row["post_id"]}'>{$row["post_title"]}</a></h3>
                                        <h4 class='text-gray-400 text-sm mb-3'><a href='/pages/user.php?id={$row["user_id"]}' class='text-gray-600 font-medium'>{$row["first_name"]} {$row["last_name"]}</a> / <span>$formated_datetime</span></h4>
                                    </div>
                                    <div class='flex gap-3'>
                                        <button type='button' class='px-4 py-2 rounded bg-gradient-to-tr from-blue-600 to-blue-400 shadow-lg flex gap-3 items-center'>
                                            <svg xmlns='http://www.w3.org/2000/svg' class='w-3.5 h-3.5 fill-white' viewBox='0 0 512 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'/></svg>
                                            <span class='text-white text-xs font-medium'>Edit</span>
                                        </button>
                                        <form action='/requests/delete-post.php' method='POST'>
                                            <input type='hidden' value='" . $row["post_id"] . "' name='post_id'>
                                            <button type='submit' class='px-4 py-2 rounded bg-gradient-to-tr from-red-600 to-red-400 shadow-lg flex gap-3 items-center'>
                                                <svg xmlns='http://www.w3.org/2000/svg' class='w-3.5 h-3.5 fill-white' viewBox='0 0 448 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z'/></svg>
                                                <span class='text-white text-xs font-medium'>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <p class='text-gray-700'>{$row["post_content"]}</p>
                            </div>";
                        }
                    ?>
                    
                </div>
                <?php
                
                    // echo '<div class="flex justify-center gap-3">
                    //     <button type="button" class="py-2 px-3 rounded bg-gray-200 fill-gray-700">
                    //         <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 320 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>
                    //     </button>
                    //     <button type="button" class="py-2 px-3 rounded bg-blue-500">
                    //         <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-white" viewBox="0 0 320 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
                    //     </button>
                    // </div>';
                ?>
            </div>
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8 hidden" id="tags">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Tags</h2>
                <p class="font-semibold text-center mb-5">Here, all the tags are listed with the number of posts they are used in.</p>
                <div class="mb-6 flex gap-4 justify-center">
                    <button type="button" class="shadow-lg inline-flex items-center text-white gap-3 px-5 py-2 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-4 h-4" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                        <span class="block">Add</span>
                    </button>
                    <button type="button" class="shadow-lg inline-flex items-center text-white gap-3 px-5 py-2 rounded-md bg-gradient-to-tr from-green-600 to-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-4 h-4" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
                        <span class="block">Edit</span>
                    </button>
                    <button type="button" class="shadow-lg inline-flex items-center text-white gap-3 px-5 py-2 rounded-md bg-gradient-to-tr from-red-600 to-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-4 h-4" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>
                        <span class="block">Delete</span>
                    </button>
                </div>
                <div class="my-9 bg-gray-300 mx-auto h-[1px] w-80">

                </div>
                <div class="grid grid-cols-4 gap-5">
                    <?php
                        $stmt = mysqli_query($conn, "SELECT tags.tag_id, tag_name, count(post_tags.tag_id) as count from tags left join post_tags on tags.tag_id = post_tags.tag_id group by tag_id");
                        while($row = mysqli_fetch_assoc($stmt)) {
                            echo
                            "<a href='/view.php?tag={$row["tag_id"]}' class='flex justify-between bg-gray-600 text-white px-4 py-2 rounded-md gap-3'>
                                <span>{$row["tag_name"]}</span>
                                <span>{$row["count"]}</span>
                            </a>";
                        }

                    ?>
                </div>
            </div>
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8 hidden" id="reported-posts">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Reported Posts</h2>
                <div>
                <div class='mb-8'>
                    <div class='flex justify-between items-center'>
                        <div>
                            <h3 class='font-semibold text-lg text-blue-500'><a href='#'>Coding Life</a></h3>
                            <h4 class='text-gray-400 text-sm mb-3'><a href='#' class="text-gray-600 font-medium">Anass Boutaib</a> / <span>16 Dec 2024 - 15:13</span></h4>
                        </div>
                        <div class='flex gap-3 items-center'>
                            <span class="text-gray-500 text-sm font-semibold mr-7">Reports: <span>5</span></span>
                            <button type='button' class='px-4 py-2 rounded bg-gradient-to-tr from-green-600 to-green-400 shadow-lg flex gap-3 items-center'>
                                <svg xmlns="http://www.w3.org/2000/svg" class='w-3.5 h-3.5 fill-white' viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                                <span class='text-white text-xs font-medium'>Mark Safe</span>
                            </button>
                            <button type='button' class='px-4 py-2 rounded bg-gradient-to-tr from-red-600 to-red-400 shadow-lg flex gap-3 items-center'>
                                <svg xmlns='http://www.w3.org/2000/svg' class='w-3.5 h-3.5 fill-white' viewBox='0 0 448 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z'/></svg>
                                <span class='text-white text-xs font-medium'>Delete</span>
                            </button>
                        </div>
                    </div>
                    <p class='text-gray-700'>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit ab quod ullam commodi magnam assumenda corporis! Quisquam dolores soluta impedit, aliquam perferendis ipsa eum consectetur earum nihil, reiciendis incidunt iste!</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/dashboard.js"></script>
</body>
</html>