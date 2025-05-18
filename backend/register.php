<?php
$host = "localhost"; //Sunucu adı
$dbname = "project"; //Kullanılacak veritabanı adı
$username = "root"; //Veritabanı kullanıcı adı
$password = ""; //Veritabanı şifresi

try {
    //PDO kullanılarak MySQL veritabanına bağlantı kurulur
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //Hataları istisna (exception) olarak fırlatması sağlanır
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Kullanıcının HTML formundan gönderdiği veriler alınır
    $email = $_POST['email']; //Kullanıcının e-posta adresi
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); //Şifre güvenli hale getirilmesi için hashlenir (şifreleme yapılmaz, tek yönlüdür)

    //Kullanıcıyı "users" tablosuna ekleyen SQL sorgusu
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    //SQL sorgusu hazırlanır
    $stmt = $conn->prepare($sql);
    //Hazırlanan sorguya kullanıcının verileri bağlanır ve çalıştırılır
    $stmt->execute([$email, $pass]);

    echo "Kayıt başarılı! Yönetici onayı bekleniyor."; //Kullanıcıya başarı mesajı verilir
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage(); //Eğer bir hata oluşursa, hata ekrana bastırılır
}
?>