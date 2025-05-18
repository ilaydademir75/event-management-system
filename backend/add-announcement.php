<?php
//Yanıtın JSON formatında ve UTF-8 karakter seti ile döneceğini belirtir
header('Content-Type: application/json; charset=utf-8');

//Frontend'den gelen JSON verisini alır ve diziye dönüştürür
$data = json_decode(file_get_contents("php://input"), true);

//PDO (PHP Data Objects) ile MySQL veritabanına bağlantı kuruluyor
//localhost üzerinde 'project' isimli veritabanına 'root' kullanıcı adıyla bağlanılıyor, şifre boş
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//Veritabanı bağlantısı için karakter setini UTF-8 olarak ayarlıyor (emoji ve özel karakterler için utf8mb4)
$pdo->exec("SET NAMES utf8mb4");

//'announcements' tablosuna yeni bir 'text' verisi eklenecek
$stmt = $pdo->prepare("INSERT INTO announcements (text) VALUES (?)");

//Hazırlanan sorguya, kullanıcıdan gelen 'text' verisini parametre olarak göndererek sorguyu çalıştırıyor
$stmt->execute([$data['text']]);

//JSON formatında başarılı olduğuna dair bir yanıt döndürüyor
echo json_encode(["success" => true]);
?>