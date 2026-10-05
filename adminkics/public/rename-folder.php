<?php
// RENAME NEW FOLDER TO NEWS - DELETE AFTER USE!

echo "<h1>📁 Renaming 'new' to 'news'</h1>";

$basePath = __DIR__ . '/../storage/app/public/';
$publicPath = __DIR__ . '/storage/';

// Rename in storage/app/public/
$oldStorage = $basePath . 'new';
$newStorage = $basePath . 'news';

if (is_dir($oldStorage)) {
    if (rename($oldStorage, $newStorage)) {
        echo "✅ Renamed: storage/app/public/new/ → storage/app/public/news/<br>";
    } else {
        echo "❌ Failed to rename storage folder<br>";
    }
} else {
    echo "⚠️ storage/app/public/new/ not found<br>";
}

// Rename in public/storage/
$oldPublic = $publicPath . 'new';
$newPublic = $publicPath . 'news';

if (is_dir($oldPublic)) {
    if (rename($oldPublic, $newPublic)) {
        echo "✅ Renamed: public/storage/new/ → public/storage/news/<br>";
    } else {
        echo "❌ Failed to rename public folder<br>";
    }
} else {
    // Try to copy if rename fails
    if (is_dir($newStorage)) {
        // Copy from storage to public
        function copyDir($src, $dst) {
            if (!is_dir($src)) return;
            if (!is_dir($dst)) mkdir($dst, 0755, true);
            
            $dir = opendir($src);
            while (false !== ($file = readdir($dir))) {
                if ($file != '.' && $file != '..') {
                    $srcFile = $src . '/' . $file;
                    $dstFile = $dst . '/' . $file;
                    if (is_dir($srcFile)) {
                        copyDir($srcFile, $dstFile);
                    } else {
                        copy($srcFile, $dstFile);
                    }
                }
            }
            closedir($dir);
        }
        
        copyDir($newStorage, $newPublic);
        echo "✅ Copied storage/app/public/news/ → public/storage/news/<br>";
    }
}

echo "<h3>✅ Fix complete!</h3>";
echo "<p>Refresh your news page.</p>";
echo "<p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER USE!</p>";