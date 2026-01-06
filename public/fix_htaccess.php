<?php
echo '<h1>Fixing .htaccess (Enabling Symlinks)</h1>';

$file = '.htaccess';

if (!file_exists($file)) {
    die('Error: .htaccess file not found in ' . __DIR__);
}

$content = file_get_contents($file);

// Check if already present
if (strpos($content, '+FollowSymLinks') !== false) {
    echo "Status: <strong style='color:orange'>Skipped</strong><br>";
    echo "Your .htaccess already contains '+FollowSymLinks'.<br>";
    echo 'If it still fails, try changing it to <code>+SymLinksIfOwnerMatch</code> manually.';
    exit;
}

// Backup
copy($file, $file . '.bak_' . time());
echo "Backup created: $file.bak_" . time() . '<br>';

// Attempt to add it to the specific Options line first
$newContent = str_replace('Options -MultiViews -Indexes', 'Options -MultiViews -Indexes +FollowSymLinks', $content, $count);

if ($count == 0) {
    // If exact string not found, add it to the top
    echo 'Standard Options line not found. Appending to top...<br>';
    $newContent = "Options +FollowSymLinks\n" . $content;
}

if (file_put_contents($file, $newContent)) {
    echo "Status: <strong style='color:green'>Success</strong><br>";
    echo "Added 'Options +FollowSymLinks' to your .htaccess file.<br>";
    echo '<h3>Try accessing your images now!</h3>';
} else {
    echo "Status: <strong style='color:red'>Failed</strong> to write to file.<br>";
}
