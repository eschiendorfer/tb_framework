<div class="flex">
    <ul class="shrink-0 w-64 border-r-2">

        <li class="font-bold">Components</li>
        {foreach from=$framework_component_navigation item=navigation_item}
            <li>
                {if $navigation_item.route_component}
                    {assign var=component_link value=$link->getModuleLink('tb_framework', 'framework', ['type' => $navigation_item.route_type, 'component' => 1])}
                {else}
                    {assign var=component_link value=$link->getModuleLink('tb_framework', 'framework', ['type' => $navigation_item.route_type])}
                {/if}
                <a class="flex justify-between" href="{$component_link}">
                    <span>{$navigation_item.label}</span>
                    <span class="mr-5">
                        {if $navigation_item.has_web}
                            <span class="{$css_selector.badge_success.small}" style="font-size: 10px; padding: 2px 4px;">Web</span>
                        {/if}
                        {if $navigation_item.has_email}
                            <span class="{$css_selector.badge_default.small}" style="font-size: 10px; padding: 2px 4px;">Mail</span>
                        {/if}
                        {if $navigation_item.has_css}
                            <span class="{$css_selector.badge_warning.small}" style="font-size: 10px; padding: 2px 4px;">CSS</span>
                        {/if}
                    </span>
                </a>
            </li>
        {/foreach}
    </ul>

    <div class="shrink-1 w-full max-w-full min-w-0 ml-8">
        {$framework_content}
    </div>
</div>
