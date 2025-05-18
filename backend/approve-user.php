<?php
//GET ile gelen 'id' parametresi varsa ve bu değer sayısal bir değer ise kontrol edilir
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    //'id' parametresi tam sayıya dönüştürülerek güvenlik sağlanır (type casting)
    $id = (int)$_GET['id'];

    try {
        //PDO kullanarak veritabanına bağlanılır (localhost sunucusunda 'project' adlı veritabanı)
        $pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");
        //Hataları yakalayabilmek için PDO hata modu 'Exception' olarak ayarlanır
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Kullanıcının onay durumunu güncelleyecek SQL sorgusu hazırlanır (is_approved = 1 yapılır)
        $stmt = $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
        //Hazırlanan sorgu çalıştırılır ve id parametresi bağlanarak SQL enjeksiyonu önlenir
        $stmt->execute([$id]);

        http_response_code(200); //HTTP yanıt kodu 200 olarak ayarlanır (işlem başarılı anlamına gelir)
    } catch (PDOException $e) {
        //Eğer veritabanı bağlantısı ya da sorgu sırasında bir hata olursa:
        //HTTP yanıt kodu 500 olarak ayarlanır (sunucu taraflı hata)
        http_response_code(500);
        echo "Veritabanı hatası: " . $e->getMessage(); //Hata mesajı ekrana yazdırılır
    }
} else {
    //Eğer 'id' parametresi gelmemişse veya geçerli bir sayı değilse:
    //HTTP yanıt kodu 400 olarak ayarlanır (kötü istek anlamına gelir)
    http_response_code(400);
    //Kullanıcıya geçersiz ID mesajı gösterilir
    echo "Geçersiz kullanıcı ID.";
}
?>