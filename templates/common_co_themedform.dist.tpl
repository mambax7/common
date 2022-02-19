<{*
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/*
 * Iscritti module
 *
 * @copyright       XOOPS Project https://xoops.org/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         iscritti
 * @since           4.00
 * @author          luciorota <lucio.rota@gmail.com>, nursind <info@nursind.it>
 * @version         svn:$Id$
 */
*}>
<{*
$form.getName
$form.getAction
$form.getTitle
$form.getMethod
$form.getExtra
$form.getSummary

$formElements as $formElement
$formElement.getAccessKey
$formElement.getAccessString
$formElement.getCaption
$formElement.getClass
$formElement.getDescription
$formElement.getExtra
$formElement.getFormType
$formElement.getName
$formElement.getNocolspan
$formElement.getTitle
$formElement.isContainer
$formElement.isHidden
$formElement.isRequired
$formElement.render
$formElement.renderValidationJS
*}>
<div>
<form name="<{$form.getName}>" id="<{$form.getName}>" action="<{$form.getAction}>" method="<{$form.getMethod}>" onsubmit="return xoopsFormValidate_<{$form.getName}>();"<{$form.getExtra}> >
<table width="100%" class="outer" cellspacing="1">
<tr>
    <th colspan="2"><{$form.title}></th>
</tr>

<{foreach item=formElement from=$formElements}>
<{if ($formElement.isHidden == true)}>
    <!-- NOP -->
<{else}>
<tr>
<{if ($formElement.is_string == true)}>
    <td colspan="2"><{$formElement.render}></td>
<{else}>
    <td>
        <div class="xoops-form-element-caption<{if ($formElement.isRequired == true)}>-required<{/if}>">
            <span class="caption-text"><{$formElement.getCaption}></span>
            <span class="caption-marker">*</span>
        </div>
        <div class="xoops-form-element-help"><{$formElement.getDescription}></div>
    </td>
    <td><{$formElement.render}></td>
<{/if}>
</tr>
<{/if}>
<{/foreach}>
</table>

<!-- hidden form elements here -->
<{foreach item=formElement from=$formElements}>
<{if ($formElement.isHidden == true)}>
    <{$formElement.render}>
<{else}>
<{/if}>
<{/foreach}>

</form>
</div>
