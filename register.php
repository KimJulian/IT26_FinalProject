<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - HealthFile</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #f4f4f4; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            width: 350px; 
            text-align: center; 
        }
        input { 
            width: 100%; 
            padding: 12px;
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background-color: #9A6F77; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Create Account</h2>
        <form action="register_process.php" method="POST">
            <input type="email" name="email" placeholder="Enter Your Email" required>
            <input type="text" name="username" placeholder="Create Username" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit">SIGN UP</button>
        </form>
        <p style="font-size: 0.9em; margin-top: 15px;">Already have an account? <a href="login.php" style="color: #9A6F77;">Login</a></p>
    </div>
</body>
</html>