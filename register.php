<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - HealthFile</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(rgba(0, 51, 102, 0.7), rgba(0, 51, 102, 0.7)), 
                    url('nbsc_background.jpg'); 
            background-size: cover;
            background-position: center;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-card { 
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px); 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            width: 380px; 
            text-align: center; 
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-top: 5px solid #FFCC00; 
        }
        h2 {
            color: #FFFFFF;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
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
            padding: 14px; 
            background-color: #FFCC00;
            color: #003366;
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1rem;
            margin-top: 10px;
            transition: all 0.3s ease; 
        }
        button:hover {
            background-color: #e6b800;
            transform: translateY(-2px);
        }
        .login-card p {
            color: #f0f0f0;
            font-size: 0.9rem;
            margin-top: 20px;
        }
        .login-card p a {
            color: #FFCC00;
            text-decoration: none;
            font-weight: bold;
        }
        .login-card p a:hover {
            color: #FFFFFF;
            text-decoration: underline;
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
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>