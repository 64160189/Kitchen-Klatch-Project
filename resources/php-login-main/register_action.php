<?php
include_once('./function.php');
$objCon = connectDB();

$data = $_POST;
$u_fullname = $data['u_fullname'];
$u_username = $data['u_username'];
$u_password = md5($data['u_password']); // เข้ารหัสด้วย md5


$strSQL = "INSERT INTO 
user(
    `u_fullname`,
    `u_username`,
    `u_password`
) VALUES (
    '$u_fullname', 
    '$u_username', 
    '$u_password'
)";

$objQuery = mysqli_query($objCon, $strSQL) or die(mysqli_error($objCon));
if ($objQuery) {
    echo '<script>alert("ลงทะเบียนเรียบร้อยแล้ว");window.location="login.php";</script>';
} else {
    echo '<script>alert("พบข้อผิดพลาด");window.location="register.php";</script>';
}