<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./userlogin.css"> 
    <title>Login Page - TaxGen</title>
</head>
<body>
    <header>
        <div class="taxgen__navbar-links_logo">
            <img src="data:image/png;base64,..." alt="logo" class="logo">
        </div>
        <nav class="nav-links">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Packages</a>
            <a href="#">Contact Us</a>
        </nav>
        <a href="./Signup.html" class="btn btn-secondary">Sign Up</a>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="login-modal">
            <h2>Welcome Back</h2>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>

            <form action="login.php" method="POST">
                <label for="email">Email Address *</label>
                <input id="email" name="email" type="email" placeholder="Enter your email" required>
                <label for="password">Password *</label>
                <input id="password" name="password" type="password" placeholder="Enter your password" required>
                <button class="btn-primary" type="submit">Login</button>
            </form>
            <div class="social-login">
                <button class="google-btn" onclick="loginWithGoogle()">
                    <img src="https://img.icons8.com/color/20/google-logo.png" alt="Google Logo">
                    Continue with Google
                </button>
                <button class="microsoft-btn" onclick="loginWithMicrosoft()">
                    <img src="https://img.icons8.com/color/20/microsoft.png" alt="Microsoft Logo">
                    Continue with Microsoft
                </button>
                <button class="apple-btn" onclick="loginWithApple()">
                    <img src="https://img.icons8.com/color/20/apple.png" alt="Apple Logo">
                    Continue with Apple
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>TaxGen is an innovative tax management solution. All rights reserved © 2024</p>
        <a href="#">Privacy Policy</a>
    </footer>

    <script>
        function loginWithGoogle() {
            window.location.href = "https://accounts.google.com/o/oauth2/v2/auth?client_id=YOUR_GOOGLE_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=token&scope=profile email";
        }

        function loginWithMicrosoft() {
            window.location.href = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=YOUR_MICROSOFT_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=token&scope=User.Read";
        }

        function loginWithApple() {
            window.location.href = "https://appleid.apple.com/auth/authorize?client_id=YOUR_APPLE_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=code&scope=name email";
        }
    </script>
</body>
</html>
