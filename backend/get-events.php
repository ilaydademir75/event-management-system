<?php
//Oturum başlatılır. Bu sayede oturum verilerine (örneğin user_id) erişilebilir.
session_start();

//PDO ile MySQL veritabanına bağlantı kurulur.
//'project' adlı veritabanına, 'localhost' sunucusundan 'root' kullanıcısı ile bağlanılır.
$pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8", "root", "");

//Oturumda giriş yapmış kullanıcı varsa user_id alınır, yoksa $userId değişkeni null olur.
$userId = $_SESSION['user_id'] ?? null;

//Kullanıcının ilgi alanlarını tutacak dizi tanımlanır.
$interests = [];

//Kullanıcı giriş yaptıysa (yani $userId boş değilse), veritabanından ilgi alanları alınır.
if ($userId) {
    //user_interests tablosundan ilgili kullanıcıya ait ilgi alanları çekilir.
    $stmt = $pdo->prepare("SELECT interest FROM user_interests WHERE user_id = ?");
    $stmt->execute([$userId]);
    //Sadece 'interest' sütunu alınır ve $interests dizisine atanır.
    $interests = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

//Kullanıcının ilgisine göre öneri yapılabilmesi için ana kategorilere alt kategori eşleştirmeleri yapılır.
$categoryMap = [
    "müzik" => ["müzik", "rock", "pop", "klasik", "caz", "konser"],
    "spor" => ["spor", "futbol", "basketbol", "voleybol"],
    "tiyatro" => ["tiyatro", "drama", "komedi", "oyun"],
    "teknoloji" => ["teknoloji", "yapay zeka", "robotik", "programlama"],
    "sinema" => ["sinema", "film", "belgesel", "korku", "aksiyon"]
];

//events tablosundaki tüm etkinlikler çekilir.
$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC); //Sonuçlar ilişkisel dizi olarak alınır.

//Her etkinliğe "recommended" (önerilen) alanı eklenir.
foreach ($events as &$event) {
    //Etkinliğin kategorisi küçük harfe çevrilerek karşılaştırmalara uygun hale getirilir.
    $eventCategory = strtolower($event['category']);
    //Başlangıçta bu etkinliğin önerilmediği varsayılır.
    $isRecommended = false;

    //Kullanıcının her bir ilgi alanı için döngü başlatılır.
    foreach ($interests as $interest) {
        //İlgi alanı da küçük harfe çevrilir.
        $interest = strtolower($interest);

        //İlgi alanı, $categoryMap içinde tanımlıysa ve etkinliğin kategorisi bu alanla eşleşiyorsa öneri olarak işaretlenir.
        if (isset($categoryMap[$interest]) && in_array($eventCategory, $categoryMap[$interest])) {
            $isRecommended = true;
            break; //Bir eşleşme bulunduğunda döngü sonlandırılır.
        }
    }

    //Etkinliğe öneri bilgisi eklenir.
    $event['recommended'] = $isRecommended;
}

echo json_encode($events); //Tüm etkinlikler (öneri bilgisi ile birlikte) JSON formatına çevrilip ekrana yazdırılır.
?>