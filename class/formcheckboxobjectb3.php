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
define("FORMSELECTOBJECTB3_FILENAME", basename($currentPath));
define("FORMSELECTOBJECTB3_PATH", dirname($currentPath));
define("FORMSELECTOBJECTB3_REL_URL", str_replace(XOOPS_ROOT_PATH . "/", '', dirname($currentPath)));
define("FORMSELECTOBJECTB3_URL", XOOPS_URL . '/' . FORMSELECTOBJECTB3_REL_URL . '/' . FORMSELECTOBJECTB3_FILENAME);
define("FORMSELECTOBJECTB3_JS_REL_URL", FORMSELECTOBJECTB3_REL_URL . "/formselectgroup/js");
define("FORMSELECTOBJECTB3_CSS_REL_URL", FORMSELECTOBJECTB3_REL_URL . "/formselectgroup/css");
define("FORMSELECTOBJECTB3_IMAGES_REL_URL", FORMSELECTOBJECTB3_REL_URL . "/formselectgroup/images");

xoops_load('XoopsFormLoader');

class FormCheckBoxObjectB3 extends \XoopsFormElement
{
    private $objectHandler;
    private $vars;
    private $columnTitles;
    private $objectCriteria;
    private $size;
    private $multiple;
    private $objects;

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
     * @param object $objectHandler
     * @param object $objectCriteria
     * @param mixed  $value        $value Either one value as a string or an array of them.
     * @param bool|strig  $size         
     */
    public function __construct($caption, $name, $objectHandler, $columnTitles = [], $vars = [], $objectCriteria = null, $value = null, $size = false)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->setId($name);
        if (isset($value)) {
            $this->setValue($value);
        }
        $this->objectHandler = $objectHandler;
        $this->columnTitles = $columnTitles;
        $this->vars = $vars;
        $this->objectCriteria = $objectCriteria;
        $this->size = $size;
        $this->objects = ($this->objectHandler)->getObjects($this->objectCriteria, true, true);
    }
    
    /**
     * prepare HTML for output
     *
     * @return sting HTML
     */
    public function render() {
        static $isCommonFormCheckBoxObjectB3Included = false;
        $commonJs = ''; // redered only once in head
        $headJs = ''; // rendred in head
        $css = ''; // rendered just before html
        $js = ''; // rendered just after html
        $html = '';
        $ret = '';
        // add header js
        $headJs .= "
            $(document).ready(function() {";
        $headJs .= " 
                // First add class for pre-checked entries
                $('#{$this->getName()}_table tbody tr td input[type=checkbox]:checked').each(function() {
                    $(this).closest('tr').addClass('info');
                });
                $('#{$this->getName()}_table tbody tr td input[type=checkbox]').on('change', function() {
                    $(this).closest('tr').toggleClass('info');
                });"; 
        $headJs .= "
                $('#{$this->getName()}_table thead tr#{$this->getName()}_ricerca th.ricerca').each(function() {
                    var title = $('#{$this->getName()}_table thead tr th').eq($(this).index()).text().trim();
                    $(this).html('<input type=\'text\' class=\'form-control input-sm\' placeholder=\'" . _SEARCH . " ' + title + '\'>' );
                });";
        $headJs .= "
            table_{$this->getName()} = $('#{$this->getName()}_table').DataTable({";
        if($this->size === false) {
            $headJs .= 'scrollY": "auto",';
            $headJs .= '"paging": false,';
        } else {
            $headJs .= '"scrollY": "' . $this->size . '",';
            $headJs .= '"scrollCollapse": true,';       
            $headJs .= '"paging": false,';
        }
        $headJs .= '"searching": false,';
        $headJs .= '"scrollX": false,';
        $headJs .= '"order": [],';
        $headJs .= '"columnDefs": [{ "orderable": false, "targets": 0 },],';
        $headJs .= '"lengthMenu": [[-1, 100, 50, 10], ["Tutti", 100, 50, 10] ],';
        $headJs .= '"language": {"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json"}';
        $headJs .= '});';
        $headJs .= "
            // Apply the search
            table_{$this->getName()}.columns().eq(0).each(function(colIdx) {
                $('input', table_{$this->getName()}.column( colIdx ).header()).on('keyup change clear', function () {
                    table_{$this->getName()}
                        .column(colIdx)
                        .search(this.value)
                        .draw();
                    });
                });";
                    
        $headJs .= "});";

        // add common js
        $commonJs = "";
        if (is_object($GLOBALS['xoTheme'])) {
            if ( !$isCommonFormCheckBoxObjectB3Included) {
//                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//                $GLOBALS['xoTheme']->addStylesheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');
//                $GLOBALS['xoTheme']->addScript('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
//                $GLOBALS['xoTheme']->addScript('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js');
                //
                $GLOBALS['xoTheme']->addScript('', array(), $commonJs);
                $isCommonFormCheckBoxObjectB3Included = true;
            }
            $GLOBALS['xoTheme']->addScript('', array(), $headJs);
        } else {
            if (!$isCommonFormCheckBoxObjectB3Included) {
//                $ret .= "<script src='" . XOOPS_URL . "/browse.php?Frameworks/jquery/jquery.js' type='text/javascript'></script>\n";
//                $ret .= "<style type='text/css'>@import url(https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css);</style>\n";
//                $ret .= "<script src='https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' type='text/javascript'></script>\n";
//                $ret .= "<script src='https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js' type='text/javascript'></script>\n";
                //
                $ret .= "<script type='text/javascript'>\n";
                $ret .= $commonJs . "\n";
                $ret .= "</script>\n";
                $isCommonFormCheckBoxObjectB3Included = true;
            }
            $ret .= "<script type='text/javascript'>\n";
            $ret .= $headJs . "\n";
            $ret .= "</script>\n";
        }
        // add css
        $css .= "<style>\n";
        $css .= "</style>\n";
        $ret .= $css . "\n";
        // add html
        $html .= "<div style='width:100%;'>\n";
        $html .= "<table id='{$this->getName()}_table' style='width:100%;' class='table table-condensed table-bordered table-hover'>\n";
        $html .= "<thead>\n";
        //
        $html .= "<tr>\n";
        $html .= "<th>&nbsp;</th>\n";
        foreach ($this->columnTitles as $columnTitle) {
            $html .= "<th>{$columnTitle}</th>\n";
        }
        $html .= "</tr>\n";
        //
        $html .= "<tr id='{$this->getName()}_ricerca'>\n";
        $html .= "<th>&nbsp;</th>\n";
        foreach ($this->vars as $key) {
            $html .= "<th class='ricerca'>&nbsp;</th>\n";
        }
        $html .= "</tr>\n";
        //
        $html .= "</thead>\n";
        $html .= "<tbody>\n";
        $row = 1;
        foreach($this->objects as $object_id => $object) {
            $values = $object->getValues();
            $checked = (count($this->getValue()) > 0 && in_array($object_id, $this->getValue())) ? 'checked' : '';
            $html .= "<tr>\n";
            $html .= "<td object_id='{$object_id}'><input type='checkbox' name='{$this->getName()}[]' id='{$this->getName()}{$row}' title='' value='{$object_id}' {$checked}></td>\n";
            foreach ($this->vars as $key) {
                $html .= "<td>{$values[$key]}</td>";
            }
            $html .= "</tr>\n";
            $row++;
        }
        $html .= "</tbody>\n";
        $html .= "</table>\n";
        $html .= "</div>\n";
        $html .= "<br>\n";
        $ret .= $html . "\n";
        // add js
//        $js .= "<script type='text/javascript'>\n";
//        $js .= "jQuery(document).ready(function($) {
//                $('#" . $this->getName() . "').multiSelect();;
//        });";
//        $js .= "</script>\n";
        $ret .= $js . "\n";
        
        return $ret;
    }
}