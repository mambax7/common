<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <{foreach from=$breadcrumb item="bread" name="bcloop"}>
        <{if ($bread.link)}>
            <li class="breadcrumb-item"><a href="<{$bread.link}>" title="<{$bread.title}>"><{$bread.title}></a></li>
        <{else}>
            <li class="breadcrumb-item active"><{$bread.title}></li>
        <{/if}>
        <{/foreach}>
    </ol>
</nav>
