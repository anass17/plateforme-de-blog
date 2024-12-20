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

    <div class="max-w-7xl mx-auto px-3 py-10 flex gap-4 items-start">
        <div class="author-window border border-gray-300 rounded-lg p-6 w-[40%]">
            <h2 class="text-green-500 font-semibold text-lg"><?php echo $row["first_name"] . ' ' . $row["last_name"]; ?></h2>
            <span class="text-gray-500 text-sm">Joined Since: 08 Apr 2018</span>
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
        <div class="w-[60%]">
            <div class="blog shadow rounded-lg overflow-hidden">
                <div class="h-80 bg-cover bg-center" style="background-image: url('<?php echo $post_image; ?>')">
                </div>
                <div class="blog-header border border-gray-200 px-5 py-4">
                    <h1 class="text-center mb-4 text-xl font-semibold text-green-600"><a href="#"><?php echo $row["post_title"]; ?></a></h1>
                    <p class="text-gray-600 text-center"><span class="font-semibold"><?php echo $row["first_name"] . ' ' . $row["last_name"]; ?></span> • <span class="text-sm text-gray-500"><?php echo $formated_datetime; ?></span></p>
                    
                    <div class="tags mt-3 mb-2 flex gap-2 justify-center">

                        <?php
                        
                            $stmt = $conn -> prepare("SELECT * FROM post_tags join tags on tags.tag_id = post_tags.tag_id WHERE post_id = ?");

                            $stmt -> bind_param("i", $_GET["id"]);

                            if ($stmt -> execute()) {
                                $result = $stmt -> get_result();

                                if ($result -> num_rows > 0) {
                                    while($tags_row = $result -> fetch_assoc()) {
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
                <div class="border border-gray-200">
                    <?php
                        $stmt = $conn -> prepare("SELECT * FROM comments join users ON comments.comment_author = users.user_id WHERE comment_post = ?");

                        $stmt -> bind_param("i", $_GET["id"]);

                        if ($stmt -> execute()) {
                            $result = $stmt -> get_result();

                            if ($result -> num_rows > 0) {
                                while($comment_row = $result -> fetch_assoc()) {

                                    $username = $comment_row["first_name"] != "" ? $comment_row["first_name"] . " " . $comment_row["last_name"] : $comment_row["email"];

                                    echo 
                                    "<div class='comment px-5 py-3'>
                                        <div class='comment-header'>
                                            <h4 class='font-semibold text-green-500'>{$username}</h4>
                                            <span class='text-xs text-gray-600 block'>18 Jul 2024 - 08:23</span>
                                        </div>
                                        <p class='text-gray-800 mt-2'>{$comment_row["comment_content"]}</p>
                                    </div>";
                                }
                            } else {
                                echo 
                                "<div class='px-5 py-3'>
                                    <p class='text-center rounded-md font-semibold'>No comments were found for this post</span>
                                </div>";
                            }
                        } else {
                            echo 'Error! Could not process your request';
                        }

                    ?>
                </div>
                <div class="">
                    <?php
                        if ($role != null) {
                            echo 
                            '<form action="" class="flex flex-1">
                                <input type="text" class="w-full outline-none px-5 border border-gray-200 rounded-bl-lg" placeholder="Write a comment ...">
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

    <script src="assets/js/view.js"></script>
</body>
</html>