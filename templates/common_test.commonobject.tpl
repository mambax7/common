<{include file='db:common_header.tpl'}>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h3 class="panel-title">Test commonobject</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-8">
                <ul>
                <{foreach from=$tests key=id item=test}>
                    <li>
                        <{$test|@var_dump}>
                        <br>
                        <a href="?op=edit&id=<{$id}>"><{$smarty.const._EDIT}></a>
                        &nbsp;
                        <a href="?op=delete&id=<{$id}>"><{$smarty.const._DELETE}></a>
                    </li>
                <{/foreach}>
                </ul>
            </div>
            <div class="col-xs-4">
                <{$form}>
            </div>
        </div>
    </div>
</div>

<{include file='db:common_footer.tpl'}>
