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
define("FORMSELECTGROUPB3_FILENAME", basename($currentPath));
define("FORMSELECTGROUPB3_PATH", dirname($currentPath));
define("FORMSELECTGROUPB3_REL_URL", str_replace(XOOPS_ROOT_PATH . "/", '', dirname($currentPath)));
define("FORMSELECTGROUPB3_URL", XOOPS_URL . '/' . FORMSELECTGROUPB3_REL_URL . '/' . FORMSELECTGROUPB3_FILENAME);
define("FORMSELECTGROUPB3_JS_REL_URL", FORMSELECTGROUPB3_REL_URL . "/formselectgroupb3/js");
define("FORMSELECTGROUPB3_CSS_REL_URL", FORMSELECTGROUPB3_REL_URL . "/formselectgroupb3/css");
define("FORMSELECTGROUPB3_IMAGES_REL_URL", FORMSELECTGROUPB3_REL_URL . "/formselectgroupb3/images");

xoops_loadLanguage('formselectgroupb3', 'common');
xoops_load('XoopsFormLoader');

class FormSelectGroupB3 extends \XoopsFormElement
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
        static $isCommonFormSelectGroupB3Included = false;
        $commonJs = '';
        $css = '';
        $js = '';
        $html = '';
        $ret = '';
        // add common js
        $commonJs = "";
        if (is_object($GLOBALS['xoTheme'])) {
            if ( !$isCommonFormSelectGroupB3Included) {
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
                //$GLOBALS['xoTheme']->addStylesheet(FORMSELECTGROUPB3_JS_REL_URL . '/lou-multi-select/css/multi-select.css');
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?' . FORMSELECTGROUPB3_JS_REL_URL . '/multiselect-master/dist/js/multiselect.min.js');
                $GLOBALS['xoTheme']->addScript('', array(), $commonJs);
                $isCommonFormSelectGroupB3Included = true;
            }
        } else {
            if (!$isCommonFormSelectGroupB3Included) {
                //$ret .= "<style type='text/css'>@import url(" . FORMSELECTGROUPB3_JS_REL_URL . "//lou-multi-select/css/multi-select.css);</style>\n";
                $ret .= "<script src='" . XOOPS_URL . "/browse.php?Frameworks/jquery/jquery.js' type='text/javascript'></script>\n";
                $ret .= "<script src='" . XOOPS_URL . "/browse.php?" . FORMSELECTGROUPB3_JS_REL_URL . "/multiselect-master/dist/js/multiselect.min.js' type='text/javascript'></script>\n";
                $ret .= "<script type='text/javascript'>\n";
                $ret .= $commonJs . "\n";
                $ret .= "</script>\n";
                $isCommonFormSelectGroupB3Included = true;
            }
        }
        // add css
        $css .= "<style>\n";
        $css .= ".members_from select.form-control {width:100% !important;}\n";
        $css .= ".members_to select.form-control {width:100% !important;}\n";
        $css .= "</style>\n";
        $ret .= $css . "\n";
        // add html
        $from_members = [];
        $to_members = [];
        foreach ($this->members as $uid => $member) {
            if (in_array($uid, $this->getValue())) {
                $to_members[$uid] = $member;
            } else {
                $from_members[$uid] = $member;
            }
        }
        $members_from = new \XoopsFormSelect('', "{$this->getName()}_{$this->getId()}", null, $this->size, true);
        $members_from->addOptionArray($from_members);
        $members_from->setClass("w-100");
        
        //
        $members_to = new \XoopsFormSelect('', "{$this->getName()}", null, $this->size, true);
        $members_to->addOptionArray($to_members);
        $members_to->setClass("form-control w-100");
$html .= "        
<div class='row'>
    <div class='members_from col-md-5'>
        {$members_from->render()}
    </div>
    <div class='col-md-2'>
        <button type='button' id='{$this->getName()}_{$this->getId()}_undo' class='btn btn-primary btn-block'>" . _FORMSELECTGROUPB3_UNDO . "</button>
        <button type='button' id='{$this->getName()}_{$this->getId()}_rightAll' class='btn btn-default btn-block'>&#8649;</button>
        <button type='button' id='{$this->getName()}_{$this->getId()}_rightSelected' class='btn btn-default btn-block'>&rarr;</button>
        <button type='button' id='{$this->getName()}_{$this->getId()}_leftSelected' class='btn btn-default btn-block'>&larr;</button>
        <button type='button' id='{$this->getName()}_{$this->getId()}_leftAll' class='btn btn-default btn-block'>&#8647;</button>
        <button type='button' id='{$this->getName()}_{$this->getId()}_redo' class='btn btn-primary btn-block'>" . _FORMSELECTGROUPB3_REDO . "</button>
    </div>
    <div class='members_to col-md-5'>
        {$members_to->render()}
    </div>
</div>
";
        $ret .= $html . "\n";
        // add js
        $js .= "<script type='text/javascript'>\n";
        $js .= "
jQuery(document).ready(function($) {
    $('#{$this->getName()}_{$this->getId()}').multiselect({
        right: '#{$this->getName()}',
        rightSelected: '#{$this->getName()}_{$this->getId()}_rightSelected',
        rightAll: '#{$this->getName()}_{$this->getId()}_rightAll',
        leftSelected: '#{$this->getName()}_{$this->getId()}_leftSelected',
        leftAll: '#{$this->getName()}_leftAll',
        undo: '#{$this->getName()}_{$this->getId()}_undo',
        redo: '#{$this->getName()}_{$this->getId()}_redo',
        submitAllLeft: false,
        submitAllRight: true,
    });
});";
        $js .= "</script>\n";
        $ret .= $js . "\n";

        
        return $ret;
    }
}