<div class="flex">
    <ul class="shrink-0 w-40 border-r-2">
        <li class="primary">Elements</li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'buttons'])}">Buttons</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'badges'])}">Badges</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'alerts'])}">Alerts</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'spinners'])}">Spinners</a></li>
        <li class="primary">Components</li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'card', 'component'=>1])}">Cards</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'imagecloud', 'component'=>1])}">Imageclouds</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'header', 'component'=>1])}">Headers</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'menu', 'component'=>1])}">Menus</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'list', 'component'=>1])}">Lists</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'table', 'component'=>1])}">Tables</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'accordion', 'component'=>1])}">Accordion</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'progressbar', 'component'=>1])}">Progessbar</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'review', 'component'=>1])}">Reviews</a></li>
        <li class="primary">Containers</li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'flexbox', 'component'=>1])}">Flexbox</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'toast', 'component'=>1])}">Toats</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'carousel', 'component'=>1])}">Carousels</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'modal', 'component'=>1])}">Modals</a></li>
        <li><a href="{$link->getModuleLink('tb_framework', 'framework', ['type'=>'tab', 'component'=>1])}">Tabs</a></li>
    </ul>

    <div class="shrink-1 w-full max-w-full min-w-0 ml-8">
        {$framework_content}
    </div>
</div>


<style>
   .primary {
       font-weight: bold;
       margin-top: 10px;
   }
</style>