<?php
if (isset($_GET['mod'])) {
    $mod = $_GET['mod'];

    switch ($mod) {
        case 'home':
            include 'templates/home.php';
            break;

        case 'order':
            include 'templates/orders.php';
            break;

        case 'checkout':
            include 'templates/checkout.php';
            break;

        case 'updateUser':
            include 'update_user.php';
            break;

        case 'pesansekarang':
            include 'shop.php';
            break;

        case 'hubungikami':
            include 'templates/contact.php';
            break;

        case 'wishlist':
            include 'templates/wishlist.php';
            break;

        case 'cart':
            include 'templates/cart.php';
            break;

        case 'short':
            include 'templates/short.php';
            break;

        case 'search':
            include 'templates/search_page.php';
            break;
        case 'quickview':
            include 'templates/quick_view.php';
            break;

        case 'login':
            include 'user_login.php';
            break;

        case 'daftar':
            include 'user_register.php';
            break;

        case 'homeadmin':
            include "admin/dashboard.php";
            break;

        case 'akun':
            include "admin/admin_accounts.php";
            break;

        case 'pesan':
            include "admin/messages.php";
            break;

        case 'produk':
            include "admin/products.php";
            break;

        case 'pengguna':
            include "admin/users_accounts.php";
            break;

        case 'profil':
            include "admin/update_profile.php";
            break;

        case 'pesanan':
            include "admin/placed_orders.php";
            break;

        case 'updateproduk':
            include "admin/update_product.php";
            break;

        case 'aksi':
            include "admin/aksi.php";
            break;

        case 'tambahadmin':
            include "admin/register_admin.php";
            break;

        case 'logout':
            include "user_logout.php";
            break;
            
        default:
            echo "Halaman tidak ditemukan.";
            break;
    }
} else {
    echo "Halaman tidak ditemukan.";
}
?>
