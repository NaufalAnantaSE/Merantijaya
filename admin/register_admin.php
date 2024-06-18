<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:user_login.php');
}

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $pass = sha1($_POST['pass']);
   $cpass = sha1($_POST['cpass']);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
   $select_admin->execute([$name]);

   if($select_admin->rowCount() > 0){
      $message[] = 'Username sudah terdaftar';
   } else {
      if($pass != $cpass){
         $message[] = 'Konfirmasi password tidak sama';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admins` (name, password) VALUES (?, ?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'Admin baru berhasil ditambahkan';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Admin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Tambah Sekarang</h3>
      <input type="text" name="name" required placeholder="Masukkan username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Masukkan password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Konfirmasi password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Daftar" class="btn" name="submit">
   </form>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>
