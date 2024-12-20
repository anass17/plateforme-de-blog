<?php
    require_once "connect/db-connect.php";
    require_once "auth/JWT.php";

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
    <title>Blogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">

    <?php 
        include "inc/header.php";

        $sql = "select post_id, post_title, post_content, post_image, post_date, first_name, last_name from posts join users on users.user_id = posts.post_author order by post_date DESC";

        $result = mysqli_query($conn, $sql);

        // Select all tags

        $sql = "select tag_id, tag_name from tags";

        $tags = mysqli_query($conn, $sql);
    ?>
    
    <div class="flex">
        <div class="py-16 px-3 w-full">
            <h1 class="sr-only">Dive into the blogs we post</h1>
            <div class="flex gap-3 justify-center mb-8">
                <button class="add-blog-btn px-4 py-1.5 rounded-md bg-blue-500 text-white flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-white" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
                    <span>Write a Post</span>
                </button>
                <button class="px-4 py-1.5 rounded-md bg-blue-500 text-white flex items-center justify-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-white" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M151.6 42.4C145.5 35.8 137 32 128 32s-17.5 3.8-23.6 10.4l-88 96c-11.9 13-11.1 33.3 2 45.2s33.3 11.1 45.2-2L96 146.3 96 448c0 17.7 14.3 32 32 32s32-14.3 32-32l0-301.7 32.4 35.4c11.9 13 32.2 13.9 45.2 2s13.9-32.2 2-45.2l-88-96zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32l32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-32 0zm0 128c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0zm0 128c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0zm0 128c-17.7 0-32 14.3-32 32s14.3 32 32 32l224 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-224 0z"/></svg>
                    <span>Filter & Sort</span>
                </button>
            </div>
            <div class="grid grid-cols-3 gap-4 max-w-7xl mx-auto px-3">
                <?php
                    $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                    while ($row = mysqli_fetch_assoc($result)) {
                        $arr = explode(' ', $row["post_date"]);
                        $date_parts = explode('-', $arr[0]);
                        $formated_date = $date_parts[2] . ' ' . $months[$date_parts[1] - 1] . ' ' . $date_parts[0];
                        $time = substr($arr[1], 0, 5);

                        $formated_datetime = $formated_date . ' - ' . $time;

                        if (trim($row["post_image"]) != "") {
                            $post_image = urlencode($row["post_image"]);
                        } else {
                            $post_image = "/assets/imgs/blogs/placeholder.jpg";
                        }

                        $stmt = $conn -> prepare("SELECT tags.tag_id AS tag_id, tag_name FROM post_tags JOIN tags ON post_tags.tag_id = tags.tag_id WHERE post_id = ?");

                        $stmt -> bind_param("i", $row["post_id"]);

                        $stmt -> execute();

                        $tags_result = $stmt -> get_result();

                        echo 
                        "<div class='blog shadow rounded-lg overflow-hidden flex flex-col'>
                            <div class='h-56 bg-cover bg-center' style=\"background-image: url('$post_image')\">
                            </div>
                            <div class='border border-gray-200 px-5 py-4 flex-1 flex flex-col justify-between'>
                                <div class='blog-header'>
                                    <h2 class='text-center mb-4 text-xl font-semibold text-green-600'><a href='/view.php?id={$row["post_id"]}'>{$row["post_title"]}</a></h2>
                                    <p class='text-gray-600 text-center'><span class='font-semibold'>{$row["first_name"]} {$row["last_name"]}</span> • <span class='text-sm text-gray-500'>{$formated_datetime}</span></p>
                                    <p class='mt-4 text-gray-700'>{$row["post_content"]}<p>
                                </div>
                                <div class='tags mt-5 flex gap-2'>";

                                    if ($tags_result -> num_rows > 0) {
                                        while($tags_row = $tags_result -> fetch_assoc()) {
                                            echo "<a href='#' class='inline-block px-3 py-2 bg-gray-800 text-white rounded-md'>{$tags_row["tag_name"]}</a>";
                                        }
                                    } else {
                                        echo "<span class='inline-block px-3 py-2 bg-gray-200 rounded-md'>No Tags</span>";
                                    }
                                    
                                echo "</div>
                            </div>
                        </div>";
                    }
                ?>
                <div class="blog shadow rounded-lg overflow-hidden flex flex-col">
                    <div class="h-56 bg-[url('/assets/imgs/test2.jpg')] bg-cover bg-center">
                    </div>
                    <div class="blog-header border border-gray-200 px-5 py-4 flex-1">
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
                <div class="blog shadow rounded-lg overflow-hidden flex flex-col">
                    <div class="h-56 bg-[url('assets/imgs/test3.jpg')] bg-cover bg-center">
                    </div>
                    <div class="blog-header border border-gray-200 px-5 py-4 flex-1">
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

    <div class="w-full h-screen bg-black fixed bg-opacity-70 hidden justify-center items-center top-0 left-0 add-blog-modal">
        <div class="w-full max-w-lg bg-white rounded-lg shadow">
            <div class="modal-header flex justify-between px-7 py-4 border-b border-gray-300">
                <h2 class="font-semibold text-2xl">Write a Post</h2>
                <button type="button" class="font-bold text-2xl text-red-500 close-btn">X</button>
            </div>
            <div class="px-7 py-5">
                <form action="requests/add-blog.php" method="POST" enctype="multipart/form-data">
                    <div class="w-full mb-4">
                        <label for="blog-title" class="block mb-1">Title</label>
                        <input type="text" id="blog-title" name="blog-title" class="w-full px-3 py-2 border border-gray-300 rounded outline-none" placeholder="Enter your email">
                    </div>
                    <div class="mb-4">
                        <label for="blog-body" class="block mb-1">Blog Body</label>
                        <textarea id="blog-body" name="blog-body" class="w-full px-3 py-2 border border-gray-300 rounded outline-none resize-none h-32" placeholder="Enter your message"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="blog-tags" class="block mb-1">Tags</label>
                        <div class="">
                            <div class="bg-gray-200 mr-1 px-4 py-1.5 rounded-md inline-flex justify-center items-center gap-2">
                                <span class="tags-count">0</span>Tags
                            </div>
                            <button type="button" class="add-tags-btn bg-blue-500 text-white px-4 py-1.5 rounded-md inline-flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-3.5 h-3.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>    
                                <span>Add</span>
                            </button>
                        </div>
                        <input type="hidden" id="blog-tags" name="blog-tags" class="w-full px-3 py-2 border border-gray-300 rounded outline-none resize-none" readonly>
                    </div>
                    <div class="w-full mb-4">
                        <p class="block mb-1">Blog Image</p>
                        <label for="blog-image" class="w-full h-20 flex justify-center items-center cursor-pointer bg-gray-100 border border-gray-200 rounded gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-green-500" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128l-368 0zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39L296 392c0 13.3 10.7 24 24 24s24-10.7 24-24l0-134.1 39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z"/></svg>    
                            <span class="text-md font-semibold">Upload an image</span>
                        </label>
                        <input type="file" name="blog-image" id="blog-image" class="hidden">
                    </div>
                    <button type="submit" class="px-6 py-2 font-semibold rounded bg-green-500 text-white">POST</button>
                </form>
            </div>
        </div>
    </div>

    <div class="w-full h-screen bg-black fixed bg-opacity-70 hidden justify-center items-center top-0 left-0 add-tags-modal z-30">
        <div class="w-full max-w-lg bg-white rounded-lg shadow">
            <div class="modal-header flex justify-between px-7 py-4 border-b border-gray-300">
                <h2 class="font-semibold text-2xl">Select blog tags</h2>
                <button type="button" class="font-bold text-2xl text-red-500 close-btn">X</button>
            </div>
            <div class="px-7 py-5">
                <form action="" method="POST">
                    <div class="w-full mb-4">
                        <label for="tag-search" class="block mb-2 font-semibold">Search for tags</label>
                        <input type="text" id="tag-search" name="tag-search" class="w-full px-3 py-2 border border-gray-300 rounded outline-none" placeholder="Type in something ...">
                    </div>
                    <h2 class="mb-2 font-semibold">Available Tags</h2>
                    <div class="available-tags *:px-4 *:py-1.5 *:rounded-md *:border *:border-gray-200 mb-6 flex gap-3 flex-wrap content-start max-h-60">
                        
                        <?php
                            while($row = mysqli_fetch_assoc($tags)) {
                                echo "<button type='button' data-id='{$row["tag_id"]}' class='bg-gray-100'>{$row["tag_name"]}</button>";
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>

</body>
</html>