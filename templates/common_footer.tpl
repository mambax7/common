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

<div class="footer">
    <!-- footer menu -->
    <div class="common_adminlinks">
    <{foreach item='footerMenuItem' from=$moduleInfoSub}>
        <a href='<{$smarty.const.COMMON_URL}>/<{$footerMenuItem.url}>'><{$footerMenuItem.name}></a>
    <{/foreach}>
    <{if $isAdmin == true}>
        <br>
        <a href="<{$smarty.const.COMMON_URL}>/admin/index.php"><{$smarty.const._MD_COMMON_ADMIN_PAGE}></a>
    <{/if}>
    </div>
</div>
