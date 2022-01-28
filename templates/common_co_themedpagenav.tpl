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
<nav>
    <ul class="pagination pagination-sm">
    <{if ($prev >= 0)}>
        <li>
            <a href="<{$pagenav.url}><{$prev}><{$pagebav.extra}>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <{/if}>
<{section name=pages start=1 loop=$total_pages+1 step=1}>
    <{assign var='counter' value=$smarty.section.pages.index}>
    <{if ($counter == $current_page)}>
        <li class="active">
            <a href="#"><{$counter}> <span class="sr-only">(current)</span></a>
        </li>
    <{elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || $counter == 1 || $counter == $total_pages)}>
        <{if ($counter == $total_pages && $current_page < $total_pages - $offset)}>
            <li class="disabled">
                <a href="#" aria-label="..."><span aria-hidden="true">...</span></a>
            </li>
        <{/if}>
        <li>
            <a href="<{$pagenav.url}><{math equation="((c - 1) * p)" c=$counter p=$pagenav.perpage}><{$pagenav.extra}>"><{$counter}></a>
        </li>
        <{if ($counter == 1 && $current_page > 1 + $offset)}>
            <li class="disabled">
                <a href="#" aria-label="..."><span aria-hidden="true">...</span></a>
            </li>
        <{/if}>
    <{/if}>
<{/section}>
    <{if ($pagenav.total > $next)}>
        <li>
            <a href="<{$pagenav.url}><{$next}><{$pagenav.extra}>" aria-label="Previous">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <{/if}>
    </ul>
</nav>
