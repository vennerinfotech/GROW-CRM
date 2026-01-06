<?php
echo '<h1>Checking .htaccess configurations</h1>';

$files = [
    '../storage/.htaccess',
    '../storage/avatars/.htaccess',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<h2>Found: $file</h2>";
        $content = file_get_contents($file);
        echo "<pre style='background:#f0c0c0; padding:10px; border:1px solid red;'>" . htmlspecialchars($content) . '</pre>';

        if (stripos($content, 'Deny from all') !== false) {
            echo "<strong style='color:red'>CRITICAL: This file is blocking access!</strong><br>";
            echo 'This is why you get a 403 error.<br><br>';

            // Attempt to fix
            $backup = $file . '.bak';
            echo "Attempting to rename to $backup ... ";
            if (rename($file, $backup)) {
                echo "<strong style='color:green'>Success! File renamed.</strong><br>";
                echo 'Try accessing your images now.';
            } else {
                echo "<strong style='color:red'>Failed to rename.</strong><br>";
                echo 'Please run this command in SSH:<br>';
                echo '<code>mv ' . realpath($file) . ' ' . realpath($file) . '.bak</code>';
            }
        }
    } else {
        echo "<h2>Checking $file</h2>";
        echo 'File does not exist (Good).<br>';
    }
}
