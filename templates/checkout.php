<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:page.php?mod=login');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $flat = $_POST['flat'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $country = $_POST['country'];
   $pin_code = $_POST['pin_code'];
   $address = 'No. Rumah '. $flat .', '. $street .', '. $city .', '. $state .', '. $country .' - '. $pin_code;
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Pesanan berhasil dibuat!';
   }else{
      $message[] = 'Keranjang belanja Anda kosong';
   }

}

?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <link rel="icon" type="image/jpg" href="images/favicon.jpg">
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Pesanan Anda</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items = [];
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= 'Rp.'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">Keranjang belanja Anda kosong!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Total Keseluruhan : <span>Rp.<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>Buat Pesanan Anda</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Nama Anda :</span>
            <input type="text" name="name" placeholder="Masukkan nama Anda" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Nomor Telepon :</span>
            <input type="number" name="number" placeholder="Masukkan nomor telepon Anda" class="box" onkeypress="if(this.value.length == 15) return false;" required>
         </div>
         <div class="inputBox">
            <span>Email Anda :</span>
            <input type="email" name="email" placeholder="Masukkan email Anda" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Metode Pembayaran :</span>
            <select name="method" class="box" required>
               <option value="COD">COD</option>
               <option value="Dana">Dana</option>
               <option value="ShopeePay">ShopeePay</option>
               <option value="Bank">Bank</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Alamat Baris 01 :</span>
            <input type="text" name="flat" placeholder="Misal: Nomor Rumah" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Alamat Baris 02 :</span>
            <input type="text" name="street" placeholder="Nama Jalan" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Kota :</span>
            <input type="text" name="city" placeholder="Misal: Jakarta" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Provinsi :</span>
            <input type="text" name="state" placeholder="Misal: DKI Jakarta" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Negara :</span>
            <input type="text" name="country" placeholder="Misal: Indonesia" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Kode Pos :</span>
            <input type="number" name="pin_code" placeholder="Misal: 12345" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Buat Pesanan">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
