<header class="bg-gradient-to-tr from-green-700 to-green-400 shadow-lg relative">
    <div class="container max-w-7xl flex justify-between items-center mx-auto px-3 h-20">
        <div>
            <a href="/blogs.php" class="text-lg font-bold text-white">Blog</a>
        </div>
        <?php
            if ($role === "user" || $role === "admin" || $role === "super_admin") {
                if ($image_url == "") {
                    $image_url = "/assets/imgs/users/default.webp";
                }
                echo 
                '<nav class="flex items-center">
                    <a href="/blogs.php" class="font-semibold text-md ml-4">Blogs</a>
                    ';  
                    
                    if ($role === "admin" || $role === "super_admin") {
                        echo '<a href="/pages/dashboard.php" class="font-semibold text-md ml-4">Dashboard</a>';
                    }
                    
                    echo '
                    <a href="/auth/logout.php" class="font-semibold text-md ml-4">logout</a>
                    <a href="/pages/profile.php" class="font-semibold text-md ml-4 inline-flex gap-3 items-center">
                        <span>' . $first_name . ' ' . $last_name . '</span>
                        <img src="' . $image_url . '" class="w-10 h-10 rounded-full border-2 border-green-700">    
                    </a>
                </nav>';
            } else {
                echo 
                '<nav>
                    <a href="/blogs.php" class="font-semibold text-md ml-4">Blogs</a>
                    <a href="/auth/login.php" class="font-semibold text-md ml-4">Log in</a>
                    <a href="/auth/signup.php" class="font-semibold text-md ml-4">Sign up</a>
                </nav>';
            }
        ?>
    </div>
</header>