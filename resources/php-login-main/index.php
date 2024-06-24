<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.php"); // redirect ไปยังหน้า login.php
    exit;
}

$user = $_SESSION['user_login'];
?>
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

<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3">
            <h1>สวัสดี <?php echo $user['fullname']; ?></h1>
            <div class="mt-5">
                <a href="logout_action.php" class="btn btn-lg btn-danger">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</body>

</html>