<?php
/**
 * Create a new directory that contains the file 'index.html'
 *
 */
function makeDir($dir) {
    if (!is_dir($dir)){
        if (!mkdir($dir)){
            return false;
        } else {
            if ($fh = @fopen($dir.'/index.html', 'w'))
                fwrite($fh, '<script>history.go(-1);</script>');
            @fclose($fh);
            return true;
        }
    }
}
/**
 * Delete a not empty directory
 *
 */
function delDir($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!iscritti_delDir($dir . '/' . $item)) return false;
    }
    return rmdir($dir);
}

function xoops_module_pre_update_common(&$xoopsModule) {
    return true;
}

function xoops_module_update_common(&$xoopsModule, $oldVersion = null) {
    delDir(XOOPS_ROOT_PATH . '/uploads/common');
    makeDir(XOOPS_ROOT_PATH . '/uploads/common');
    return true;
}
