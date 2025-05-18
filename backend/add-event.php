<?php
//JSON formatında gelen POST verisini alır ve PHP dizisine çevirir
$data = json_decode(file_get_contents("php://input"), true);

//JSON'dan alınan veriler değişkenlere atanır
$title = $data['title']; //Etkinlik başlığı
$date = $data['date']; //Etkinlik tarihi
$category = $data['category']; //Etkinlik kategorisi
$location = $data['location']; //Etkinlik yeri
$quota = $data['quota']; //Etkinlik kontenjanı (sayı olarak)

//Veritabanı bağlantısı kurulur (localhost, kullanıcı: root, şifre: boş, veritabanı: project)
$baglanti = new mysqli("localhost", "root", "", "project");

//Bağlantı hatası varsa işlem durdurulur ve hata mesajı gösterilir
if ($baglanti->connect_error) {
    die("Bağlantı hatası: " . $baglanti->connect_error);
}

//Etkinliği veritabanındaki 'events' tablosuna ekleyecek SQL sorgusu hazırlanır
$sql = "INSERT INTO events (title, event_date, category, location, quota) 
        VALUES (?, ?, ?, ?, ?)";

//SQL sorgusunu çalıştırmak için prepare (hazırla) fonksiyonu kullanılır 
$stmt = $baglanti->prepare($sql);

//Parametreler sorguya bağlanır (sırasıyla: string, string, string, string, integer)
//"ssssi" => s: string, i: integer
$stmt->bind_param("ssssi", $title, $date, $category, $location, $quota);

if ($stmt->execute()) {
    echo json_encode(["success" => true]); //Eğer başarıyla çalıştıysa JSON olarak başarı mesajı gönderilir
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]); //Hata varsa, hem başarısız olduğu hem de hata mesajı JSON formatında gönderilir
}

//Bellek sızıntısını önlemek için sorgu nesnesi kapatılır
$stmt->close();
//Veritabanı bağlantısı kapatılır
$baglanti->close();
?>