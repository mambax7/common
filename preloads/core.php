<?php

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class CommonCorePreload
 */
class CommonCorePreload extends \XoopsPreloadItem
{
    // to add PSR-4 autoloader
    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        require __DIR__ . '/autoloader.php';
    }

    // Here your functions method
    // Example:
    /**
     * @param $args
     */
    public function eventCoreYourNameStart($args)
    {
        // Here your event
        exit();
    }
}
