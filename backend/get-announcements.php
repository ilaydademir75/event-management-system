<?php
//PDO (PHP Data Objects) kullanılarak MySQL veritabanına bağlanılır
//localhost üzerinde 'project' veritabanına, kullanıcı adı 'root' ve boş şifre ile bağlanılılır
//Bağlantı sırasında karakter seti UTF-8 olarak ayarlanır
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//Veritabanındaki tüm duyurular (announcements tablosundaki verileri) id'ye göre azalan sırayla (yeni duyurular en üstte olacak şekilde) sorgulanır
$stmt = $pdo->query("SELECT * FROM announcements ORDER BY id DESC");

//Tarayıcıya gönderilecek cevabın türünü JSON ve karakter setini UTF-8 olarak belirtir
header('Content-Type: application/json; charset=utf-8');

//Sorgudan dönen tüm veriler dizi olarak alınır (her satır bir dizi olur, sütunlar anahtar olarak alınır)
//JSON formatına dönüştürürken Türkçe karakterlerin bozulmaması için JSON_UNESCAPED_UNICODE parametresi kullanılır
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
?>