<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:user_login.php');
}

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $price = $_POST['price'];
   $details = $_POST['details'];

   // Sanitasi input tidak diperlukan untuk name, price, dan details karena tidak akan dimasukkan langsung ke dalam query

   $image_01 = $_FILES['image_01']['name'];
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = 'uploaded_img/' . $image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = 'uploaded_img/' . $image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = 'uploaded_img/' . $image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Nama produk yang dimasukkan sudah ada';
   } else {

      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_size_01 > 2000000 || $image_size_02 > 2000000 || $image_size_03 > 2000000){
            $message[] = 'Ukuran file terlalu besar';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'Produk berhasil ditambahkan!';
         }
      }

   }  

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Produk</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>
<section class="add-products">
   <h1 class="heading">Tambahkan Produk</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Nama Produk (wajib)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Masukkan nama produk" name="name">
         </div>
         <div class="inputBox">
            <span>Harga (wajib)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Masukkan harga" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>Foto 1 (wajib)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Foto 2 (opsional)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
         </div>
         <div class="inputBox">
            <span>Foto 3 (opsional)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
         </div>
         <div class="inputBox">
            <span>Deskripsi Produk (Wajib)</span>
            <textarea name="details" placeholder="Masukkan detail produk" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      <input type="submit" value="Posting Produk" class="btn" name="add_product">
   </form>
</section>

<section class="show-products">
   <h1 class="heading">Produk yang Sudah Ditambahkan</h1>
   <div class="box-container">
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
      ?>
      <div class="box">
         <img src="uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="price">Rp. <span><?= $fetch_products['price']; ?></span>/-</div>
         <div class="details"><span><?= $fetch_products['details']; ?></span></div>
         <div class="flex-btn">
            <a href="?mod=updateproduk&update=<?= $fetch_products['id']; ?>" class="option-btn">Ubah</a>
            <a href="?mod=updateproduk&delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Yakin akan menghapus produk?');">HAPUS</a>
         </div>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">Belum ada produk yang ditambahkan</p>';
      }
      ?>
   </div>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>
