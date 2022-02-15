<{include file='db:common_header.tpl'}>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h3 class="panel-title">TESTS</h3>
    </div>
    <div class="panel-body">
        <ul>
        <{foreach from=$tests item=test}>
            <li><a href="<{$test}>"><{$test}></a></li>
        <{/foreach}>
        </ul>
    </div>
</div>

<{include file='db:common_footer.tpl'}>
