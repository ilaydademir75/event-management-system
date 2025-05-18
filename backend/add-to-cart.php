<?php
//Oturum (session) başlatılır. Kullanıcıya özel verileri saklamak için kullanılır.
session_start();

//URL üzerinden gelen 'id' parametresi alınır (sepete eklenecek etkinlik ID'si)
$id = $_GET['id'];

//Eğer 'cart' adında bir oturum değişkeni daha önce oluşturulmamışsa, boş bir dizi olarak başlatılır
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

//Alınan ID sepete (session içindeki cart dizisine) eklenir
$_SESSION['cart'][] = $id;
?>