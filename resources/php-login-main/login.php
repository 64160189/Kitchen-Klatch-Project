<?php session_start(); // เปิดใช้งาน session ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP login</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
</head>

<body class="text-center">
    <div class="container">
        <div class="register-box bg-light p-5 rounded mt-3">

            <main class="form-signin">
                <form method="post" action="login_action.php">
                    <h1 class="text-danger h2 mb-3 fw-bold">Login</h1>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username">
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <button class="w-100 btn btn-lg btn-danger" type="submit">Login</button>
                    <a href="register.php" class="w-100 btn btn-lg btn-secondary mt-3">Register</a>
                </form>
            </main>
        </div>
    </div>

</body>

</html>