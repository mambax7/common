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

function xoops_module_pre_install_common(&$xoopsModule) {
    // Check if this XOOPS version is supported
    $minSupportedVersion = explode('.', '2.0.16');
    $currentVersion = explode('.', substr(XOOPS_VERSION,6));
    if($currentVersion[0] > $minSupportedVersion[0]) {
        return true;
    } elseif($currentVersion[0] == $minSupportedVersion[0]) {
        if($currentVersion[1] > $minSupportedVersion[1]) {
            return true;
        } elseif($currentVersion[1] == $minSupportedVersion[1]) {
            if($currentVersion[2] > $minSupportedVersion[2]) {
                return true;
            } elseif ($currentVersion[2] == $minSupportedVersion[2]) {
                return true;
            }
        }
    }
    return false;
}

function xoops_module_install_common(&$xoopsModule) {
    makeDir(XOOPS_ROOT_PATH . '/uploads/common');
    return true;
}
