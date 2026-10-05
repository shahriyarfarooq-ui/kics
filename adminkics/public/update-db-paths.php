<?php
// UPDATE DATABASE PATHS - DELETE AFTER USE!

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>🔧 Updating Database Paths</h1>";

try {
    // Update news table
    $updated = DB::table('news')
        ->where('image', 'LIKE', 'new/%')
        ->update([
            'image' => DB::raw("REPLACE(image, 'new/', 'news/')")
        ]);
    
    echo "✅ Updated $updated news records<br>";
    
    // Update events table (if needed)
    $updatedEvents = DB::table('events')
        ->where('featured_image', 'LIKE', 'new/%')
        ->update([
            'featured_image' => DB::raw("REPLACE(featured_image, 'new/', 'news/')")
        ]);
    
    echo "✅ Updated $updatedEvents event records<br>";
    
    // Check if any records still have 'new/' path
    $remaining = DB::table('news')
        ->where('image', 'LIKE', 'new/%')
        ->count();
    
    if ($remaining > 0) {
        echo "⚠️ Still $remaining records with 'new/' path<br>";
    } else {
        echo "✅ All paths updated successfully!<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER USE!</p>";