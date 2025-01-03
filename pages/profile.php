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

        // Get user details

        $stmt = $conn -> prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt -> bind_param("i", $id);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $user_row = $result -> fetch_assoc();
        }

        // Get statistics

        $stmt = $conn -> prepare("SELECT count(*) as count FROM posts WHERE post_author = ?
        UNION All SELECT count(*) FROM comments WHERE comment_author = ?
        UNION ALL SELECT count(*) FROM post_reactions WHERE react_user = ?");

        $stmt -> bind_param("iii", $id, $id, $id);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $counters = array();

            while($count_row = $result -> fetch_assoc()) {
                array_push($counters, $count_row['count']);
            }
        }

        // Get published posts

        $stmt = $conn -> prepare("SELECT * FROM posts WHERE post_author = ?");
        $stmt -> bind_param("i", $id);

        if ($stmt -> execute()) {
            $posts_result = $stmt -> get_result();
        }

        // Get liked posts

        $stmt = $conn -> prepare("SELECT * FROM post_reactions join posts on posts.post_id = post_reactions.react_post WHERE react_user = ?");
        $stmt -> bind_param("i", $id);

        if ($stmt -> execute()) {
            $posts_react_result = $stmt -> get_result();
        }
    ?>
    
    <h1 class="sr-only">Profile</h1>
    <?php 
        if (isset($_SESSION["user_error_msg"])) {
            echo 
            "<div class='max-w-lg mx-auto bg-red-200 border border-red-300 px-8 py-5 rounded-md mt-10 text-center'>
                <h2 class='mb-3 font-bold text-lg'>Error!</h2>
                <p class='text-center font-semibold'>{$_SESSION["user_error_msg"]}</p>
            </div>";
            
        } else if (isset($_SESSION["user_success_msg"])) {
            echo 
            "<div class='max-w-lg mx-auto bg-green-200 border border-green-300 px-8 py-5 rounded-md mt-10 text-center'>
                <h2 class='mb-3 font-bold text-lg'>Awesome!</h2>
                <p class='text-center font-semibold'>{$_SESSION["user_success_msg"]}</p>
            </div>";
        }
        session_destroy();
    ?>

    <div class="max-w-7xl mx-auto px-3 py-10 grid gap-4 grid-cols-[35%_1fr] items-start">
        <div class="border border-gray-200 rounded-lg px-3 py-7 text-center">
            <div>
                <div class="w-16 h-16 rounded-full border-2 border-green-500 bg-gray-300 mx-auto mb-5">
                    <img src="<?php if ($user_row["user_image"] == "") {echo "/assets/imgs/users/default.webp";} else {echo $user_row["user_image"];} ?>" class="w-full rounded-full" alt="">
                </div>
                <h2 class="text-green-500 font-semibold mb-1 text-xl"><?php echo $user_row["first_name"] . ' ' . $user_row["last_name"]; ?></h2>
                <span class="text-gray-500 text-sm">Joined On: <?php echo format_date($user_row["registration_date"]); ?></span>
                <span class="h-[1px] w-3/6 bg-gray-200 mx-auto block my-5"></span>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">Statistics</h3>
                <ul>
                    <li class="mb-1"><?php echo $counters[0]; ?> Posts</li>
                    <li class="mb-1"><?php echo $counters[1]; ?> Comments</li>
                    <li class="mb-1"><?php echo $counters[2]; ?> Reactions</li>
                </ul>
                <span class="h-[1px] w-3/6 bg-gray-200 mx-auto block my-5"></span>
            </div>
            <div class="text-center profile-options">
                <button type="button" data-target="published-posts" class="block mx-auto font-semibold mb-1.5">Published Posts</button>
                <button type="button" data-target="liked-posts" class="block mx-auto font-semibold mb-1.5">Liked Posts</button>
                <button type="button" data-target="settings" class="block mx-auto font-semibold mb-1.5">Settings</button>
                <a href="../auth/logout.php" class="block mx-auto font-semibold mb-1.5">Log Out</a>
                <button type="button" data-target="delete-account" class="block mx-auto font-semibold text-red-500">Delete Account</button>
            </div>
        </div>
        <div id="option-blocks">
            <div class="border border-gray-200 rounded-lg flex-1 py-8 px-8 hidden" id="published-posts">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Published Posts</h2>

                <?php
                    if ($posts_result -> num_rows > 0) {
                        while($row = $posts_result -> fetch_assoc()) {
                            $formated_datetime = format_datetime($row["post_date"]);
                            echo 
                            "<div class='mb-6'>
                                <h3 class='font-semibold text-blue-500 text-lg'><a href='/view.php?id={$row["post_id"]}'>{$row["post_title"]}</a></h3>
                                <span class='text-gray-400 text-md'>{$formated_datetime}</span>
                                <p class='mt-2'>{$row["post_content"]}</p>
                            </div>";
                        }
                    } else {
                        echo '<p class="text-center font-semibold text-md text-gray-600">You have not published any posts yet.</p>';
                    }
                ?>
            </div>
            <div class="border border-gray-200 rounded-lg flex-1 py-8 px-8 hidden" id="liked-posts">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Liked Posts</h2>
                <?php
                    if ($posts_react_result -> num_rows > 0) {
                        while($row = $posts_react_result -> fetch_assoc()) {
                            $formated_datetime = format_datetime($row["post_date"]);
                            echo 
                            "<div class='mb-6'>
                                <h3 class='font-semibold text-blue-500 text-lg'><a href='/view.php?id={$row["post_id"]}'>{$row["post_title"]}</a></h3>
                                <span class='text-gray-400 text-md'>{$formated_datetime}</span>
                                <p class='mt-2'>{$row["post_content"]}</p>
                            </div>";
                        }
                    } else {
                        echo '<p class="text-center font-semibold text-md text-gray-600">You have not liked any posts yet.</p>';
                    }
                ?>
            </div>
            <div class="border border-gray-200 rounded-lg flex-1 py-8 px-12" id="settings">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Settings</h2>
                <form action="../requests/edit-profile.php" id="profile-form" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <p class="block mb-1 font-semibold">Profile Picture</p>
                        <label class="w-24 h-24 rounded overflow-hidden relative block" for="picture">
                            <img src="<?php if ($user_row["user_image"] == "") {echo "/assets/imgs/users/default.webp";} else {echo $user_row["user_image"];} ?>" class="block w-full">
                            <span class="w-full h-full bg-black absolute top-0 left-0 justify-center items-center bg-opacity-60 image-overlay hidden cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 fill-white" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128l-368 0zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39L296 392c0 13.3 10.7 24 24 24s24-10.7 24-24l0-134.1 39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z"/></svg>
                            </span>
                        </label>
                        <input type="file" id="picture" name="picture" class="hidden" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="px-3 py-1.5 border border-white rounded w-80 outline-none bg-white" value="<?php echo $user_row["first_name"]; ?>" placeholder="Write your first name" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="px-3 py-1.5 border border-white rounded w-80 outline-none bg-white" value="<?php echo $user_row["last_name"]; ?>" placeholder="Write your last name" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="email">Email</label>
                        <input type="text" id="email" name="email" class="px-3 py-1.5 border border-white rounded w-80 outline-none bg-white" value="<?php echo $user_row["email"]; ?>" placeholder="Write your email" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="password">Password</label>
                        <input type="password" id="password" name="password" class="px-3 py-1.5 border border-white rounded w-80 outline-none bg-white" value="********" placeholder="Write your password" disabled>
                    </div>
                    <div class="text-center buttons mt-5">
                        <button type="button" class="bg-green-500 text-white rounded px-7 py-2 edit-profile-btn">Edit</button>
                        <div class="hidden">
                            <button type="submit" class="bg-blue-500 text-white rounded px-7 py-2 mr-3">Save</button>
                            <button type="button" class="bg-gray-700 text-white rounded px-7 py-2 edit-profile-cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="border border-gray-200 rounded-lg flex-1 py-8 px-12 hidden" id="delete-account">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Delete Account</h2>
                <p class="font-semibold mb-5">If you delete your account, all your data will be removed and this cannot be undone.</p>
                <p class="font-semibold mb-5">Are you sure you want to delete your account? </p>
                <form action="" method="POST">
                    <input type="hidden" value="<?php echo $user_row["user_id"]; ?>" name="user_id">
                    <button type="submit" class="px-7 py-2 bg-red-500 rounded text-white">Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>


    <script src="../assets/js/profile.js"></script>
</body>
</html>