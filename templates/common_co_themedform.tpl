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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
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
<form class="form-horizontal" name="<{$form.getName}>" id="<{$form.getName}>" action="<{$form.getAction}>" method="<{$form.getMethod}>" onsubmit="return xoopsFormValidate_<{$form.getName}>();"<{$form.getExtra}> >
<h4><{$form.title}></h4>
<{foreach item=formElement from=$formElements}>
<{if ($formElement.isHidden == true)}>
    <!-- NOP -->
<{else}>
<{if ($formElement.is_string == true)}>
    <{$formElement.render}>
<{else}>
<div class="form-group<{if ($formElement.isRequired == true)}> has-error<{/if}>" title="<{$formElement.getDescription}>" >
    <label for="<{$formElement.getName}>" class="col-sm-2 control-label">
        <{$formElement.getCaption}>
    </label>
    <div class="col-sm-10">
        <{$formElement.render}>
    <{if ($formElement.getDescription != '')}>
        <br>
        <small><{$formElement.getDescription}></small>
    <{/if}>
    </div>
</div>
<{/if}>
<{/if}>
<{if $formElement.getClass <> ''}>
<script type="text/javascript">
    $("form#<{$form.getName}> [name='<{$formElement.getName}>']").addClass("<{$formElement.getClass}>");
</script>
<{/if}>
<{/foreach}>
<style type="text/css">
    form#<{$form.getName}> select.form-control {
        width:auto;
        display:inline-block;
    }
    form#<{$form.getName}> input[type=radio] {
        width:auto;
        display:inline-block;
    }
</style>
<script type="text/javascript">
    $("form#<{$form.getName}> input[type=text]").addClass("form-control");
    $("form#<{$form.getName}> input[type=number]").addClass("form-control");
    $("form#<{$form.getName}> input[type=tel]").addClass("form-control");
    $("form#<{$form.getName}> input[type=button]").addClass("btn btn-default");
    $("form#<{$form.getName}> input[type=submit]").addClass("btn btn-default");
    $("form#<{$form.getName}> input[type=reset]").addClass("btn btn-default");
    $("form#<{$form.getName}> select").addClass("form-control");
    //$("form#<{$form.getName}> input[type=radio]").addClass("form-control");
    $("form#<{$form.getName}> textarea").addClass("form-control");

    $("form#<{$form.getName}> input[type=text]").attr("autocomplete", "off");
    $("form#<{$form.getName}> input[type=tel]").attr("autocomplete", "off");
    $("form#<{$form.getName}> select").attr("autocomplete", "off");

</script>


<!-- hidden form elements here -->
<{foreach item=formElement from=$formElements}>
<{if ($formElement.isHidden == true)}>
    <{$formElement.render}>
<{else}>
<{/if}>
<{/foreach}>

</form>
</div>
