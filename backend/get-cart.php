<?php
//Kullanıcı oturumu başlatılır (veya mevcut oturum devam ettirilir)
session_start();

//PDO kullanılarak MySQL veritabanına bağlantı kurulur
//'localhost' sunucusundaki 'project' adlı veritabanına 'root' kullanıcısı ile bağlanılır
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//Oturumda daha önce sepete eklenmiş etkinlik ID’leri var mı diye kontrol edilir
//Varsa $cart değişkenine atanır, yoksa boş bir dizi atanır
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

//Sorguda kullanılacak kadar '?' işareti oluşturulur
//Örneğin 3 etkinlik varsa: '?, ?, ?' gibi bir ifade oluşur
$placeholders = implode(',', array_fill(0, count($cart), '?'));

//Eğer sepette etkinlik varsa
if ($placeholders) {
  //Hazırlanmış bir SQL sorgusu oluşturulur: "SELECT * FROM events WHERE id IN (?, ?, ...)"
  //Bu sorgu, sepetteki etkinlik ID'lerine karşılık gelen etkinlikleri getirir
  $stmt = $pdo->prepare("SELECT * FROM events WHERE id IN ($placeholders)");
  //Sepetteki ID'ler kullanılarak sorgu çalıştırılır
  $stmt->execute($cart);
  //Sonuçlar JSON formatına dönüştürülerek ekrana yazdırılır
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
  echo json_encode([]); //Eğer sepette hiç etkinlik yoksa, boş bir JSON dizisi döndürülür
}
?>