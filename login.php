<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");

    if ($username != "") {

        // Save entered name in session
        $_SESSION["username"] = $username;

        // Open dashboard
        header("Location: dashboard.php");
        exit();

    } else {

        $error = "Please enter your name.";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>KrushiMitra Login</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background:
                linear-gradient(
                    135deg,
                    #14532d,
                    #16a34a
                );
        }

        .login-box {

            width: 400px;

            max-width: 90%;

            background: white;

            padding: 40px;

            border-radius: 20px;

            box-shadow:
                0 15px 40px
                rgba(0,0,0,0.25);

        }

        .logo {

            text-align: center;

            font-size: 55px;

            margin-bottom: 10px;
        }

        h1 {

            text-align: center;

            color: #14532d;

            margin-bottom: 5px;
        }

        .subtitle {

            text-align: center;

            color: #78909c;

            margin-bottom: 30px;
        }

        label {

            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #37474f;
        }

        input {

            width: 100%;

            padding: 14px;

            border: 1px solid #ccc;

            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 15px;

            outline: none;
        }

        input:focus {

            border-color: #16a34a;
        }

        button {

            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 8px;

            background: #15803d;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;
        }

        button:hover {

            background: #14532d;
        }

        .error {

            background: #ffebee;

            color: #c62828;

            padding: 10px;

            border-radius: 8px;

            text-align: center;

            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="login-box">

    <div class="logo">
        🌱
    </div>

    <h1>
        KrushiMitra
    </h1>

    <p class="subtitle">
        Agriculture Management System
    </p>

    <?php if (isset($error)): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>
            Enter Your Name
        </label>

        <input
            type="text"
            name="username"
            placeholder="Enter your name"
            required
        >


        <button type="submit">

            <i>→</i>
            Login

        </button>

    </form>

</div>

</body>

</html>