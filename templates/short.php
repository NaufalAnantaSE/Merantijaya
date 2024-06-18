<?php
// Fungsi bubble sort yang memperhatikan case-insensitive
function bubbleSort(&$array, $key, $order = 'ASC') {
    $n = count($array);
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($order == 'ASC') {
                if ((is_numeric($array[$j][$key]) && is_numeric($array[$j + 1][$key]) && $array[$j][$key] > $array[$j + 1][$key]) ||
                    (!is_numeric($array[$j][$key]) && !is_numeric($array[$j + 1][$key]) && strcasecmp($array[$j][$key], $array[$j + 1][$key]) > 0)) {
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            } else {
                if ((is_numeric($array[$j][$key]) && is_numeric($array[$j + 1][$key]) && $array[$j][$key] < $array[$j + 1][$key]) ||
                    (!is_numeric($array[$j][$key]) && !is_numeric($array[$j + 1][$key]) && strcasecmp($array[$j][$key], $array[$j + 1][$key]) < 0)) {
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            }
        }
    }
}

include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

include 'components/wishlist_cart.php';

// Menentukan urutan berdasarkan parameter 'short'
$short = isset($_GET['short']) ? $_GET['short'] : 'termurahketermahal';

// Mengambil semua produk dari tabel products
$select_products = $conn->prepare("SELECT * FROM `products`"); 
$select_products->execute();

$products = [];
if($select_products->rowCount() > 0){
    while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
        $products[] = $fetch_product;
    }
}

switch($short) {
    case 'termahalketermurah':
        bubbleSort($products, 'price', 'DESC');
        break;
    case 'dariakez':
        bubbleSort($products, 'name', 'ASC');
        break;
    case 'darizkea':
        bubbleSort($products, 'name', 'DESC');
        break;
    case 'dariterbaruketerlama':
        bubbleSort($products, 'created_at', 'DESC'); // Anda harus memiliki kolom `created_at` di tabel products
        break;
    case 'termurahketermahal':
    default:
        bubbleSort($products, 'price', 'ASC');
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pengurutan Produk</title>
   
   <link rel="icon" type="image/jpg" href="images\favicon.jpg">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">Urutkan Produk</h1>

   <div class="box-container">

   <?php
     if(count($products) > 0){
        foreach($products as $product){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $product['id']; ?>">
      <input type="hidden" name="name" value="<?= $product['name']; ?>">
      <input type="hidden" name="price" value="<?= $product['price']; ?>">
      <input type="hidden" name="image" value="<?= $product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="?mod=quickview&pid=<?= $product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $product['image_01']; ?>" alt="">
      <div class="name"><?= $product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>Rp.</span><?= $product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Tambah Ke keranjang" class="btn" name="add_to_cart">
   </form>
   <?php
        }
     } else {
        echo '<p class="empty">tidak ada produk yang di temukan</p>';
     }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
