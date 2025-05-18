<?php
//URL üzerinden gelen 'id' parametresi alınır (silinmek istenen etkinliğin ID'si)
$id = $_GET['id'];

//Veritabanına PDO kullanılarak bağlanılır (localhost sunucusunda 'project' adlı veritabanı, root kullanıcı, şifresiz)
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//SQL komutu ile belirtilen ID'ye sahip etkinlik 'events' tablosundan silinir
$pdo->exec("DELETE FROM events WHERE id = $id");
?>