<?php
ob_start();
?>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <a href="?mod=home" class="logo">Meranti<span>Jaya</span></a>

      <nav class="navbar">
         <a href="?mod=home">Beranda</a>
         <a href="?mod=order">Histori Pesanan</a>
         <a href="?mod=pesansekarang">Pesan Sekarang</a>
         <a href="?mod=hubungikami">Hubungi kami</a>
      </nav>

      <div class="icons">
         <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="?mod=search"><i class="fas fa-search"></i>Cari</a>
         <a href="?mod=wishlist"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="?mod=cart"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php          
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile["name"]; ?></p>
         <a href="?mod=updateUser" class="btn">Perbarui Profil</a>
         <div class="flex-btn">
            <!-- <a href="user_register.php" class="option-btn">Daftar sekarang</a>
            <a href="user_login.php" class="option-btn">Masuk ke Akun</a> -->
         </div>
         <a href="?mod=logout" class="delete-btn" ;">Keluar</a> 
         <?php
            }else{
         ?>
         <p>Mohon masuk atau mendaftar terlebih dahulu</p>
         <div class="flex-btn">
            <a href="?mod=daftar" class="option-btn">Daftar</a>
            <a href="?mod=login" class="option-btn">Masuk</a>
         </div>
         <?php
            }
         ?>      
         
         
      </div>

   </section>

</header>
<?php
$content = ob_get_contents();
ob_end_clean();

// Fungsi untuk mengganti URL
function replace_urls($content) {
    return preg_replace(
        '/page\.php\?mod=([a-zA-Z0-9_-]+)/', 
        'projektest/$1', 
        $content
    );
}

echo replace_urls($content);
?>
