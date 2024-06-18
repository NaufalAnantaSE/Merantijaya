<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $msg = $_POST['msg'];

   // Validate the length of the phone number
   if(strlen($number) < 9 || strlen($number) > 15){
      $message[] = 'Nomor telepon harus antara 9 sampai 15 digit.';
   } else {
      $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
      $select_message->execute([$name, $email, $number, $msg]);

      if($select_message->rowCount() > 0){
         $message[] = 'Pesan sudah pernah dikirim!';
      } else {
         $insert_message = $conn->prepare("INSERT INTO `messages` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
         $insert_message->execute([$user_id, $name, $email, $number, $msg]);

         $message[] = 'Pesan berhasil dikirim!';
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
   <title>Contact</title>

   <link rel="icon" type="image/jpg" href="images/favicon.jpg">
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form action="" method="post" onsubmit="return validateForm()">
      <h3>Isi form untuk kami hubungi</h3>
      <input type="text" name="name" placeholder="Nama (wajib)" required maxlength="20" class="box">
      <input type="email" name="email" placeholder="Email aktif" maxlength="50" class="box">
      <input type="text" name="number" placeholder="Nomor yang dapat dihubungi (wajib)" required minlength="9" maxlength="15" class="box" oninput="validateLength(this)">
      <textarea name="msg" class="box" placeholder="Masukkan pesan atau pertanyaan Anda" cols="30" rows="10"></textarea>
      <input type="submit" value="Kirim Pesan" name="send" class="btn">
   </form>

</section>

<script>
   function validateLength(input) {
      if (input.value.length < 9) {
         input.setCustomValidity('Nomor telepon harus minimal 9 digit');
      } else if (input.value.length > 15) {
         input.setCustomValidity('Nomor telepon harus maksimal 15 digit');
      } else {
         input.setCustomValidity('');
      }
   }

   function validateForm() {
      var number = document.querySelector('input[name="number"]').value;
      if (number.length < 9 || number.length > 15) {
         alert('Nomor telepon harus antara 9 dan 15 digit.');
         return false;
      }
      return true;
   }
</script>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
