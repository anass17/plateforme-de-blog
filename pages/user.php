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

        // Get user details

        $stmt = $conn -> prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt -> bind_param("i", $_GET["id"]);

        $user_image = '';

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $user_row = $result -> fetch_assoc();

            $user_image = $user_row["user_image"] == "" ? "/assets/imgs/users/default.webp" : $user_row["user_image"];
        }

        // Get statistics

        $stmt = $conn -> prepare("SELECT count(*) as count FROM posts WHERE post_author = ?
        UNION All SELECT count(*) FROM comments WHERE comment_author = ?
        UNION ALL SELECT count(*) FROM post_reactions WHERE react_user = ?");

        $stmt -> bind_param("iii", $_GET["id"], $_GET["id"], $_GET["id"]);

        if ($stmt -> execute()) {
            $result = $stmt -> get_result();

            $counters = array();

            while($count_row = $result -> fetch_assoc()) {
                array_push($counters, $count_row['count']);
            }
        }

        // Get published posts

        $stmt = $conn -> prepare("SELECT * FROM posts WHERE post_author = ?");
        $stmt -> bind_param("i", $_GET["id"]);

        if ($stmt -> execute()) {
            $posts_result = $stmt -> get_result();
        }
    ?>
    
    <h1 class="sr-only">Profile</h1>
    <div class="max-w-7xl mx-auto px-3 py-10 grid gap-4 grid-cols-[35%_1fr] items-start">
        <div class="border border-gray-200 rounded-lg px-3 py-7 text-center">
            <div>
                <div class="w-16 h-16 rounded-full border-2 border-green-500 bg-gray-300 mx-auto mb-5">
                    <img src="<?php echo $user_image; ?>" class="w-full rounded-full" alt="">
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
            </div>
        </div>
        <div id="option-blocks">
            <div class="border border-gray-200 rounded-lg flex-1 py-8 px-8" id="published-posts">
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
                        echo '<p class="text-center font-semibold text-md text-gray-600">This user has not published any posts yet.</p>';
                    }
                ?>

            </div>
        </div>
    </div>

</body>
</html>