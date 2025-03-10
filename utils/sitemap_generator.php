<?php

$fullDomain = "";
$envFilePath = __DIR__ . "/../app/Config/Envs/.env";
$envFileData = file($envFilePath);
foreach ($envFileData as $row) {
    $row = trim($row);
    list($key, $value) = explode('=', $row);
    if ($key == "FULLDOMAIN") {
        $fullDomain = $value;
    }
}

if (empty($fullDomain)) {
    echo "Full domain name not specified in env... Failed to generate sitemap";
    exit;
}

$views = __DIR__ . "/../app/Views/";
$files = scandir($views);
$ignoreFiles = [
    ".",
    "..",
    "Fixed",
    "404.php",
];

$xml = <<<XML
<?xml version='1.0' encoding='UTF-8'?>
<urlset></urlset>
XML;

$urlset = new SimpleXMLElement($xml);

$url = $urlset->addChild("url");
$url->addChild("loc", "https://$fullDomain/");
$url->addChild("lastmod", date("Y-m-d"));
$url->addChild("priority", "0.8");
$url->addChild("changefreq", "daily");

foreach ($files as $fileName) {
    if (in_array($fileName, $ignoreFiles)) {
        continue;
    }

    $pageName = explode('.', $fileName)[0];
    $url = $urlset->addChild("url");
    $url->addChild("loc", "https://$fullDomain/$pageName");
    $url->addChild("lastmod", date("Y-m-d"));
    $url->addChild("priority", "0.8");
    $url->addChild("changefreq", "daily");
}

$urlset->asXML(__DIR__ . "/../app/public/sitemap.xml");
