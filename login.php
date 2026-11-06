<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Plain text check para siguradong makapasok ka
    if ($username === 'admin' && $password === '123') {
        $_SESSION['user_id']  = 1;
        $_SESSION['role']     = 'admin';
        $_SESSION['full_name']= 'System Administrator';
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | SAS Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --dark: #0f172a;
        }
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #0f172a, #1e1b4b, #312e81, #0f172a);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow: hidden;
            color: white;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Orbs Background */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: float 10s infinite ease-in-out alternate;
        }
        .orb-1 { width: 300px; height: 300px; background: rgba(99, 102, 241, 0.4); top: -50px; left: -50px; }
        .orb-2 { width: 400px; height: 400px; background: rgba(168, 85, 247, 0.3); bottom: -100px; right: -100px; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(30px) scale(1.1); }
        }

        /* Glassmorphism Card */
        .glass-login {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
            transition: transform 0.3s;
        }

        .glass-login:hover {
            transform: translateY(-5px);
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4);
        }

        .glass-login h2 {
            text-align: center;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 30px;
            font-size: 26px;
            background: -webkit-linear-gradient(45deg, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Inputs */
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            transition: 0.3s;
        }

        .form-control-custom {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 16px 20px 16px 50px;
            border-radius: 12px;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            outline: none;
            background: rgba(15, 23, 42, 0.9);
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
        }

        .form-control-custom:focus + i {
            color: var(--primary);
        }

        .form-control-custom::placeholder {
            color: #64748b;
        }

        /* Button */
        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 1px;
            margin-top: 10px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(99, 102, 241, 0.5);
        }

        /* Error Alert */
        .alert-custom {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #fca5a5;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="glass-login">
        <div class="brand-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h2>SCHOOL ATTENDANCE</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert-custom">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group-custom">
                <input type="text" name="username" class="form-control-custom" placeholder="Enter Username" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-group-custom">
                <input type="password" name="password" class="form-control-custom" placeholder="Enter Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <button type="submit" class="btn-login">
                LOGIN TO SYSTEM <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>
    </div>
</body>
</html>