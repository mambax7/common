<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/*
 * common module
 *
 * @copyright       XOOPS Project https://xoops.org/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         common
 * @since           1.00
 * @author          luciorota, studiopas
 * @version         svn:$Id$
 */

if (class_exists('Xoops', false)) {
    \Xoops::getInstance()->header();
} else {
    require_once XOOPS_ROOT_PATH . '/header.php';
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
}
include __DIR__ . '/common.php';
