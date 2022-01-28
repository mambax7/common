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
define("FORMTAGSINPUTB3_FILENAME", basename($currentPath));
define("FORMTAGSINPUTB3_PATH", dirname($currentPath));
define("FORMTAGSINPUTB3_REL_URL", str_replace(XOOPS_ROOT_PATH . "/", '', dirname($currentPath)));
define("FORMTAGSINPUTB3_URL", XOOPS_URL . '/' . FORMTAGSINPUTB3_REL_URL . '/' . FORMTAGSINPUTB3_FILENAME);
define("FORMTAGSINPUTB3_JS_REL_URL", FORMTAGSINPUTB3_REL_URL . "/formtagsinputb3/js");
define("FORMTAGSINPUTB3_CSS_REL_URL", FORMTAGSINPUTB3_REL_URL . "/formtagsinputb3/css");
define("FORMTAGSINPUTB3_IMAGES_REL_URL", FORMTAGSINPUTB3_REL_URL . "/formtagsinputb3/images");

xoops_load('XoopsFormLoader');

/**
 * FormTagsinputB3
 *
 * Bootstrap Tags Input with Autocomplete
 * jQuery plugin providing a Twitter Bootstrap user interface for managing tags
 * https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/
 *
 */
class FormTagsinputB3 extends \XoopsFormElement {

    private $_id;
    
    /**
     * Options
     *
     * @var array
     * @access private
     */
    private $_values = [];

    /**
     * Pre-selcted tags
     *
     * @var array
     * @access private
     */
    public $_tags = [];

    /**
     * Available tags
     *
     * @var array
     * @access private
     */
    public $_availableTags = [];

    /**
     * Free input
     *
     * @var bool
     * @access private
     */
    public $_freeInput = true;

    /**
     * Max number of tags
     *
     * @var int
     * @access private
     */
    public $_limit = 0;

    /**
     * Max tag lenght
     *
     * @var int
     * @access private
     */
    public $_maxChars = 255;

    /**
     * allowDuplicates
     *
     * @var bool
     * @access private
     */
    public $_allowDuplicates = false;

    /**
     * tagClass
     *
     * @var string
     * @acces private
     */
    public $_tagClass = '';

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
     * @param string $caption       Caption
     * @param string $name          "name" attribute
     * @param array  $values        array of tags
     * @param array  $availableTags array of availabl tags
     * @param bool   $freeInput     Allow creating tags which are not returned by typeahead's source (default: true)
     * @param int    $limit         Max number of tags (0 = unlimited)
     * @param int    $maxChars      Max tag lenght
     * @param bool   $allowDuplicates
     *
     */
    public function __construct(
            $caption,
            $name,
            $values = [],
            $availableTags = [],
            $freeInput = true,
            $limit = 0,
            $maxChars = 255,
            $allowDuplicates = false,
            $tagClass = 'label label-success'
    ) {
        $this->setCaption($caption);
        $this->setName($name);
        $this->setId($name);
        $this->setValue($values);
        //
        $this->_tags = $values;
        $this->_availableTags = $availableTags;
        $this->_freeInput = $freeInput;
        $this->_limit = (int) $limit;
        $this->_maxChars = (int) $maxChars;
        $this->_allowDuplicates = $allowDuplicates;
        $this->_tagClass = (string) $tagClass;
    }

    /**
     * Get an array of pre-selected values
     *
     * @param  bool $encode To sanitizer the text?
     * @return array
     */
    public function getValue($encode = false) {
        if (!$encode) {
            return $this->_values;
        }
        $value = [];
        foreach ($this->_values as $value) {
            $values[] = $value ? htmlspecialchars($value, ENT_QUOTES) : $value;
        }
        return $values;
    }

