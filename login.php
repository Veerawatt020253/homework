<?php session_start();

require_once('./config/conn.php');

$keyrecaptcha = "6LdUEzUpAAAAAAXMckT1_Fm35sMCYXKbY3QWY2fb"; // ใส่ key recaptcha ที่ได้จาก google ตรงนี้

$secretkeyrecaptcha = '6LdUEzUpAAAAAFVMekymw6nBztL5b1AHjJsBK6Wg';
if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretkeyrecaptcha . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
    if (!$captcha) {
        $missinginputsecret = ["The response parameter is missing."];
        print_r($missinginputsecret[0]);
    }
}
if (isset($_POST['submit'])  && $response['success'] == true) {
    // เมื่อคลิกที่กที่ปุ่ม 
    /// ทำคำสั่งเพิ่มข้อมูลหรืออื่นๆ
}

$name = $_SESSION['name'];

if ($name == null) {
    $_SESSION['login'] = false;
    $html_login = "d-block";
    $html_name = "d-none";
    // echo $html_login;
} else {
    $_SESSION['login'] = true;
    $html_login = "d-none";
    $html_name = "d-block";
    $surname = $_SESSION['surname'];
    echo "<script>location.href = './index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homework - login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="./Framework/bootstrap/css/bootstrap.min.css">

    <link rel="shortcut icon" href="./img/yrc_logo.png" type="image/png">

    <link rel=”icon” type=”image/png href=”./img/yrc_logo.png” />

    <link rel="stylesheet" href="./style.css">
    
    <link rel="stylesheet" href="./Framework/sweetalert/sweetalert2.css">

</head>

<body>
    <?php include_once('./header.php'); ?>
    <div class="mt-0 mb-3">
        
            <?php include('./banner.php'); ?>
            <div class="">
                
                <div class="container"> <br>
                    <h4>Homework : เข้าสู่ระบบ</h4>
                    <form action="" method="post">
                        <div class="mb-2">
                            <div class="col-sm-9">
                                <input type="text" id="username" name="username" class="form-control w-100" required minlength="3" placeholder="ชื่อผู้ใช้">
                                <small for="username" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control" required minlength="3" placeholder="รหัสผ่าน">
                            </div>
                        </div>
                        
                        <div class=" d-grid gap-2 col-sm-9 mb-3">
                            <button type="submit" id="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                        </div>
                    </form>
                </div>
                
            </div>
    </div>
    <?php include_once('./footer.php'); ?>
</body>
<script src="./Framework/bootstrap/js/bootstrap.bundle.js"></script>
<script src="./Framework/sweetalert/sweetalert2.all.js"></script>
<script src="https://kit.fontawesome.com/42b75145a7.js" crossorigin="anonymous"></script>
</html>


<?php

//print_r($_POST); //ตรวจสอบมี input อะไรบ้าง และส่งอะไรมาบ้าง 
//ถ้ามีค่าส่งมาจากฟอร์ม
if (isset($_POST['username']) && isset($_POST['password'])) {
    // sweet alert 
    echo '
    <link rel="stylesheet" href="./style.css">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';

    //ไฟล์เชื่อมต่อฐานข้อมูล
    require_once './config/conn.php';
    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $username = $_POST['username'];
    // $password = sha1($_POST['password']); //เก็บรหัสผ่านในรูปแบบ sha1 
    $password = $_POST['password'];

    //check username  & password
    $stmt = $conn->prepare("SELECT id, username, name, surname, role FROM member WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    //กรอก username & password ถูกต้อง
    if ($stmt->rowCount() == 1) {
        //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //สร้างตัวแปร session
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
        $_SESSION['role'] = $row['role'];

        $role = $_SESSION['role'];

        //เช็คว่ามีตัวแปร session อะไรบ้าง
        //print_r($_SESSION);

        // exit();

        if ($role == "admin") {
            echo "<script>location.href = './admin/admin.php'</script>";
        } else {
            // echo $_SESSION;
             echo "<script>location.href = './admin/admin.php'</script>";
        }
    } else { //ถ้า username or password ไม่ถูกต้อง

        echo '<script>
                       
            $(document).ready(function() {
                Swal.fire({
                    title: "เกิดข้อผิดพลาด",
                     text: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                    icon: "warning"
                }, function() {
                    window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                });
            })
                      
                  </script>';
        $conn = null; //close connect db
    } //else
} //isset 
//devbanban.com
?>