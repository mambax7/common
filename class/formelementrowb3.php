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
    $currentPath = str_replace(strpos( $currentPath, "\\\\", 2 ) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $currentPath);
}



/**
 * A group of form elements
 */
class FormElementrowB3 extends \XoopsFormElement
{
    /**
     * array of form element objects
     *
     * @var array
     * @access private
     */
    private $_elements = array();

    /**
     * required elements
     *
     * @var array
     */
    public $_required = array();

    /**
     * columns
     *
     * @var string
     * @access private
     */
    private $_columns;
    
    /**
     * constructor
     *
     * @param string $caption   Caption for the group.
     * @param string $delimeter HTML to separate the elements
     * @param string $name
     *
     */
    public function __construct($caption, $columns = 0, $name = '')
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->_columns = $columns;
    }

    /**
     * Is this element a container of other elements?
     *
     * @return bool true
     */
    public function isContainer()
    {
        return true;
    }

    /**
     * Find out if there are required elements.
     *
     * @return bool
     */
    public function isRequired()
    {
        return !empty($this->_required);
    }

    /**
     * Add an element to the group
     *
     * @param XoopsFormElement $formElement {@link XoopsFormElement} to add
     * @param bool             $required
     *
     */
    public function addElement(\XoopsFormElement $formElement, $required = false)
    {
        $this->_elements[] = $formElement;
        if (!$formElement->isContainer()) {
            if ($required) {
                $formElement->_required = true;
                $this->_required[]      = $formElement;
            }
        } else {
            $required_elements = $formElement->getRequired();
            $count             = count($required_elements);
            for ($i = 0; $i < $count; ++$i) {
                $this->_required[] = &$required_elements[$i];
            }
        }
    }

    /**
     * get an array of "required" form elements
     *
     * @return array array of {@link XoopsFormElement}s
     */
    public function &getRequired()
    {
        return $this->_required;
    }

    /**
     * Get an array of the elements in this group
     *
     * @param  bool $recurse get elements recursively?
     * @return XoopsFormElement[]  Array of {@link XoopsFormElement} objects.
     */
    public function &getElements($recurse = false)
    {
        if (!$recurse) {
            return $this->_elements;
        } else {
            $html   = array();
            $count = count($this->_elements);
            for ($i = 0; $i < $count; ++$i) {
                if (!$this->_elements[$i]->isContainer()) {
                    $html[] = &$this->_elements[$i];
                } else {
                    $elements = &$this->_elements[$i]->getElements(true);
                    $count2   = count($elements);
                    for ($j = 0; $j < $count2; ++$j) {
                        $html[] = &$elements[$j];
                    }
                    unset($elements);
                }
            }

            return $html;
        }
    }

    /**
     * Get the delimiter of this group
     *
     * @param  bool $encode To sanitizer the text?
     * @return string The delimiter
     */
    public function getDelimeter($encode = false)
    {
        return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $this->_delimeter)) : $this->_delimeter;
    }

    /**
     * prepare HTML to output this group
     *
     * @return string HTML output
     */
    public function render()
    {
        $elements = $this->getElements();
        $countElements = count($elements);
        if ($this->_columns == 0) {
            $this->_columns = floor($countElements / 12);
        }
        $html = '';
        $html .= "<div class = 'row'>";
        foreach($elements as $element) {
            $required = ($element->_required) ? "*" : "";
            $html .= "<div class='col-md-{$this->_columns}'>";
            //$html .= "<div class='form-group'>";
            $html .= "<label for='{$element->getName()}'>{$element->getCaption()}{$required}</label>";
            $html .= "<span id='help{$element->getName()}' class='help-block'>{$element->getDescription()}</span>";
            $html .= $element->render();
            //$html .= "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";
        return $html;
        
        
    }
}
