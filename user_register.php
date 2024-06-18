<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = sha1($_POST['pass']); // Disarankan untuk menggunakan hashing yang lebih kuat seperti bcrypt
    $cpass = sha1($_POST['cpass']);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message[] = 'Email sudah terdaftar';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Konfirmasi Password Tidak Sama';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
            $insert_user->execute([$name, $email, $cpass]);
            $message[] = 'Pendaftaran Berhasil, Silahkan Login';
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
   <title>Daftar</title>
   
   <link rel="icon" type="image/jpg" href="images/favicon.jpg">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Buat Akun Baru</h3>
      <input type="text" name="name" required placeholder="Masukkan username Anda" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="Masukkan email Anda" maxlength="50" class="box">
      <input type="password" name="pass" required placeholder="Masukkan password Anda" maxlength="20" class="box">
      <input type="password" name="cpass" required placeholder="Konfirmasi password Anda" maxlength="20" class="box">
      <input type="submit" value="Daftar Sekarang" class="btn" name="submit">
      <p>Sudah punya Akun?</p>
      <a href="user_login.php" class="option-btn">Login Sekarang</a>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
