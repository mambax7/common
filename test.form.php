<?php

$currentFile = basename(__FILE__);
include __DIR__ . '/header.php';

$xoopsOption['template_main'] = "{$commonHelper->getModule()->dirname()}_test.form.tpl";
include XOOPS_ROOT_PATH . '/header.php';

//$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addStylesheet(COMMON_CSS_URL . '/module.css');
//$xoTheme->addStylesheet(COMMON_CSS_URL . '/' . $currentFile . '.css'); // ie: index.php.css
$xoTheme->addScript(COMMON_JS_URL . '/module.js');
//$xoTheme->addScript(COMMON_JS_URL . '/' . $currentFile . '.js'); // ie: index.php.js
// template: common\breadcrumb
xoops_load('breadcrumb', 'common');
$breadcrumb = new common\breadcrumb();
$breadcrumb->addLink($commonHelper->getModule()->getVar('name'), COMMON_URL);
$xoopsTpl->assign('commonBreadcrumb', $breadcrumb->render());

// template: isAdmin
$GLOBALS['xoopsTpl']->assign('$isAdmin', $isAdmin);

xoops_load('XoopsUserUtility');



xoops_load('XoopsRequest');
$op = XoopsRequest::getCmd('op', '');
switch ($op) {
    default:
        break;

    case 'save':
        var_dump($_POST);
        break;
}



// template: form
xoops_load('XoopsFormLoader');
xoops_load('ThemedForm', 'common');
$formObj = new common\ThemedForm('title', 'iscrittoForm', '', 'POST', true);
$formObj->setExtra('enctype="multipart/form-data"');

xoops_load('FormGoogleMap', 'common');
$formObj->addElement(new common\FormGoogleMap('FormGoogleMap', 'FormGoogleMap', array(), $commonHelper->getConfig('GoogleMapsAPIKey')));

xoops_load('FormXoopsImage', 'common');
$formObj->addElement(new common\FormXoopsImage('FormXoopsImage', 'FormXoopsImage', 255, 255, '', null));

xoops_load('FormAjaxImageManager', 'common');
$formObj->addElement(new common\FormAjaxImageManager('FormAjaxImageManager', 'FormAjaxImageManager', '', array()));

xoops_load('FormCodiceFiscale', 'common');
$formObj->addElement(new common\FormCodiceFiscale('FormCodiceFiscale', 'FormCodiceFiscale'));

xoops_load('FormCap', 'common');
$formObj->addElement(new common\FormCap('FormCap', 'FormCap'));

xoops_load('FormTelephonenumber', 'common');
$formObj->addElement(new common\FormTelephonenumber('FormTelephonenumber', 'FormTelephonenumber', 10, 10, ''));

xoops_load('FormEmail', 'common');
$formObj->addElement(new common\FormEmail('FormEmail', 'FormEmail'));

xoops_load('FormDatepicker', 'common');
$formObj->addElement(new common\FormDatepicker('FormDatepicker', 'FormDatepicker'));

xoops_load('FormDatepickerB3', 'common');
$formObj->addElement(new common\FormDatepickerB3('FormDatepickerB3', 'FormDatepickerB3'));
//
//$formObj->addElement(new \XoopsFormSelectGroup('XoopsFormSelectGroup', 'XoopsFormSelectGroup', false, null, 5, true));

//xoops_load('FormSelectGroup', 'common');
//$formObj->addElement(new common\FormSelectGroup('FormSelectGroup<br>multiple true', 'FormSelectGroup-multiple', false, null, 10, true));
//$formObj->addElement(new common\FormSelectGroup('FormSelectGroup<br>multiple false', 'FormSelectGroup', false, null, 10, false));

xoops_load('FormSelectGroupB3', 'common');
$FormSelectGroupB3 = XoopsRequest::getArray('FormSelectGroupB3', array());
$formObj->addElement(new common\FormSelectGroupB3('FormSelectGroupB3<br>multiple true', 'FormSelectGroupB3', true, $FormSelectGroupB3, 10, true));

xoops_load('FormMultiSelectB3', 'common');
//$FormMultiSelectB3 = XoopsRequest::getArray('FormMultiSelectB3', array());
//$testFormMultiSelectB3 = new common\FormMultiSelectB3('FormMultiSelectB3_3', 'FormMultiSelectB3_3', $FormMultiSelectB3, 3, true);
//for ($i = 1; $i <= 10; $i++) {
//    $testFormMultiSelectB3->addOption("{$i}", "{$i}_nome", "{$i}_descrizione");
//}
//$formObj->addElement($testFormMultiSelectB3);

//$testFormMultiSelectB3 = new common\FormMultiSelectB3('FormMultiSelectB3_0', 'FormMultiSelectB3_0', $FormMultiSelectB3, 0, true);
//for ($i = 1; $i <= 10; $i++) {
//    $testFormMultiSelectB3->addOption("{$i}", "{$i}_nome", "{$i}_descrizione");
//}
//$formObj->addElement($testFormMultiSelectB3);

//xoops_load('FormFileinputB3', 'common');
//$formObj->addElement(new common\FormFileinputB3('FormFileinputB3', 'FormFileinputB3'));

xoops_load('FormTagsinputB3', 'common');
$FormTagsinputB3 = XoopsRequest::getArray('FormTagsinputB3', []);
$availableTags = ["vanessa", "maria", "gaia"];
$freeInput = true;
$limit = 0;
$maxChars = 255;
$allowDuplicates = false;
$tagClass = 'label label-success';
$formObj->addElement(new common\FormTagsinputB3('FormTagsinputB3', 'FormTagsinputB3', $FormTagsinputB3, $availableTags, $freeInput, $limit, $maxChars, $allowDuplicates, $tagClass));

xoops_load('FormToggleB3', 'common');
$FormToggleB3 = XoopsRequest::getBool('FormToggleB3', true);
$on = _YES;
$off = _NO;
$size = "normal";
$onstyle = "primary";
$offstyle = "default";
$formObj->addElement(new common\FormToggleB3('FormToggleB3', 'FormToggleB3', $FormToggleB3, $on, $off, $size, $onstyle, $offstyle));



$formObj->addElement(new \XoopsFormHidden('op', 'save'));
$button_submit = new \XoopsFormButton('', 'submit', _SUBMIT, 'submit');
$button_submit->setExtra('onclick="this.form.elements.op.value=\'save\'"');
$formObj->addElement($button_submit);

$xoopsTpl->assign('form', $formObj->render());

//xoops_load('functions', 'common');
//$num = rand();
//echo $num . " = " . common\functions::bytesToSize1024($num);



include __DIR__ . '/footer.php';
