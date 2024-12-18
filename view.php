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
        include "inc/header.php";
    ?>

    <div class="max-w-7xl mx-auto px-3 py-10 flex gap-4 items-start">
        <div class="author-window border border-gray-300 rounded-lg p-6 w-[40%]">
            <h2 class="text-green-500 font-semibold text-lg">Anass Boutaib</h2>
            <span class="text-gray-500 text-sm">Joined Since: 08 Apr 2018</span>
            <div class="mt-5">
                <h3 class="mb-2">Other published posts by Anass:</h3>
                <ul>
                    <li><a href="#" class="text-blue-500 font-semibold">Frontend or Backend?</a></li>
                    <li><a href="#" class="text-blue-500 font-semibold">The new technology that everyone must know</a></li>
                </ul>
            </div>
            
        </div>
        <div class="w-[60%]">
            <div class="blog shadow rounded-lg overflow-hidden">
                <div class="h-80 bg-[url('assets/imgs/test.jpg')] bg-cover bg-center">
                </div>
                <div class="blog-header border border-gray-200 px-5 py-4">
                    <h1 class="text-center mb-4 text-xl font-semibold text-green-600"><a href="#">The colorful season</a></h1>
                    <p class="text-gray-600 text-center"><span class="font-semibold">Anass Boutaib</span> • <span class="text-sm text-gray-500">17 Jul 2024 - 16:11</span></p>
                    <div class="tags mt-3 mb-2 flex gap-2 justify-center">
                        <button type="button" class="inline-block px-3 py-2 text-sm bg-gray-800 text-white rounded-md">Nature</button>
                        <button type="button" class="inline-block px-3 py-2 text-sm bg-gray-800 text-white rounded-md">Season</button>
                    </div>
                    <p class="mt-4 text-gray-700">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sit, ab officiis adipisci nulla, accusamus consectetur labore totam id soluta sapiente dolorum architecto tempora odio, quisquam non beatae iste eos nobis?<br><br>
                    Hablar conmigo mi amigo ab offipisci nulla, accusamus consectetur labore totam amet consectetur elit. Sit,  idds sapiente dolorrchitecto tempora odio, quisquam tae iste eos nobis?<br><br>
                    soluta sapiente dolorum architecto tempora odio, quisquam non beatae iste eos nobis? labore totam amet consectetur elit. Sit,  idds sapiente dolorrchitecto tem</p>
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
                        <span>5</span>
                        <span type="button" class="font-semibold ml-1">Comments</span>
                    </div>
                </div>
                <div class="border border-gray-200">
                    <div class="comment px-5 py-3">
                        <div class="comment-header">
                            <h4 class="font-semibold text-green-500">Someone unknown</h4>
                            <span class="text-xs text-gray-600 block">18 Jul 2024 - 08:23</span>
                        </div>
                        <p class="text-gray-800 mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo, iusto.</p>
                    </div>
                    <div class="comment px-5 py-3">
                        <div class="comment-header">
                            <h4 class="font-semibold text-green-500">Someone unknown</h4>
                            <span class="text-xs text-gray-600 block">18 Jul 2024 - 08:23</span>
                        </div>
                        <p class="text-gray-800 mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo, iusto.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>