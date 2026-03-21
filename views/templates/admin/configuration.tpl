<ul class="nav nav-tabs" role="tablist">
    <li class="active">
        <a data-toggle="tab" href="#tabAuth">
            <i class="icon-cogs"></i> Autenticação
        </a>
    </li>

    <li>
        <a data-toggle="tab" href="#tabConfig">
            <i class="icon-cogs"></i> Configuração
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#tabHelp">
            <i class="icon-question-circle"></i> Ajuda
        </a>
    </li>
</ul>
<div class='tab-content'>    
    <div class="tab-pane active in" id="tabConfig">{$tabs['config']}</div>

    <div class="tab-pane" id="tabHelp">
        <div class="panel">
            {include file=$modules_path|cat:"agcliente/views/templates/hook/includes/tab_help.tpl"}
        </div>
    </div>

</div>
