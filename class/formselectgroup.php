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
define("FORMSELECTGROUP_FILENAME", basename($currentPath));
define("FORMSELECTGROUP_PATH", dirname($currentPath));
define("FORMSELECTGROUP_REL_URL", str_replace(XOOPS_ROOT_PATH . "/", '', dirname($currentPath)));
define("FORMSELECTGROUP_URL", XOOPS_URL . '/' . FORMSELECTGROUP_REL_URL . '/' . FORMSELECTGROUP_FILENAME);
define("FORMSELECTGROUP_JS_REL_URL", FORMSELECTGROUP_REL_URL . "/formselectgroup/js");
define("FORMSELECTGROUP_CSS_REL_URL", FORMSELECTGROUP_REL_URL . "/formselectgroup/css");
define("FORMSELECTGROUP_IMAGES_REL_URL", FORMSELECTGROUP_REL_URL . "/formselectgroup/images");

xoops_loadLanguage('formgooglemap', 'common');
xoops_load('XoopsFormLoader');

class FormSelectGroup extends \XoopsFormElement
{
    private $members = array();
    private $size;
    private $multiple;

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
    function getId($encode = true)
    {
        if (false != $encode) {
            return str_replace('&amp;', '&', htmlspecialchars($this->_id, ENT_QUOTES));
        }

        return $this->_id;
    }
    
    /**
     * Set initial content
     *
     * @param  $value string
     */
    function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * Get initial content
     *
     * @param bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compat
     * @return string
     */
    function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value) : $this->_value;
    }
    
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param bool   $include_anon Include group "anonymous"?
     * @param mixed  $value        Pre-selected value (or array of them).
     * @param int    $size         Number or rows. "1" makes a drop-down-list.
     * @param bool   $multiple     Allow multiple selections?
     */
    public function __construct($caption, $name, $include_anon = false, $value = null, $size = 1, $multiple = false)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->setId($name);
        $this->setValue($value);
        $member_handler = xoops_getHandler('member');
        if (!$include_anon) {
            $this->members = $member_handler->getGroupList(new \Criteria('groupid', XOOPS_GROUP_ANONYMOUS, '!='));
        } else {
            $this->members = $member_handler->getGroupList();
        }  
        $this->size = $size;
        $this->multiple = $multiple;
    }
    
    /**
     * prepare HTML for output
     *
     * @return sting HTML
     */
    public function render() {
        static $isCommonFormSelectGroupIncluded = false;
        $commonJs = '';
        $css = '';
        $js = '';
        $html = '';
        $ret = '';
        // add common js
        $commonJs = "";
        if (is_object($GLOBALS['xoTheme'])) {
            if ( !$isCommonFormSelectGroupIncluded) {
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
                $GLOBALS['xoTheme']->addStylesheet(FORMSELECTGROUP_JS_REL_URL . '/lou-multi-select/css/multi-select.css');
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?' . FORMSELECTGROUP_JS_REL_URL . '/lou-multi-select/js/jquery.multi-select.js');
                $GLOBALS['xoTheme']->addScript('', array(), $commonJs);
                $isCommonFormSelectGroupIncluded = true;
            }
        } else {
            if (!$isCommonFormSelectGroupIncluded) {
                $ret .= "<style type='text/css'>@import url(" . FORMSELECTGROUP_JS_REL_URL . "//lou-multi-select/css/multi-select.css);</style>\n";
                $ret .= "<script src='" . XOOPS_URL . "/browse.php?Frameworks/jquery/jquery.js' type='text/javascript'></script>\n";
                $ret .= "<script src='" . XOOPS_URL . "/browse.php?" . FORMSELECTGROUP_JS_REL_URL . "/lou-multi-select/js/jquery.multi-select.js' type='text/javascript'></script>\n";
                $ret .= "<script type='text/javascript'>\n";
                $ret .= $commonJs . "\n";
                $ret .= "</script>\n";
                $isCommonFormSelectGroupIncluded = true;
            }
        }
        // add css
        $css .= "<style>\n";
        $css .= "</style>\n";
        $ret .= $css . "\n";
        // add html
        $members = new \XoopsFormSelect('', $this->getName(), $this->getValue(), $this->size, $this->multiple);
        $members->addOptionArray($this->members);
        $html .= $members->render();
        $ret .= $html . "\n";
        // add js
        $js .= "<script type='text/javascript'>\n";
        $js .= "jQuery(document).ready(function($) {
                $('#" . $this->getName() . "').multiSelect();;
        });";
        $js .= "</script>\n";
        $ret .= $js . "\n";
        
        
        return $ret;
    }
}