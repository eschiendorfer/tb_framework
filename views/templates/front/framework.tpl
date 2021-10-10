<ul>
    <li class="primary">Elements</li>
    <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'buttons'])}">Buttons</a></li>
    <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'badges'])}">Badges</a></li>
    <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'alerts'])}">Alerts</a></li>
    <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'spinners'])}">Spinners</a></li>
    <li class="primary">Components</li>
    <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'cards', 'component'=>1])}">Cards</a></li>
</ul>

<div>
    {$framework_content}
</div>

<style>
   .primary {
       font-weight: bold;
       margin-top: 10px;
   }
</style>