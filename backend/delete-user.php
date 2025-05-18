<?php
//URL üzerinden gelen 'id' parametresi alınır (silinmek istenen kullanıcının ID'si)
$id = $_GET['id'];

//PDO kullanarak MySQL veritabanına bağlanılır
//localhost üzerinde 'project' adlı veritabanına, 'root' kullanıcısı ve şifresiz bağlantı yapılır
//Ayrıca karakter seti olarak UTF-8 kullanılır
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//Kullanıcıyı silmek için SQL sorgusu hazırlanır
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");

//Hazırlanan sorgu çalıştırılır ve id değeri parametre olarak verilir
$stmt->execute([$id]);
?>