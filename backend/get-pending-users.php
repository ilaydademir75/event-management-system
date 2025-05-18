<?php
//Veritabanına bağlanılır: localhost üzerinde "project" adlı veritabanı, "root" kullanıcı adıyla şifresiz bağlanılır.
$baglanti = new mysqli("localhost", "root", "", "project");

//Bağlantı hatası kontrolü yapılır. Hata varsa işlem durdurulur ve hata mesajı gösterilir.
if ($baglanti->connect_error) {
    die("Bağlantı hatası: " . $baglanti->connect_error);
}

//Henüz onaylanmamış (is_approved = 0) kullanıcıları ID ve email bilgileriyle seçen SQL sorgusu.
$sql = "SELECT id, email FROM users WHERE is_approved = 0";
//Sorgu çalıştırılır ve sonuç $sonuc değişkenine atanır.
$sonuc = $baglanti->query($sql);

//Onay bekleyen kullanıcıları tutmak için boş bir dizi oluşturulur.
$kullanicilar = [];

//Sorgudan dönen her satır (her kullanıcı) alınır ve $kullanicilar dizisine eklenir.
while ($satir = $sonuc->fetch_assoc()) {
    $kullanicilar[] = $satir; //fetch_assoc(), sonuçları ilişkisel dizi olarak döndürür
}

header('Content-Type: application/json'); //Tarayıcıya içerik türünün JSON olduğunu belirtir.
echo json_encode($kullanicilar); //PHP dizisini JSON formatına çevirip çıktı olarak gönderir.
?>