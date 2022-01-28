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
 * @package         common
 * @since           1.00
 * @author          luciorota, studiopas
 * @version         svn:$Id$
 */
*}>
<{include file='db:common_header.tpl'}>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h3 class="panel-title">TESTS</h3>
    </div>
    <div class="panel-body">
        <ul>
        <{foreach from=$tests item=test}>
            <li><a href="<{$test}>" target="_blank"><{$test}></a></li>
        <{/foreach}>
        </ul>
    </div>
</div>

<{include file='db:common_footer.tpl'}>
