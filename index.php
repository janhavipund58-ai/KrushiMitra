<!DOCTYPE html>
<html>
<head>
    <title>KrushiMitra - Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0b5d3b, #42b883);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 380px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            text-align: center;
        }

        .logo {
            font-size: 55px;
            margin-bottom: 5px;
        }

        h1 {
            color: #0b5d3b;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .input-box {
            text-align: left;
            margin-bottom: 20px;
        }

        .input-box label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-weight: bold;
        }

        .input-box input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        .input-box input:focus {
            border-color: #0b8f5a;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #0b5d3b;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-btn:hover {
            background: #087449;
        }

        .footer {
            margin-top: 25px;
            color: #888;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="logo">🌱</div>

    <h1>KRUSHIMITRA</h1>

    <p class="subtitle">
        Agriculture Shop Management
    </p>

    <form action="dashboard.php" method="post">

        <div class="input-box">
            <label>Username</label>
            <input type="text" name="username"
                   placeholder="Enter username" required>
        </div>

        <div class="input-box">
            <label>Password</label>
            <input type="password" name="password"
                   placeholder="Enter password" required>
        </div>

        <button type="submit" class="login-btn">
            LOGIN
        </button>

    </form>

    <div class="footer">
        🌾 Smart Agriculture • Better Farming
    </div>

</div>

</body>
</html>