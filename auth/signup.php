<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen flex-col">

    <?php 
        include "../inc/header.php";

        require_once "validate-token.php";
    ?>
    
    <div class="bg-gray-200 flex items-center justify-center h-full">
        <form action="auth.php" method="POST" class="bg-white rounded-lg shadow-lg px-7 py-6 w-full max-w-lg">
            <h2 class="text-center mb-7 text-2xl font-bold text-green-500">SIGN UP</h2>
            <div class="form-group mb-5 flex gap-4">
                <div class="form-input w-full">
                    <label for="first_name" class="block mb-1">First Name</label>
                    <input type="text" placeholder="Anass" class="border border-gray-300 rounded w-full px-3 py-2" id="first_name" name="first_name">
                </div>
                <div class="form-input w-full">
                    <label for="last_name" class="block mb-1">Last Name</label>
                    <input type="text" placeholder="Boutaib" class="border border-gray-300 rounded w-full px-3 py-2" id="last_name" name="last_name">
                </div>
            </div>
            <div class="form-input mb-5">
                <label for="email" class="block mb-1">Email</label>
                <input type="text" placeholder="username@example.com" class="border border-gray-300 rounded w-full px-3 py-2" id="email" name="email">
            </div>
            <div class="form-input mb-4">
                <label for="password" class="block mb-1">Password</label>
                <input type="password" placeholder="At least 8 characters" class="border border-gray-300 rounded w-full px-3 py-2" id="password" name="password">
            </div>
            <div class="form-input mb-4">
                <label for="confirm-password" class="block mb-1">Confirm Password</label>
                <input type="password" placeholder="At least 8 characters" class="border border-gray-300 rounded w-full px-3 py-2" id="confirm-password" name="confirm-password">
            </div>
            <div class="mb-4 flex items-center gap-3">
                <input type="checkbox" name="agreement" id="agreement" class="relative top-0.5 scale-125">
                <label for="agreement">I agree to the <a href="#" class="font-semibold text-blue-500">terms and conditions</a></label>
            </div>
            <p class="text-sm text-gray-500">You already have an account? <a href="login.php" class="font-semibold text-blue-500">Log in</a></p>
            <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white font-semibold mt-5" name="form-type" value="signup">Sign Up</button>
        </form>
    </div>

</body>
</html>