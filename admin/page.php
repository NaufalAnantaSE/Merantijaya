<?php

if (isset($_GET['mod'])) {
    $mod = $_GET['mod'];

    switch ($mod) {
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

        case "profil":
            include "admin/update_profile.php";
            break;
        case "pesanan":
            include "admin/placed_orders.php";
            break;

        case 'logout':
            session_start();
            session_unset();
            session_destroy();
            include('index.php');
            exit();


        default:
            echo "Halaman tidak ditemukan.";
            break;
    }
} else {
    echo "Halaman tidak ditemukan.";
}

?>