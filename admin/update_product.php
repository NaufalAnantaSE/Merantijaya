<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:user_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = :id");
   $delete_product->bindParam(':id', $delete_id, PDO::PARAM_INT);
   $delete_product->execute();

   if ($delete_product->rowCount() > 0) {
      echo "<script>alert('Produk berhasil dihapus');</script>";
   } else {
      echo "<script>alert('Gagal menghapus produk');</script>";
   }

   // Redirect to the same page without 'delete' parameter
   header("Location: ?mod=produk");
   exit;
}

if(isset($_POST['update'])){
   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $details = $_POST['details'];

   // Update product details
   $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ? WHERE id = ?");
   $update_product->execute([$name, $price, $details, $pid]);

   $message[] = 'Produk berhasil diperbarui';

   // Handle image uploads
   $image_folder = 'uploaded_img/';

   // Function to handle file upload and update database
   function handleFileUpload($fileKey, $imageName, $oldImageName, $productId, $conn) {
      $image_name = $_FILES[$fileKey]['name'];
      $image_size = $_FILES[$fileKey]['size'];
      $image_tmp_name = $_FILES[$fileKey]['tmp_name'];
      $image_folder = 'uploaded_img/'.$image_name;

      if(!empty($image_name)){
         if($image_size > 2000000){
            return 'Ukuran gambar terlalu besar';
         } else {
            $update_image = $conn->prepare("UPDATE `products` SET $imageName = ? WHERE id = ?");
            $update_image->execute([$image_name, $productId]);
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$oldImageName);
            return 'Gambar berhasil diunggah';
         }
      }
      return null; // If no image is uploaded
   }

   // Update image_01
   $message[] = handleFileUpload('image_01', 'image_01', $_POST['old_image_01'], $pid, $conn);

   // Update image_02
   $message[] = handleFileUpload('image_02', 'image_02', $_POST['old_image_02'], $pid, $conn);

   // Update image_03
   $message[] = handleFileUpload('image_03', 'image_03', $_POST['old_image_03'], $pid, $conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Produk</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="update-product">
   <h1 class="heading">Update Produk</h1>

   <?php
   $update_id = $_GET['update'];
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $select_products->execute([$update_id]);

   if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
      <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
      <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">
      <div class="image-container">
         <div class="main-image">
            <img src="uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
         </div>
         <div class="sub-image">
            <img src="uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <img src="uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
            <img src="uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
         </div>
      </div>
      <span>Ubah Nama</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="Masukkan nama produk" value="<?= $fetch_products['name']; ?>">
      <span>Ubah Harga</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="Masukkan harga produk" value="<?= $fetch_products['price']; ?>">
      <span>Ubah Detail</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <span>Ubah gambar 01</span>
      <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <span>Ubah gambar 02</span>
      <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <span>Ubah gambar 03</span>
      <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="Ubah Sekarang">
         <a href="?mod=produk" class="option-btn">Kembali</a>
      </div>
   </form>
   <?php
      }
   } else {
      echo '<p class="empty">Produk tidak ditemukan</p>';
   }
   ?>

</section>

<script src="js/admin_script.js"></script>
</body>
</html>
