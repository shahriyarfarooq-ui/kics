<?php
// CHECK NEWS FOLDER - DELETE AFTER USE!

echo "<h1>🔍 Checking News Folder</h1>";

$paths = [
    __DIR__ . '/storage/news/',
    __DIR__ . '/storage/new/',
    __DIR__ . '/../storage/app/public/news/',
    __DIR__ . '/../storage/app/public/new/',
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        echo "✅ Folder exists: " . str_replace(__DIR__, '', $path) . "<br>";
        $files = scandir($path);
        $count = 0;
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $count++;
                if ($count <= 5) {
                    echo "  📄 " . $file . "<br>";
                }
            }
        }
        if ($count > 5) {
            echo "  ... and " . ($count - 5) . " more files<br>";
        }
    } else {
        echo "❌ Folder NOT found: " . str_replace(__DIR__, '', $path) . "<br>";
    }
}

echo "<p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER USE!</p>";