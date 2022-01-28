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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         common
 * @since           1.00
 * @author          luciorota
 * @version         svn:$Id$
 */

namespace common;

use Xmf\Module\Helper;

defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

$currentPath = __FILE__;
if (DIRECTORY_SEPARATOR != "/") {
    $currentPath = str_replace(strpos($currentPath, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $currentPath);
}
define("FORMTOGGLEB3_FILENAME", basename($currentPath));
define("FORMTOGGLEB3_PATH", dirname($currentPath));
define("FORMTOGGLEB3_REL_URL", str_replace(XOOPS_ROOT_PATH . "/", '', dirname($currentPath)));
define("FORMTOGGLEB3_URL", XOOPS_URL . '/' . FORMTOGGLEB3_REL_URL . '/' . FORMTOGGLEB3_FILENAME);
define("FORMTOGGLEB3_JS_REL_URL", FORMTOGGLEB3_REL_URL . "/formtoggleb3/js");
define("FORMTOGGLEB3_CSS_REL_URL", FORMTOGGLEB3_REL_URL . "/formtoggleb3/css");
define("FORMTOGGLEB3_IMAGES_REL_URL", FORMTOGGLEB3_REL_URL . "/formtoggleb3/images");

xoops_load('XoopsFormLoader');

/**
 * FormToggleB3
 *
 * Bootstrap Tags Input with Autocomplete
 * jQuery plugin providing a Twitter Bootstrap user interface for managing tags
 * https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/
 *
 */
class FormToggleB3 extends \XoopsFormElement {

    private $_id;
    private $_value;    
    private $_on;
    private $_off;
    private $_size;
    private $_onstyle;
    private $_offstyle;
    
    /**
     * set the "id" attribute for the element
     *
     * @param string $name "name" attribute for the element
     */
    function setId($name) {
        $this->_id = md5(uniqid(rand(), true));
    }

    /**
     * get the "id" attribute for the element
     *
     * @param bool $encode
     *
     * @return string "name" attribute
     */
    function getId($encode = true) {
        if (false != $encode) {
            return str_replace('&amp;', '&', htmlspecialchars($this->_id, ENT_QUOTES));
        }
        return $this->_id;
    }

    /**
     * Constructor
     *
     * @param string $caption   Caption
     * @param string $name      "name" attribute
     * @param bool   $value     value
     * @param string $on        Text of the on toggle
     * @param string $off       Text of the off toggle
     * @param string $size      Size of the toggle. Possible values are:large,normal,small,mini
     * @param string $onstyle   Style of the on toggle. Possible values are:default,primary,success,info,warning,danger
     * @param string $offstyle  Style of the off toggle. Possible values are:default,primary,success,info,warning,danger
     *
     */
    public function __construct(
            $caption,
            $name,
            $value,
            $on = _YES,
            $off = _NO,
            $size = "normal",
            $onstyle = "primary",
            $offstyle = "default"
    ) {
        $this->setCaption($caption);
        $this->setName($name);
        $this->setId($name);
        $this->_value = $value;
        $this->_on = $on;
        $this->_off = $off;
        $this->_size = $size;
        $this->_onstyle = $onstyle;
        $this->_offstyle = $offstyle;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render() {
        static $isCommonFormToggleB3Included = false;
        $commonJs = ''; // redered only once in head
        $headJs = ''; // redered in head
        $js = ''; // rendered just after html
        $commonCss = ''; // redered only once in head
        $headCss = ''; // redered in head
        $css = ''; // rendered just before html

        $html = '';
        $html = '';
        // add common js
        // add css js
        if (is_object($GLOBALS['xoTheme'])) {
            if (!$isCommonFormToggleB3Included) {
                $GLOBALS['xoTheme']->addStylesheet('https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
                //$GLOBALS['xoTheme']->addStylesheet('', [], $commonCss);
                $GLOBALS['xoTheme']->addScript('https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');
                //$GLOBALS['xoTheme']->addScript('', [], $commonJs);
                //
                $isCommonFormToggleB3Included = true;
            }
            $GLOBALS['xoTheme']->addScript('', [], $headJs);
            $GLOBALS['xoTheme']->addStylesheet('', [], $commonCss);
        } else {
            if (!$isCommonFormToggleB3Included) {
                $html .= "<style type='text/css'>@import url(https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css);</style>\n";
                //$html .= "<style>\n" . $commonCss . "\n" . "</style>\n";
                $html .= "<script src='https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js' type='text/javascript'></script>\n";
                //$html .= "<script type='text/javascript'>\n" . $commonJs . "\n" . "</script>\n";
                $isCommonFormToggleB3Included = true;
            }
        }
        // add css just before html
        $css .= "<style>\n";
        $css .= "</style>\n";
        $html .= $css . "\n";
        //
        // html
        $checked = ($this->_value) ? "checked " : "";
        $html .= "<input type='checkbox' {$this->getExtra()} name='{$this->getName()}' id='{$this->getName()}' title='{$this->getTitle()}' {$checked}>\n";
        //
        // add js just after html
        $js .= "<script type='text/javascript'>\n";
        $js .= "
            $(document).ready(function() {
                $('#{$this->getName()}').bootstrapToggle({
                    on: '{$this->_on}',
                    off: '{$this->_off}',
                    size: '{$this->_size}',
                    onstyle: '{$this->_onstyle}',
                    offstyle: '{$this->_offstyle}',
                });
            });
            ";
        $js .= "</script>\n";
        $html .= $js . "\n";
        //
        return $html;
    }

}
