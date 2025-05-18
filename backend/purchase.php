<?php
session_start(); //Oturumu başlatılır. Sepet bilgisi gibi kullanıcıya özel verileri saklamak için gereklidir.

//İstemciden gelen JSON verisi alınır ve diziye dönüştürülür
$data = json_decode(file_get_contents("php://input"), true);
//JSON verisinden 'tickets' adlı anahtar altındaki bilet bilgileri alınır
$tickets = $data['tickets'];
//Veritabanına PDO kullanarak bağlanılır (UTF-8 karakter seti ile)
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//Her bilet için işlemler yapılır
foreach ($tickets as $ticket) {
  $eventId = $ticket['id']; //İlgili etkinliğin ID'si alınır

  //Etkinliğin kontenjanı 1 azaltılır. Ancak kontenjan 0'dan büyükse işlem yapılır.
  $pdo->exec("UPDATE events SET quota = quota - 1 WHERE id = $eventId AND quota > 0");
}

//Tüm işlemler tamamlandıktan sonra kullanıcının sepeti temizlenir
unset($_SESSION['cart']);
?>