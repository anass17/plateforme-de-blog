<?php
    session_start();

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
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="">

    <div class="absolute top-0 left-0 w-full">
        <?php 
            $home_page = '';
            include "inc/header.php";
        ?>
    </div>
    
    <div class="h-screen">
        <div class="w-full h-full bg-gradient-to-b from-[#001700] to-green-900">
            <div class="container mx-auto px-3 h-full w-full flex justify-between items-center text-white">
                <div class="w-6/12">
                    <h1 class="text-5xl font-bold mb-7">A Space for Inspiration</h1>
                    <h2 class="text-3xl font-semibold text-green-500 mb-4">Where creativity and insight come together</h2>
                    <p class="text-gray-300 leading-relaxed font-medium">Everyone has a story to tell. This space is designed to be a source of inspiration—whether through personal stories, expert advice, or creative exploration. Dive in and discover something that sparks the next great idea.</p>
                    <a href="./blogs.php" class="px-5 py-3 rounded bg-green-700 font-semibold text-white mt-5 inline-block hover:bg-green-800 transition-colors">Take a look <span class="ml-5">>></span></a>
                </div>
                <div class="w-5/12">
                    <img src="assets/imgs/vector.webp" alt="" class="w-full">
                </div>
            </div>
        </div>
    </div>

    <div class="py-32">
        <div class="">
            <div class="container mx-auto px-3 text-center">
                <div>
                    <h2 class="text-3xl mb-7 font-semibold text-green-600">Share Your Voice - Publish Your Own Post!</h2>
                    <p class="mb-9 max-w-4xl text-gray-700 mx-auto text-lg">The power of diverse voices and ideas is undeniable. Whether you're an expert, a hobbyist, or someone with a unique perspective to share, this is the perfect space to publish your thoughts.</p>
                    <div class="flex gap-12">
                        <div class="text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-green-700 w-9 h-9 mb-2 mx-auto block" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z"/></svg>
                            <b class="block mb-3 text-green-600">Express Yourself:</b> Share your ideas, stories, and knowledge with a community that cares.
                        </div>
                        <div class="text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-green-700 w-9 h-9 mb-2 mx-auto block" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M533.6 32.5C598.5 85.2 640 165.8 640 256s-41.5 170.7-106.4 223.5c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C557.5 398.2 592 331.2 592 256s-34.5-142.2-88.7-186.3c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zM473.1 107c43.2 35.2 70.9 88.9 70.9 149s-27.7 113.8-70.9 149c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C475.3 341.3 496 301.1 496 256s-20.7-85.3-53.2-111.8c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zm-60.5 74.5C434.1 199.1 448 225.9 448 256s-13.9 56.9-35.4 74.5c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C393.1 284.4 400 271 400 256s-6.9-28.4-17.7-37.3c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zM301.1 34.8C312.6 40 320 51.4 320 64l0 384c0 12.6-7.4 24-18.9 29.2s-25 3.1-34.4-5.3L131.8 352 64 352c-35.3 0-64-28.7-64-64l0-64c0-35.3 28.7-64 64-64l67.8 0L266.7 40.1c9.4-8.4 22.9-10.4 34.4-5.3z"/></svg>
                            <b class="block mb-3 text-green-600">Reach an Audience:</b> Your words can resonate with others who share your interests or are looking for new perspectives.
                        </div>
                        <div class="text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-green-700 w-9 h-9 mb-2 mx-auto block" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M248 106.6c18.9-9 32-28.3 32-50.6c0-30.9-25.1-56-56-56s-56 25.1-56 56c0 22.3 13.1 41.6 32 50.6l0 98.8c-2.8 1.3-5.5 2.9-8 4.7l-80.1-45.8c1.6-20.8-8.6-41.6-27.9-52.8C57.2 96 23 105.2 7.5 132S1.2 193 28 208.5c1.3 .8 2.6 1.5 4 2.1l0 90.8c-1.3 .6-2.7 1.3-4 2.1C1.2 319-8 353.2 7.5 380S57.2 416 84 400.5c19.3-11.1 29.4-32 27.8-52.8l50.5-28.9c-11.5-11.2-19.9-25.6-23.8-41.7L88 306.1c-2.6-1.8-5.2-3.3-8-4.7l0-90.8c2.8-1.3 5.5-2.9 8-4.7l80.1 45.8c-.1 1.4-.2 2.8-.2 4.3c0 22.3 13.1 41.6 32 50.6l0 98.8c-18.9 9-32 28.3-32 50.6c0 30.9 25.1 56 56 56s56-25.1 56-56c0-22.3-13.1-41.6-32-50.6l0-98.8c2.8-1.3 5.5-2.9 8-4.7l80.1 45.8c-1.6 20.8 8.6 41.6 27.8 52.8c26.8 15.5 61 6.3 76.5-20.5s6.3-61-20.5-76.5c-1.3-.8-2.7-1.5-4-2.1l0-90.8c1.4-.6 2.7-1.3 4-2.1c26.8-15.5 36-49.7 20.5-76.5S390.8 96 364 111.5c-19.3 11.1-29.4 32-27.8 52.8l-50.6 28.9c11.5 11.2 19.9 25.6 23.8 41.7L360 205.9c2.6 1.8 5.2 3.3 8 4.7l0 90.8c-2.8 1.3-5.5 2.9-8 4.6l-80.1-45.8c.1-1.4 .2-2.8 .2-4.3c0-22.3-13.1-41.6-32-50.6l0-98.8z"/></svg>    
                            <b class="block mb-3 text-green-600">Create Connections:</b> By contributing, you'll connect with like-minded readers and fellow writers.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative bg-center bg-cover bg-[url('/assets/imgs/background.jpg')] bg-fixed">
        <div class="py-24 bg-green-600 w-full h-full bg-opacity-75">
            <div class="container mx-auto px-5 text-center text-white">
                <h2 class="font-semibold mb-8 text-4xl" style="text-shadow: 0px 0px 5px black">A world of topics at your fingertips.</h2>
                <p class="font-semibold max-w-4xl mx-auto" style="text-shadow: 0px 0px 5px black">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Excepturi animi minima expedita molestias debitis! Beatae minima cupiditate ea vero earum aut magni ab ipsum dolorum facere, delectus vitae praesentium minus?</p>
            </div>
        </div>
    </div>

    <div class="relative">
        <div class="py-20">
            <div class="container mx-auto px-5 flex justify-between items-center">
                <div class="w-6/12">
                    <h2 class="font-semibold mb-8 text-4xl">Interact with others' posts and share your thoughts</h2>
                    <p class="font-semibold leading-8 text-gray-600">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Excepturi animi minima expedita molestias debitis! Beatae minima cupiditate ea vero earum aut magni ab ipsum dolorum facere, delectus vitae praesentium minus?</p>
                </div>
                <div class="w-4/12">
                    <img src="/assets/imgs/reaction.png" alt="">
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 py-5">
        <div class="text-white flex justify-between items-center container mx-auto px-3">
            <p class="font-semibold">Copyright &copy; All Right Reserved</p>
            <b>Created By Anass Boutaib</b>
        </div>
    </footer>

</body>
</html>