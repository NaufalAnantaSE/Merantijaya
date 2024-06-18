<?php
include 'components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:user_login.php');
   exit;
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'Status pembayaran Diperbarui';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesanan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="orders">

<h1 class="heading">Orderan</h1>

<div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Diorder Pada : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Nama : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Nomor : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Alamat : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total Produk : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Harga : <span>Nrs.<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Metode Pembayaran : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="select">
            <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Selesai</option>
         </select>
        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_payment">
         <!-- Mengubah tautan delete untuk mengarahkan ke aksi.php dengan modul dan parameter delete yang tepat -->
         <a href="?mod=aksi&delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Hapus Pesanan ini?');">HAPUS</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Belum Ada orderan</p>';
      }
   ?>

</div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
