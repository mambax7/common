<{include file='db:common_header.tpl'}>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h3 class="panel-title">Test commonobject</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-condensed table-hover">
                    <tbody>
                    <{foreach from=$tests key=id item=test}>
                        <tr>
                        <{foreach from=$test|@array_keys item=key}>
                            <td><span title="<{$key}>"><{$test[$key]}></span></td>
                        <{/foreach}>
                            <th>
                                <a href="?op=edit&id=<{$id}>"><{$smarty.const._EDIT}></a>
                                &nbsp;
                                <a href="?op=delete&id=<{$id}>"><{$smarty.const._DELETE}></a>                
                            </th>
                        </tr>
                    <{/foreach}>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <{$form}>
            </div>
        </div>
    </div>
</div>

<{include file='db:common_footer.tpl'}>
