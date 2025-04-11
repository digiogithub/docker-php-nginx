<?php
$requestUri = $_SERVER['REQUEST_URI'];
if ($requestUri === '/info') {
    phpinfo();
    exit;
}

$path = parse_url($requestUri, PHP_URL_PATH);
$filePath = __DIR__ . $path;

// Check if the file exists directly
if (file_exists($filePath)) {
    $content = file_get_contents($filePath);
    header('Content-Type: ' . mime_content_type($filePath));
    echo $content;
    exit;
}

// Check if it's a hashed filename (filename-hash.ext)
$extension = pathinfo($path, PATHINFO_EXTENSION);
$fileName = pathinfo($path, PATHINFO_FILENAME);

if (preg_match('/^(.+)-([a-f0-9]+)$/', $fileName, $matches)) {
    $originalName = $matches[1];
    $newPath = dirname($path) . '/' . $originalName . '.' . $extension;
    $originalFilePath = __DIR__ . $newPath;

    if (file_exists($originalFilePath)) {
        $content = file_get_contents($originalFilePath);
        header('Content-Type: ' . mime_content_type($originalFilePath));
        echo $content;
        exit;
    }
}

echo "servername: " . $_SERVER['SERVER_NAME'] . "\n";
echo "request_uri: " . $_SERVER['REQUEST_URI'] . "\n";
var_export($_SERVER);
echo "\n";
echo $path;
http_response_code(404);
