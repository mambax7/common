<?php
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

function xoops_module_pre_uninstall_common(&$xoopsModule) {
    return true;
}

function xoops_module_uninstall_common(&$xoopsModule) {
    delDir(XOOPS_ROOT_PATH . '/uploads/iscritti');
	return true;
}
