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

      <a href="?mod=homeadmin" class="logo">Admin<span> Meranti Jaya</span></a>

      <nav class="navbar">
         <a href="?mod=homeadmin">beranda</a>
         <a href="?mod=produk">Produk</a>
         <a href="?mod=pesanan">Pesanan</a>
         <a href="?mod=akun">Admin</a>
         <a href="?mod=pengguna">Pengguna</a>
         <a href="?mod=pesan">Pesan</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         include 'components/connect.php';
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="?mod=profil" class="btn">Update Profile</a>
         <div class="flex-btn">

         </div>
         <a href="?mod=logout" class="delete-btn" onclick="return confirm('logout dari website?');">logout</a> 
      </div>

   </section>

</header>
