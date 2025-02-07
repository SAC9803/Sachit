<?php
session_start();
require_once 'config.php'; // Make sure to create this file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Store</title>
    <!-- Keep all your existing CSS -->
    <style>
        /* Your existing CSS remains the same */
    </style>
</head>
<body>
    <nav>
        <ul>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="#" onclick="showLogin()">Login</a></li>
                <li><a href="#" onclick="showRegister()">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome to Our Store</h1>
        <div class="products">
            <div class="product-card">
                <img src="images/headphones.jpg" alt="Product 1">
                <div class="product-info">
                    <h3>Premium Headphones</h3>
                    <p>High-quality wireless headphones with noise cancellation</p>
                    <p class="price">$199.99</p>
                    <button>Add to Cart</button>
                </div>
            </div>
            <!-- Other product cards remain the same -->
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="auth-forms" id="loginForm">
        <button class="close-btn" onclick="hideAuth()">&times;</button>
        <h2>Login</h2>
        <form id="loginFormElement">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <div id="loginMessage" style="margin-top: 10px; color: red;"></div>
        </form>
    </div>

    <div class="auth-forms" id="registerForm">
        <button class="close-btn" onclick="hideAuth()">&times;</button>
        <h2>Register</h2>
        <form id="registerFormElement">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <div id="registerMessage" style="margin-top: 10px; color: red;"></div>
        </form>
    </div>

    <script>
        // Keep existing show/hide functions
        function showLogin() {
            document.getElementById('loginForm').classList.add('active');
            document.getElementById('overlay').classList.add('active');
        }

        function showRegister() {
            document.getElementById('registerForm').classList.add('active');
            document.getElementById('overlay').classList.add('active');
        }

        function hideAuth() {
            document.querySelectorAll('.auth-forms').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById('overlay').classList.remove('active');
        }

        // Updated form handling with fetch API
        document.getElementById('loginFormElement').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.reload(); // Reload page after successful login
                } else {
                    document.getElementById('loginMessage').innerHTML = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loginMessage').innerHTML = 'An error occurred. Please try again.';
            });
        });

        document.getElementById('registerFormElement').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            if (formData.get('password') !== formData.get('confirm_password')) {
                document.getElementById('registerMessage').innerHTML = 'Passwords do not match';
                return;
            }

            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('registerMessage').innerHTML = data.message;
                if (data.status === 'success') {
                    setTimeout(() => {
                        hideAuth();
                        showLogin();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('registerMessage').innerHTML = 'An error occurred. Please try again.';
            });
        });

        // Keep existing overlay click handler
        document.getElementById('overlay').addEventListener('click', hideAuth);
    </script>
</body>
</html>