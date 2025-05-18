<?php
session_start(); //Oturum başlatılır. Kullanıcı oturum bilgilerini saklamak için gereklidir.

$host = "localhost"; //Veritabanı sunucusunun adresi
$dbname = "project"; //Kullanılacak veritabanının adı
$username = "root"; //Veritabanı kullanıcı adı
$password = ""; //Veritabanı şifresi

try {
    //PDO ile veritabanına bağlantı kurulur. UTF-8 karakter seti kullanılır.
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //Hata ayıklama modu aktif hale getirilir. Hata olduğunda istisna (exception) fırlatılır.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Kullanıcının giriş formundan gönderdiği email ve şifre alınır
    $email = $_POST['email'];
    $pass = $_POST['password'];

    //Email'e göre kullanıcıyı sorgulayan SQL sorgusu hazırlanır
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    //Sorgu çalıştırılır, kullanıcının girdiği email veritabanında aranır
    $stmt->execute([$email]);
    //Kullanıcı bilgisi bir dizi olarak alınır
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //Eğer kullanıcı bulunduysa ve girilen şifre hash ile eşleşiyorsa
    if ($user && password_verify($pass, $user['password'])) {
        //Eğer kullanıcı henüz yönetici tarafından onaylanmamışsa
        if ($user['is_approved'] == 0) {
            echo "Hesabınız henüz yönetici tarafından onaylanmadı.";
        } else {
            //Kullanıcının ID ve rol bilgisi oturum değişkenlerine atanır
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            //Eğer kullanıcıdan şifre değiştirmesi istenmişse yönlendirme yapılır
            if ($user['must_change_password'] == 1) {
                header("Location: ../frontend/change-password.html"); // Şifre değiştirme sayfasına yönlendirilir
                exit(); //Kodun devam etmesi engellenir
            } else {
                //Kullanıcı onaylı ve şifre değiştirmesi gerekmiyorsa
                header("Location: ../frontend/interest.html");
                exit(); //Yönlendirmeden sonra kod çalışmasın
            }
        }
    } else {
        //Kullanıcı yoksa veya şifre yanlışsa uyarı mesajı gösterilir
        echo "Geçersiz e-posta veya şifre!";
    }

} catch (PDOException $e) {
    //Bağlantı veya sorgu sırasında bir hata olursa, hata mesajı yazdırılır
    echo "Hata: " . $e->getMessage();
}
?>