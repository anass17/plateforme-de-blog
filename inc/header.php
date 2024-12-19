<header class="bg-green-400 shadow-lg relative">
    <div class="container max-w-7xl flex justify-between items-center mx-auto px-3 h-20">
        <div>
            <a href="/blogs.php" class="text-lg font-bold">Blog</a>
        </div>
        <?php
            if ($role === "user" || $role === "admin") {
                echo 
                '<nav>
                    <a href="/blogs.php" class="font-semibold text-md ml-4">Blogs</a>
                    <a href="#" class="font-semibold text-md ml-4">Profile</a>
                    <a href="/auth/logout.php" class="font-semibold text-md ml-4">logout</a>
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