    /**
     * Set pre-selected values
     *
     * @param mixed $value
     */
    public function setValue($values) {
        if (is_array($values)) {
            foreach ($values as $value) {
                $this->_values[] = $value;
            }
        } elseif (isset($values)) {
            $this->_values[] = $values;
        }
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render() {
        static $isCommonFormTagsinputB3Included = false;
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
            if (!$isCommonFormTagsinputB3Included) {
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_CSS_REL_URL . '/bootstrap-tagsinput.css');
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_CSS_REL_URL . '/bootstrap-tokenfield.css');
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_CSS_REL_URL . '/typeahead.css');
                //$GLOBALS['xoTheme']->addStylesheet('', [], $commonCss);
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_JS_REL_URL . '/bootstrap-tagsinput.js');
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_JS_REL_URL . '/typeahead.js');
                //$GLOBALS['xoTheme']->addScript('', [], $commonJs);
                //
                $isCommonFormTagsinputB3Included = true;
            }
            $GLOBALS['xoTheme']->addScript('', [], $headJs);
            $GLOBALS['xoTheme']->addStylesheet('', [], $commonCss);
        } else {
            if (!$isCommonFormTagsinputB3Included) {
                $html .= "<style type='text/css'>@import url(" . XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_CSS_REL_URL . '/bootstrap-multiselect.css' . ");</style>\n";
                $html .= "<style type='text/css'>@import url(" . XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_CSS_REL_URL . '/bootstrap-tokenfield.css' . ");</style>\n";
                $html .= "<style type='text/css'>@import url(" . XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_CSS_REL_URL . '/typeahead.css' . ");</style>\n";
                //$html .= "<style>\n" . $commonCss . "\n" . "</style>\n";
                $html .= "<script src='" . XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_JS_REL_URL . '/bootstrap-tagsinput.js' . "' type='text/javascript'></script>\n";
                $html .= "<script src='" . XOOPS_URL . '/browse.php?' . FORMTAGSINPUTB3_JS_REL_URL . '/typeahead.js' . "' type='text/javascript'></script>\n";

                //$html .= "<script type='text/javascript'>\n" . $commonJs . "\n" . "</script>\n";
                $isCommonFormTagsinputB3Included = true;
            }
        }
        // add css just before html
        $css .= "<style>\n";
        $css .= "</style>\n";
        $html .= $css . "\n";
        //
        // html
        $html .= "<select {$this->getExtra()} name='{$this->getName()}[]' id='{$this->getName()}' title='{$this->getTitle()}' multiple>\n";
        foreach ($this->getValue() as $key => $value) {
            $optionName = $value;
            $html .= "<option value='" . htmlspecialchars($optionName, ENT_QUOTES) . "'>{$optionName}</option>\n";
        }
        $html .= "</select>\n";
        //
        // add js just after html
        $js .= "<script type='text/javascript'>\n";
        $js .= "
            $(document).ready(function() {
                var engine{$this->getName()} = new Bloodhound({
                    local: [
        ";
        foreach ($this->_availableTags as $availableTag) {
            $js .= " '{$availableTag}',";
        }
        $js .= "
                    ],
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    datumTokenizer: Bloodhound.tokenizers.whitespace
                });
                engine{$this->getName()}.initialize();
                $('#{$this->getName()}').tagsinput({
                    limit: {$this->_limit},
                    confirmKeys: [13, 188],
                    maxChars: {$this->_maxChars},
                    focusClass: '',
                    tagClass: '{$this->_tagClass}',
        ";
        $js .= ($this->_freeInput) ? " freeInput: true," : " freeInput: false,";
        $js .= ($this->_allowDuplicates) ? " allowDuplicates: true," : " allowDuplicates: false,";
        $js .= "
                    typeaheadjs: [null, { source: engine{$this->getName()}.ttAdapter() }],
                });
            });
            ";
        $js .= "</script>\n";
        $html .= $js . "\n";
        //
        return $html;
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
     */
    public function renderValidationJS() {
        // render custom validation code if any
        if (!empty($this->customValidationCode)) {
            return implode("\n", $this->customValidationCode);
            // generate validation code if required
        } elseif ($this->isRequired()) {
            $eltname = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            return "\nvar hasSelected = false; var selectBox = myform.{$eltname};" . "for (i = 0; i < selectBox.options.length; i++) { if (selectBox.options[i].selected == true && selectBox.options[i].value != '') { hasSelected = true; break; } }" . "if (!hasSelected) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
        }
        return '';
    }
}
