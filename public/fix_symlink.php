<?php
echo '<h1>Fixing Storage Symlink (v3 - Force)</h1>';

$target = '../storage';
$link = 'storage';

function getLinkStatus($link)
{
    if (is_link($link))
        return 'symlink';
    if (is_dir($link))
        return 'directory';
    if (is_file($link))
        return 'file';
    return false;
}

// 1. Diagnostics
echo '<h2>1. Checking for conflicts...</h2>';
$type = getLinkStatus($link);
if ($type) {
    echo "Found existing item: <strong>$type</strong><br>";

    if ($type === 'symlink') {
        echo 'It is a symlink. Deleting it to refresh... ';
        if (@unlink($link)) {
            echo "<strong style='color:green'>Deleted.</strong><br>";
        } else {
            echo 'Failed to delete via PHP. Trying shell...<br>';
            shell_exec("rm $link");
            if (!file_exists($link)) {
                echo "<strong style='color:green'>Deleted via shell.</strong><br>";
            } else {
                echo "<strong style='color:red'>Could not delete.</strong> Aborting.<br>";
                exit;
            }
        }
    } elseif ($type === 'directory') {
        $backup = 'storage_backup_' . time();
        echo "It is a DIRECTORY. Attempting to rename to $backup... ";
        if (@rename($link, $backup)) {
            echo "<strong style='color:green'>Renamed.</strong><br>";
        } else {
            echo "<strong style='color:red'>Failed to rename.</strong> Check permissions.<br>";
            exit;
        }
    }
} else {
    echo 'No conflict found.<br>';
}

// 2. Creation
echo '<h2>2. Creating Symlink...</h2>';

// Method A: PHP
if (@symlink($target, $link)) {
    echo "Method A (PHP): <strong style='color:green'>Success!</strong><br>";
} else {
    echo "Method A (PHP): <strong style='color:red'>Failed</strong>.<br>";

    // Method B: Shell
    echo 'Method B (Shell): ';
    $out = shell_exec("ln -s $target $link 2>&1");
    if (is_link($link)) {
        echo "<strong style='color:green'>Success!</strong><br>";
    } else {
        echo "<strong style='color:red'>Failed</strong>. Output: $out<br>";
    }
}

// 3. Verification
echo '<h2>3. Verification</h2>';
if (is_link($link)) {
    echo 'Symlink exists.<br>';
    echo 'Target: ' . readlink($link) . '<br>';
    // Check if target is readable
    $realTarget = realpath($link);
    if ($realTarget && file_exists($realTarget)) {
        echo "Content access: <strong style='color:green'>OK (Target resolves)</strong><br>";
        echo '<h3>You can now delete this file and check your site.</h3>';
    } else {
        echo "Content access: <strong style='color:orange'>Warning (Target is broken or unreadable)</strong><br>";
        echo 'Path resolved to: ' . ($realTarget ? $realTarget : 'FALSE') . '<br>';
        echo 'Make sure permissions on <code>../storage</code> are 755.';
    }
} else {
    echo "<h3 style='color:red'>FAILED.</h3>";
    echo 'Please use the manual SSH command provided previously.';
}
