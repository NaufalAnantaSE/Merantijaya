<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_POST['submit'])) {

   if (isset($_POST['name']) && isset($_POST['pass'])) {
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $pass = sha1($_POST['pass']);
      $pass = filter_var($pass, FILTER_SANITIZE_STRING);

      // Cek pengguna di tabel users
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE name = ? AND password = ?");
      $select_user->execute([$name, $pass]);
      $row_user = $select_user->fetch(PDO::FETCH_ASSOC);

      // Jika ditemukan di tabel users
      if ($select_user->rowCount() > 0) {
         $_SESSION['user_id'] = $row_user['id'];
         header('location:page.php?mod=home');
         exit;
      } else  {
         $name = $_POST['name'];
         $name = filter_var($name, FILTER_SANITIZE_STRING);
         $pass = sha1($_POST['pass']);
         $pass = filter_var($pass, FILTER_SANITIZE_STRING);
         // Cek admin di tabel admins
         $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
         $select_admin->execute([$name, $pass]);
         $row_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

         if ($select_admin->rowCount() > 0) {
            $_SESSION['admin_id'] = $row_admin['id'];
            header('location:?mod=homeadmin');
            exit;
         } else {
            $message[] = 'username atau password salah';
         }
      }
   } else {
      $message[] = 'mohon lengkapi data';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="icon" type="image/jpg" href="images\favicon.jpg">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>Login Sekarang</h3>
         <input type="text" name="name" required placeholder="enter your name" maxlength="50" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="login now" class="btn" name="submit">
         <p>Belum Punya Akun?</p>
         <a href="user_register.php" class="option-btn">Daftar Sekarang</a>
      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
