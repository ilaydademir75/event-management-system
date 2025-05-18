<?php
//Oturum başlatılır (kullanıcının kimliğini takip edebilmek için session gerekir)
session_start();

//Eğer kullanıcı oturumu açık değilse (yani user_id oturumda yoksa), yetkisiz erişim uyarısı verilir ve işlem sonlandırılır
if (!isset($_SESSION['user_id'])) {
    die("Yetkisiz erişim.");
}

//Veritabanı bağlantı bilgileri tanımlanır
$host = "localhost"; //Sunucu adı
$dbname = "project"; //Veritabanı adı
$username = "root"; //Veritabanı kullanıcı adı
$password = ""; //Veritabanı şifresi

//PDO kullanılarak veritabanına bağlanma denenir
try {
    //PDO nesnesi oluşturulup bağlantı yapılır, karakter seti utf8 olarak ayarlanır
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //Hataların yakalanabilmesi için PDO hata modu "Exception" olarak ayarlanır
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Yeni şifre, güvenlik açısından password_hash fonksiyonu ile hash'lenir (şifrelenir)
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    //Oturumdan kullanıcı ID'si alınır
    $userId = $_SESSION['user_id'];

    //SQL sorgusu hazırlanır: Kullanıcının şifresi güncellenir ve 'must_change_password' 0 yapılır (şifre artık değiştirilmeyecek)
    $stmt = $conn->prepare("UPDATE users SET password = ?, must_change_password = 0 WHERE id = ?");
    //Sorgu çalıştırılır, '?' yerlerine sırasıyla yeni şifre ve kullanıcı ID’si yerleştirilir
    $stmt->execute([$newPassword, $userId]);

    //Şifre başarıyla değiştirildikten sonra kullanıcı ilgi alanlarını seçeceği sayfaya yönlendirilir
    header("Location: ../frontend/interest.html");
    exit(); //Yönlendirmeden sonra kodun devam etmesini engellemek için çıkış yapılır

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage(); //Eğer veritabanı bağlantısı ya da sorgu sırasında bir hata olursa ekrana hata mesajı yazdırılır
}
?>