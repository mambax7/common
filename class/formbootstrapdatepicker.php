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
include_once dirname(__DIR__) . '/include/common.php';

xoops_loadLanguage('formdatepicker', 'common');
xoops_load('XoopsFormLoader');

class FormBootstrapDatepicker extends \XoopsFormText
{
    private $_id;
	private $_startDate = null;
	private $_endDate = null;
	private $_langcode = null;

    /**
     * Options
     *
     * @var array
     * @access private
     */
    public $_options = array();

     /**
     * Constructor
     *
     * @param string $caption   Caption
     * @param string $name      "name" attribute
     * @param int    $size      "size" attribute
     * @param bool   $maxlength "maxlength" attribute
     * @param int    $min_length should be > 1
     */
    public function __construct($caption, $name, $value = '', $startDate = null, $endDate = null)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->setValue($value);
		$this->_startDate = $startDate;
		$this->_endDate = $endDate;
		$this->_langcode = str_replace('_', '-' ,_LANGCODE);
		//
		$this->addOption('language', "{$this->_langcode}");
		$this->addOption('todayBtn', 'linked');
		$this->addOption('daysOfWeekHighlighted', '0');
		$this->addOption('calendarWeeks', true);
		$this->addOption('todayHighlight', true);
		if (!is_null($startDate)) {
			$this->addOption('startDate', $startDate);
		}
		if (!is_null($endDate)) {
			$this->addOption('endDate', $endDate);
		}
    }

    /**
     * set the "id" attribute for the element
     *
     * @param string $name "name" attribute for the element
     */
    function setId()
    {
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
     * Add an option
     *
     * @param string $name  "name"
     * @param string $value "value"
     */
    public function addOption($name = '', $value)
    {
        if ($name != '') {
            $this->_options[$name] = $value;
        } else {
            $this->_options[$name] = $name;
        }
    }

    /**
     * Add multiple options
     *
     * @param array $options Associative array of name->value pairs
     */
    public function addOptionArray($options)
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->addOption($k, $v);
            }
        }
    }

    /**
     * Get an array with all the options
     *
     * @return array Associative array of name->value pairs
     */
    public function getOptions()
    {
        $rets = array();
        foreach ($this->_options as $name => $value) {
            $rets[$name] = $value;
        }
        return $rets;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    function render()
    {
        $html = "\n";
        // common code
        static $isCommonFormDatepickerIncluded = false;
        if (is_object($GLOBALS['xoTheme'])) {
            if (!$isCommonFormDatepickerIncluded) {
                $isCommonFormDatepickerIncluded = true;
                $GLOBALS['xoTheme']->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
				$GLOBALS['xoTheme']->addStylesheet(COMMON_JS_URL . '/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker3.min.css');
                $GLOBALS['xoTheme']->addScript(COMMON_JS_URL . "/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js");
				$GLOBALS['xoTheme']->addScript(COMMON_JS_URL . "/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.{$this->_langcode}.min.js");
            }
        } else {
            if (!$isCommonFormDatepickerIncluded) {
                $isCommonFormDatepickerIncluded = true;
                $html .= "<style type='text/css'>@import url(" . XOOPS_URL . "/xoops.css);</style>\n";
                $html .= "<style type='text/css'>@import url(" . COMMON_JS_URL . "/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker3.min.css);</style>\n";
                $html .= "<script src='" . XOOPS_URL . "/browse.php?Frameworks/jquery/jquery.js' type='text/javascript'></script>\n";
                $html .= "<script src='" . COMMON_JS_URL . "/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js' type='text/javascript'></script>\n";
                $html .= "<script src='" . COMMON_JS_URL . "/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.{$this->_langcode}.min.js\n";
            }
        }
        // not common code
        $css = "";
        $js = "";
        if (is_object($GLOBALS['xoTheme'])) {
            $GLOBALS['xoTheme']->addStylesheet('', array(), $css);
            $GLOBALS['xoTheme']->addScript('','', $js);
        } else {
            $html .= "<style type='text/css'>\n{$css}\n</style>\n";
            $html .= "<script type='text/javascript'>\n{$js}\n</script>\n";
        }
        $html .= parent::render();
        $html .= "
            <script>
			    $('input[name=\"{$this->getName()}\"]').datepicker({\n";
        // set options - start
        foreach ($this->getOptions() as $name => $value) {
            $html .= "{$name}: ";
            if ($value === true) {
                $html .= "true,";
            } elseif ($value === false) {
                $html .= "false,";
            } else {
                $html .= "'{$value}',";
            }
            $html .= "\n";
        }
        $html .= "
				});
            </script>\n";
        return $html;
//        $html .= "<input type='text' name='{$this->getName()}' title='{$this->getTitle()}' id='{$this->getName()}' class='{$this->getClass()}' size='{$this->getSize()}' maxlength='{$this->getMaxlength()}' value='{$this->getValue()}' {$this->getExtra()} />";
        return $html;
    }
}
