<?php
echo '<h1>Image Access Check</h1>';

$base = '../storage/avatars';
echo "Scanning $base ...<br>";

// Recursive Iterator to find first PNG
try {
    $dir = new RecursiveDirectoryIterator($base, RecursiveDirectoryIterator::SKIP_DOTS);
    $ite = new RecursiveIteratorIterator($dir);

    $found = false;
    foreach ($ite as $file) {
        if ($file->getExtension() == 'png') {
            $path = $file->getPathname();
            echo "<h2>Testing File: $path</h2>";
            $found = true;

            // 1. Check Perms
            $perms = substr(sprintf('%o', fileperms($path)), -4);
            echo "Permissions: <strong>$perms</strong> (Should be 0644)<br>";
            echo 'Owner: ' . fileowner($path) . ' | Group: ' . filegroup($path) . '<br>';

            // 2. Try to Read via PHP
            if (is_readable($path)) {
                echo "PHP Read: <strong style='color:green'>Success</strong><br>";
                echo "<img src='/storage/avatars/" . str_replace('\\', '/', substr($path, strlen('../storage/avatars/'))) . "' style='width:50px; border:2px solid green;'><br>";
                echo 'If you see the image above, PHP can read it, but Apache is blocking it.<br>';
            } else {
                echo "PHP Read: <strong style='color:red'>Failed</strong> (Permission Denied)<br>";

                // Try FIX
                echo 'Attempting CHMOD 0644... ';
                if (@chmod($path, 0644)) {
                    echo "<strong style='color:green'>Changed.</strong><br>";
                } else {
                    echo "<strong style='color:red'>Failed.</strong><br>";
                }
            }
            break;  // Stop after 1
        }
    }

    if (!$found) {
        echo 'No PNG files found in avatars directory to test.';
        // List directories
        $scandir = scandir($base);
        print_r($scandir);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

echo '<h2>Apache Check</h2>';
echo 'If permissions are 0644 but 403 persists, the issue is likely <code>Options +FollowSymLinks</code> missing in .htaccess.';
