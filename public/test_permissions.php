<?php

echo '<h1>Diagnostic & Permission Test</h1>';

$publicStorage = __DIR__ . '/storage';
$targetAvatarPath = '../storage/avatars/';  // Relative to public

echo "<h2>Checking 'public/storage'</h2>";

if (file_exists($publicStorage)) {
    echo "Found 'public/storage': <strong style='color:green'>YES</strong><br>";

    if (is_link($publicStorage)) {
        echo "It is a Symlink: <strong style='color:green'>YES</strong><br>";
        echo 'Symlink Target: ' . readlink($publicStorage) . '<br>';

        // Check if target exists
        $target = readlink($publicStorage);
        if (file_exists($target)) {
            echo "Target Directory Exists: <strong style='color:green'>YES</strong><br>";
            echo 'Target Permissions: ' . substr(sprintf('%o', fileperms($target)), -4) . '<br>';
        } else {
            echo "Target Directory Exists: <strong style='color:red'>NO (Broken Link)</strong><br>";
        }
    } else {
        echo "It is a Symlink: <strong style='color:red'>NO (It is a real directory)</strong><br>";
        echo 'Directory Permissions: ' . substr(sprintf('%o', fileperms($publicStorage)), -4) . '<br>';
    }
} else {
    echo "Found 'public/storage': <strong style='color:red'>NO</strong><br>";
}

echo "<h2>Checking 'storage/avatars' Availability</h2>";

// Try to access the storage root directly if we can (assuming standard structure)
$realStoragePath = realpath(__DIR__ . '/../storage');
if ($realStoragePath) {
    echo 'Real Storage Path resolved: ' . $realStoragePath . '<br>';

    $avatarsPath = $realStoragePath . '/avatars';
    if (file_exists($avatarsPath)) {
        echo "Avatars Directory Exists: <strong style='color:green'>YES</strong><br>";
        echo 'Avatars Directory Permissions: ' . substr(sprintf('%o', fileperms($avatarsPath)), -4) . '<br>';
    } else {
        echo "Avatars Directory Exists: <strong style='color:red'>NO</strong><br>";
    }
} else {
    echo "Could not resolve '../storage' path.<br>";
}

echo '<h2>Server Info</h2>';
echo 'Web Server User: ' . exec('whoami') . '<br>';
echo 'PHP User: ' . get_current_user() . '<br>';

echo '<h2>Next Steps</h2>';
echo "<ul>
<li>If 'public/storage' is missing, run: <code>ln -s " . ($realStoragePath ?? '/path/to/storage') . ' public/storage</code></li>
<li>If Permissions are not 0755 or 0775, fix them: <code>chmod -R 755 storage</code></li>
<li>If it says 403 Forbidden on this file, your issue is .htaccess or server config.</li>
</ul>';
