<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic System - Login</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 51, 102, 0.7), rgba(0, 51, 102, 0.7)), 
                        url('nbsc_background.jpg'); /* Replace with your image path */ 
            background-color: #f8f9fa; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-card { 
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 2.5rem; 
            border-radius: 16px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 380px; 
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-top: 5px solid #FFCC00;
            text-align: center;
        }
        h2 { 
            text-align: center; 
            color: #003366; 
            margin-bottom: 1.5rem; 
        }
        input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 1rem; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
        }
        button { 
            width: 100%; 
            padding: 14px; 
            background-color: #FFCC00;
            color: #003366;
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1rem;
            transition: all 0.3s ease; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        button:hover {
             background-color: #003366; 
        }
        .error { 
            color: #d9534f; 
            font-size: 0.9rem; 
            text-align: center; 
            margin-bottom: 1rem; 
        
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Clinic Login</h2>
        
        <?php 
        if(isset($_GET['error'])) {
            echo '<div class="error">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>

        <form action="login_process.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
            <p style="color: white; font-size: 0.9em; margin-top: 15px; text-align: center; font-weight : bold;">
                Don't have an account? 
                <a href="register.php" style="color: #FFCC00; font-weight: bold; text-decoration: none;">Register</a>
            </p>
        </form>
    </div>
</body>
</html>