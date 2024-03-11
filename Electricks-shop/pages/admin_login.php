<?php
    session_start();
    include('../config/dbconn.php');
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
        $user_unsafe = $_POST['username'];
        $pass_unsafe = $_POST['password'];

        $user = mysqli_real_escape_string($dbconn, $user_unsafe);
        $pass = mysqli_real_escape_string($dbconn, $pass_unsafe);

        $query = mysqli_query($dbconn, "SELECT * FROM `admin` WHERE username='$user' AND password='$pass'");
        $res = mysqli_fetch_array($query);
        $id = $res['user_id'];

        if (mysqli_num_rows($query) < 1) {
            $_SESSION['msg'] = "Login Failed, Admin not found!";
            header('Location:admin_login_page.php');
        } else {
            $res = mysqli_fetch_array($query);
            $_SESSION['id'] = $res['user_id'];
            header('Location: admin_index.php');
            
            $_SESSION['id'] = $id;
        }
    }
?>