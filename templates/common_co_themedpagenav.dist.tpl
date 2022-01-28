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
$pagenav.total
$pagenav.perpage
$pagenav.current
$pagenav.url
$pagenav.extra
$offset
$total_pages
$prev
$current_page
$next
*}>
<div id="xo-pagenav">
<{if ($prev >= 0)}>
    <a class="xo-pagarrow" href="<{$pagenav.url}><{$prev}><{$pagebav.extra}>"><u>&laquo;</u></a>
<{/if}>
<{section name=pages start=1 loop=$total_pages+1 step=1}>
    <{assign var='counter' value=$smarty.section.pages.index}>
    <{if ($counter == $current_page)}>
        <strong class="xo-pagact" >(<{$counter}>)</strong>
    <{elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || $counter == 1 || $counter == $total_pages)}>
        <{if ($counter == $total_pages && $current_page < $total_pages - $offset)}>
            ...&nbsp;
        <{/if}>
        <a class="xo-counterpage" href="<{$pagenav.url}><{math equation="((counter - 1) * perpage)" counter=$counter perpage=$pagenav.perpage}><{$this->extra}>"><{$counter}></a>
        <{if ($counter == 1 && $current_page > 1 + $offset)}>
            ...&nbsp;
        <{/if}>
    <{/if}>
    <{assign var=counter value=$counter+1}>
<{/section}>
<{if ($pagenav.total > $next)}>
    <a class="xo-pagarrow" href="<{$pagenav.url}><{$next}><{$pagebav.extra}>"><u>&raquo;</u></a>
<{/if}>
</div>
