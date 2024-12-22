<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">

    <?php 

        require_once "connect/db-connect.php";
        require_once "auth/JWT.php";
        require_once "inc/functions.php";

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

        include "inc/header.php";

        $stmt = $conn -> prepare("SELECT * FROM posts join users on posts.post_author = users.user_id WHERE post_id = ?");

        $stmt -> bind_param("i", $_GET["id"]);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $row = $result -> fetch_assoc();

            $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
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

            $post_author_id = $row["post_author"];
        }
    ?>

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

    <div class="max-w-7xl mx-auto px-3 py-10 flex gap-4 items-start">

        <!-- Author Block -->

        <div class="author-window border border-gray-300 rounded-lg p-6 w-[40%]">
            <h2 class="text-green-500 font-semibold text-lg"><a href="/pages/user.php?id=<?php echo $row["user_id"]; ?>"><?php echo $row["first_name"] . ' ' . $row["last_name"]; ?></a></h2>
            <span class="text-gray-500 text-sm">Joined Since: <?php echo format_date($row["registration_date"]); ?></span>
            <div class="mt-5">

                <?php

                    $other_posts_stmt = $conn -> prepare("SELECT * FROM posts join users on posts.post_author = users.user_id WHERE user_id = ? and post_id != ?");

                    $other_posts_stmt -> bind_param("ii", $post_author_id, $_GET["id"]);

                    if ($other_posts_stmt -> execute()) {
                        $other_posts_result = $other_posts_stmt -> get_result();

                        if ($other_posts_result -> num_rows > 0) {

                            echo "<h3 class='mb-2'>Other published post by {$row["first_name"]}:</h3>";

                            echo '<ul>';
                            while($other_posts_row = $other_posts_result -> fetch_assoc()) {
                                echo "<li><a href='/view.php?id={$other_posts_row["post_id"]}' class='text-blue-500 font-semibold'>{$other_posts_row["post_title"]}</a></li>";
                            }
                            echo '</ul>';
                        } else {
                            echo "<p class='mb-2'>{$row["first_name"]}, does not have any other posts</p>";
                        }
                    } else {
                        echo 'Error! Could not process your request';
                    }

                ?>

            </div>
            
        </div>

        <!-- Post Block -->

        <div class="w-[60%]">
            <div class="blog shadow rounded-lg overflow-hidden">
                
                <div class="h-80 bg-cover bg-center relative group" style="background-image: url('<?php echo $post_image; ?>')">

                    <!-- Side Menu button for admins, super admins and post author -->

                    <?php if($role == 'admin' || $role == 'super_admin' || ($role == 'user' && $id == $row["post_author"])): ?>

                        <div class="absolute top-5 right-5 opacity-0 group-hover:opacity-100 transition-opacity post-menu-btn">
                            <button type="button" class="font-bold text-sm tracking-wider w-8 h-8 bg-gray-100 border-2 border-gray-600 text-gray-600 pb-0.5 rounded-full flex justify-center items-center">•••</button>
                        </div>

                    <?php endif; ?>
                </div>
                <div class="blog-header border border-gray-200 px-5 py-4">
                    <h1 class="text-center mb-4 text-xl font-semibold text-green-600"><a href="#"><?php echo $row["post_title"]; ?></a></h1>
                    <p class="text-gray-600 text-center"><span class="font-semibold"><?php echo $row["first_name"] . ' ' . $row["last_name"]; ?></span> • <span class="text-sm text-gray-500"><?php echo $formated_datetime; ?></span><?php if($row["is_modified"] == true) {echo "<span class='ml-3'>(modified)</span>";} ?></p>
                    
                    <div class="tags mt-3 mb-2 flex gap-2 justify-center">

                        <?php

                            // Select Tags that are linked to this post
                        
                            $stmt = $conn -> prepare("SELECT * FROM post_tags join tags on tags.tag_id = post_tags.tag_id WHERE post_id = ?");

                            $stmt -> bind_param("i", $_GET["id"]);

                            $tags_list = "";

                            if ($stmt -> execute()) {
                                $result = $stmt -> get_result();

                                if ($result -> num_rows > 0) {
                                    while($tags_row = $result -> fetch_assoc()) {
                                        $tags_list .= $tags_row["tag_id"] . ';';
                                        echo "<button type='button' class='inline-block px-3 py-2 text-sm bg-gray-800 text-white rounded-md font-semibold'>{$tags_row["tag_name"]}</button>";
                                    }
                                } else {
                                    echo "<span class='inline-block px-3 py-2 text-sm bg-gray-200 rounded-md font-semibold'>No Tags</span>";
                                }
                            } else {
                                echo 'Error! Could not process your request';
                            }
                        
                        ?>
                    
                    </div>
                    <p class='mt-4 text-gray-700'><?php echo $row["post_content"]; ?></p>
                </div>
                <div class="flex px-5 py-3 justify-between border border-gray-200">
                    <div class="flex">
                        <div class="mr-6">
                            <button type="button" class="font-semibold mr-1">Like</button>
                            <span>12</span>
                        </div>
                        <div>
                            <button type="button" class="font-semibold mr-1">Dislike</button>
                            <span>2</span>
                        </div>
                    </div>
                    <div class="ml-8">
                        <?php

                            // Select all comments that were written for this post

                            $stmt = $conn -> prepare("SELECT count(*) AS count FROM comments WHERE comment_post = ?");

                            $stmt -> bind_param("i", $_GET["id"]);

                            if ($stmt -> execute()) {
                                $result = $stmt -> get_result();

                                $count_row = $result -> fetch_assoc();

                                echo "<span>{$count_row["count"]}</span>";
                            } else {
                                echo 'Error! Could not process your request';
                            }
                        ?>
                        <span type="button" class="font-semibold ml-1">Comments</span>
                    </div>
                </div>

                <!-- Displaying Comments -->

                <div class="border border-gray-200">
                    <?php
                        $stmt = $conn -> prepare("SELECT * FROM comments join users ON comments.comment_author = users.user_id WHERE comment_post = ?");

                        $stmt -> bind_param("i", $_GET["id"]);

                        if ($stmt -> execute()) {
                            $result = $stmt -> get_result();

                            if ($result -> num_rows > 0) {
                                while($comment_row = $result -> fetch_assoc()) {

                                    $username = $comment_row["first_name"] != "" ? $comment_row["first_name"] . " " . $comment_row["last_name"] : $comment_row["email"];

                                    $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                                    $arr = explode(' ', $comment_row["comment_date"]);
                                    $date_parts = explode('-', $arr[0]);
                                    $formated_date = $date_parts[2] . ' ' . $months[$date_parts[1] - 1] . ' ' . $date_parts[0];
                                    $time = substr($arr[1], 0, 5);
                                    $formated_datetime = $formated_date . ' - ' . $time;

                                    echo 
                                    "<div class='comment px-5 py-3'>
                                        <div class='comment-header'>
                                            <h4 class='font-semibold text-green-500'>{$username}</h4>
                                            <span class='text-xs text-gray-600 block'>{$formated_datetime}</span>
                                        </div>
                                        <p class='text-gray-800 mt-2'>{$comment_row["comment_content"]}</p>
                                    </div>";
                                }
                            } else {
                                echo 
                                "<div class='px-5 py-8'>
                                    <p class='text-center rounded-md font-semibold text-gray-500'>No comments were found for this post</span>
                                </div>";
                            }
                        } else {
                            echo 'Error! Could not process your request';
                        }

                    ?>
                </div>
                <div class="">
                    <?php

                        // Comment Btn depending on the role                        

                        if ($role != null) {
                            echo 
                            '<form action="requests/add-comment.php" method="POST" class="flex flex-1">
                                <input type="hidden" name="post-id" value="' . $_GET["id"] . '">
                                <label for="comment-body" class="sr-only">Comment Body</label>
                                <input type="text" class="w-full outline-none px-5 border border-gray-200 rounded-bl-lg"id="comment-body" name="comment-body" placeholder="Write a comment ...">
                                <button type="submit" class="bg-green-500 text-white py-3 px-5 border border-green-500">Send</button>
                            </form>';
                        } else {
                            echo 
                            '<div class="flex flex-1">
                                <button type="submit" class="add-comment-btn bg-green-500 text-white py-3 px-5 border border-green-500 w-full">Send a Comment</button>
                            </div>';
                        }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Comment form for visitor -->

    <?php if($role == null): ?>

        <div class="add-comment-modal w-full h-screen bg-black fixed bg-opacity-70 justify-center items-center top-0 left-0 hidden">
            <div class="w-full max-w-lg bg-white rounded-lg shadow">
                <div class="modal-header flex justify-between px-7 py-4 border-b border-gray-300">
                    <h2 class="font-semibold text-2xl">Add a comment</h2>
                    <button type="button" class="close-btn font-bold text-2xl text-red-500">X</button>
                </div>
                <div class="px-7 py-5">
                    <form action="requests/add-comment.php" method="POST">
                        <input type="hidden" name="post-id" value="<?php echo $_GET["id"]; ?>">
                        <div class="w-full mb-4">
                            <label for="comment-email" class="block mb-1">Email</label>
                            <input type="text" id="comment-email" name="comment-email" class="w-full px-3 py-2 border border-gray-300 rounded outline-none" placeholder="Enter your email">
                        </div>
                        <div class="mb-4">
                            <label for="comment-body" class="block mb-1">Comment</label>
                            <textarea id="comment-body" name="comment-body" class="w-full px-3 py-2 border border-gray-300 rounded outline-none resize-none h-32" placeholder="Enter your comment"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2 font-semibold rounded bg-green-500 text-white">SEND</button>
                    </form>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- Side Menu and delete confirmation for admins, super admins and post author -->

    <?php if($role == 'admin' || $role == 'super_admin' || ($role == 'user' && $id == $row["post_author"])): ?>

    <div class="post-menu-modal w-full h-screen bg-black fixed bg-opacity-70 justify-center items-center top-0 left-0 hidden">
        <div class="w-full max-w-md bg-white rounded-lg shadow">
            <div class="modal-header flex justify-between px-7 py-4 border-b border-gray-300">
                <h2 class="font-semibold text-2xl">Post Menu</h2>
                <button type="button" class="close-btn font-bold text-2xl text-red-500">X</button>
            </div>
            <div class="px-7 py-10">
                <button type="button" class="modify-btn mb-5 font-semibold text-lg text-gray-700 mx-auto flex gap-3 items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-gray-700" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
                    <span>Modify Post</span>
                </button>
                <button type="button" class="mb-5 font-semibold text-lg text-gray-700 mx-auto flex gap-3 items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-gray-700" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M64 32C64 14.3 49.7 0 32 0S0 14.3 0 32L0 64 0 368 0 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-128 64.3-16.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30l0-247.7c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L64 48l0-16z"/></svg>
                    <span>Report Post</span>
                </button>
                <button type="button" class="delete-btn font-semibold text-lg text-red-500 mx-auto flex gap-3 items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-red-500" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>
                    <span>Delete Post</span>
                </button>
            </div>
        </div>
    </div>

    <div class="post-delete-modal w-full h-screen bg-black fixed bg-opacity-70 justify-center items-center top-0 left-0 hidden">
        <div class="w-full max-w-md bg-white rounded-lg shadow">
            <div class="modal-header flex justify-between px-7 py-4 border-b border-gray-300">
                <h2 class="font-semibold text-2xl">Post Menu</h2>
                <button type="button" class="close-btn font-bold text-2xl text-red-500">X</button>
            </div>
            <div class="px-7 py-10">
                <p class="font-medium">This will delete the post permanently. Are you sure you want to proceed?</p>
                <form action="/requests/delete-post.php" method="POST" class="mt-6">
                    <input type="hidden" value="<?php echo $row["post_id"]; ?>" name="post_id">
                    <button class="px-5 py-2 rounded bg-red-500 text-white">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <?php endif; ?>
    
    <!-- Side Menu and delete confirmation for admins, super admins and post author -->

    <?php if($role == 'admin' || $role == 'super_admin' || ($role == 'user' && $id == $row["post_author"])): ?>

        <div class="w-full h-screen bg-black fixed bg-opacity-70 <?php if (!isset($_GET["edit"])) {echo 'hidden';} else {echo 'flex';} ?> justify-center items-center top-0 left-0 edit-blog-modal">
            <div class="w-full max-w-lg bg-white rounded-lg shadow">
                <div class="modal-header flex justify-between px-7 py-4 border-b border-gray-300">
                    <h2 class="font-semibold text-2xl">Edit Post</h2>
                    <button type="button" class="font-bold text-2xl text-red-500 close-btn">X</button>
                </div>
                <div class="px-7 py-5">
                    <form action="requests/edit-post.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="post-id" value="<?php echo $row["post_id"]; ?>">
                        <div class="w-full mb-4">
                            <label for="blog-title" class="block mb-1">Title *</label>
                            <input type="text" id="blog-title" name="blog-title" value="<?php echo $row["post_title"]; ?>" class="w-full px-3 py-2 border border-gray-300 rounded outline-none" placeholder="Enter your email">
                        </div>
                        <div class="mb-4">
                            <label for="blog-body" class="block mb-1">Blog Body *</label>
                            <textarea id="blog-body" name="blog-body" class="w-full px-3 py-2 border border-gray-300 rounded outline-none resize-y h-32" placeholder="Enter your message"><?php echo trim($row["post_content"]); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="blog-tags" class="block mb-1">Tags *</label>
                            <div class="">
                                <div class="bg-gray-200 mr-1 px-4 py-1.5 rounded-md inline-flex justify-center items-center gap-2">
                                    <span class="tags-count"><?php echo count(explode(';', $tags_list)) - 1; ?></span>Tags
                                </div>
                                <button type="button" class="edit-tags-btn bg-blue-500 text-white px-4 py-1.5 rounded-md inline-flex justify-center items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-white w-3.5 h-3.5" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
                                    <span>Edit</span>
                                </button>
                            </div>
                            <input type="hidden" id="blog-tags" name="blog-tags" value="<?php echo $tags_list; ?>" class="w-full px-3 py-2 border border-gray-300 rounded outline-none resize-none" readonly>
                        </div>
                        <div class="w-full mb-4">
                            <p class="block mb-1">Blog Image</p>
                            <div class="text-center">
                                <label for="blog-image" class="inline-block mx-auto justify-center items-center cursor-pointer bg-gray-100 border border-gray-200 rounded gap-3">
                                    <img src="<?php echo $row["post_image"]; ?>" class="h-20 rounded">   
                                    <span class="sr-only">Upload an image</span>
                                </label>
                            </div>
                            <input type="file" name="blog-image" id="blog-image" class="hidden">
                        </div>
                        <button type="submit" id="edit-post-submit" class="px-6 py-2 font-semibold rounded bg-green-500 text-white">EDIT</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-full h-screen bg-black fixed bg-opacity-70 hidden justify-center items-center top-0 left-0 edit-tags-modal z-30">
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
                                $tags = mysqli_query($conn, "SELECT * FROM tags");

                                while($row = mysqli_fetch_assoc($tags)) {
                                    echo "<button type='button' data-id='{$row["tag_id"]}' class='bg-gray-100'>{$row["tag_name"]}</button>";
                                }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <script src="assets/js/view.js"></script>
</body>
</html>