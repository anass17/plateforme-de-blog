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

    <h1 class="mt-8 text-center text-gray-600 font-semibold text-3xl">Dashboard</h1>
    <div class="max-w-7xl mx-auto px-3 py-10 grid gap-4 grid-cols-[25%_1fr] items-start">
        <div class="border border-gray-200 rounded-md px-5 py-7 shadow">
            <div class="dashboard-options">
                <button type="button" data-target="statistics" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64L0 400c0 44.2 35.8 80 80 80l400 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L80 416c-8.8 0-16-7.2-16-16L64 64zm406.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L320 210.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L240 221.3l57.4 57.4c12.5 12.5 32.8 12.5 45.3 0l128-128z"/></svg>
                    <span>Statistics</span>
                </button>
                <button type="button" data-target="liked-posts" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>
                    <span>Users</span>
                </button>
                <button type="button" data-target="settings" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 64c0 17.7-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l224 0c17.7 0 32 14.3 32 32zm0 256c0 17.7-14.3 32-32 32L32 352c-17.7 0-32-14.3-32-32s14.3-32 32-32l224 0c17.7 0 32 14.3 32 32zM0 192c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 224c-17.7 0-32-14.3-32-32zM448 448c0 17.7-14.3 32-32 32L32 480c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/></svg>
                    <span>Posts</span>
                </button>
                <button type="button" data-target="tags" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 80L0 229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7L48 32C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                    <span>Tags</span>
                </button>
                <button type="button" data-target="settings" class="font-semibold mb-3 flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 relative top-0.5 fill-green-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 32C64 14.3 49.7 0 32 0S0 14.3 0 32L0 64 0 368 0 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-128 64.3-16.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30l0-247.7c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L64 48l0-16z"/></svg>
                    <span>Reported Posts</span>
                </button>
            </div>
        </div>
        <div id="option-blocks" class="shadow rounded-md">
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8 hidden" id="statistics">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Statistics</h2>
                <div class="mb-5">
                    <h3 class="font-semibold mb-3 text-lg">Posts</h3>
                    <div class="grid grid-cols-3 gap-5">
                        <div class="bg-gradient-to-tr from-blue-800 to-blue-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Last 7 Days</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="bg-gradient-to-tr from-green-800 to-green-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Last 30 Days</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="bg-gradient-to-tr from-gray-800 to-gray-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Total</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h3 class="font-semibold mb-3 text-lg">Users</h3>
                    <div class="grid grid-cols-3 gap-5">
                        <div class="bg-gradient-to-tr from-orange-800 to-orange-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Registred Users</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="bg-gradient-to-tr from-violet-800 to-violet-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Admins</h4>
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="bg-gradient-to-tr from-red-800 to-red-400 text-white py-5 text-center rounded-lg">
                            <h4 class="mb-1">Total</h4>
                            <span class="inline-block text-xl font-bold">5</span>
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
                            <span class="inline-block text-xl font-bold">5</span>
                        </div>
                        <div class="border-2 border-pink-500 py-5 text-center rounded-lg">
                            <h4 class="mb-1 font-semibold">Comments</h4>
                            <span class="inline-block text-xl font-bold">14</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8 hidden" id="liked-posts">
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
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-12 hidden" id="settings">
                <h2 class="text-3xl text-green-500 font-semibold mb-8 text-center">Settings</h2>
                <form action="">
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="picture">Profile Picture</label>
                        <div>
                            <img src="<?php if ($user_row["user_image"] == "") {echo "/assets/imgs/users/default.webp";} else {echo $user_row["user_image"];} ?>" class="w-24 rounded">
                        </div>
                        <input type="file" id="picture" name="picture" class="hidden" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="<?php echo $user_row["first_name"]; ?>" placeholder="Write your first name" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="<?php echo $user_row["last_name"]; ?>" placeholder="Write your last name" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold" for="email">Email</label>
                        <input type="text" id="email" name="email" class="px-3 py-1.5 rounded w-80 outline-none bg-white" value="<?php echo $user_row["email"]; ?>" placeholder="Write your email" disabled>
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
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-8" id="tags">
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
                            "<div class='flex justify-between bg-gray-600 text-white px-4 py-2 rounded-md gap-3'>
                                <span>{$row["tag_name"]}</span>
                                <span>{$row["count"]}</span>
                            </div>";
                        }

                    ?>
                </div>
            </div>
            <div class="border border-gray-200 rounded-md flex-1 py-8 px-12 hidden" id="delete-account">
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

    <script src="/assets/js/dashboard.js"></script>
</body>
</html>