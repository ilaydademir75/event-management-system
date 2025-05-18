<?php
session_start(); //Kullanıcı oturumu başlatılır
//İstemciden gelen JSON verisi alınır ve diziye dönüştürülür
$data = json_decode(file_get_contents("php://input"), true);

//Oturumdan giriş yapmış kullanıcının ID'si alınır
$userId = $_SESSION['user_id'] ?? null; //Eğer oturumda yoksa null olur

//Eğer kullanıcı oturum açmamışsa, yetkisiz hatası verilir ve işlem durdurulur
if (!$userId) {
    http_response_code(401); //HTTP 401 Yetkisiz erişim
    echo json_encode(["error" => "Kullanıcı oturumda değil."]); //Hata mesajı
    exit; //Kodun çalışmasını sonlandır
}

//Gönderilen veri içerisinde "interests" anahtarı yoksa veya bu bir dizi değilse hata döndürülür
if (!isset($data['interests']) || !is_array($data['interests'])) {
    http_response_code(400); //HTTP 400 Geçersiz istek
    echo json_encode(["error" => "İlgi alanları gönderilmedi."]); //Hata mesajı
    exit; //Kodun çalışmasını sonlandır
}

try {
    //PDO ile MySQL veritabanına bağlantı kurulur
    $pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

    //Önceki ilgi alanı kayıtları silinir (her kayıt için aynı kullanıcıya ait olanları)
    $stmt = $pdo->prepare("DELETE FROM user_interests WHERE user_id = ?");
    $stmt->execute([$userId]);

    //Yeni ilgi alanlarını eklemek için bir hazırlık sorgusu oluşturulur
    $stmt = $pdo->prepare("INSERT INTO user_interests (user_id, interest) VALUES (?, ?)");

    //Her bir ilgi alanı için döngü kurularak kullanıcıya ait olarak veritabanına eklenir
    foreach ($data['interests'] as $interest) {
        $stmt->execute([$userId, $interest]); //Kullanıcı ID'si ve ilgi alanı veritabanına yazılır
    }

    echo json_encode(["success" => true]); //İşlem başarılıysa başarı mesajı gönderilir
} catch (Exception $e) {
    //Veritabanı bağlantısı ya da SQL sorgusu sırasında bir hata olursa buraya düşer

    http_response_code(500); //HTTP 500 Sunucu Hatası
    echo json_encode(["error" => "Veritabanı hatası: " . $e->getMessage()]); //Hata mesajı gönderilir
}
?>