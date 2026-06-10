<form method="get" action="{$link->getModuleLink('tb_framework', 'framework')}" class="mb-5 p-3 bg-gray-100 rounded">
    {if !empty($framework_current_kind)}
        <input type="hidden" name="catalog_kind" value="{$framework_current_kind|escape:'html':'UTF-8'}">
    {/if}
    {if !empty($framework_current_type)}
        <input type="hidden" name="type" value="{$framework_current_type|escape:'html':'UTF-8'}">
    {/if}
    {if !empty($framework_current_component)}
        <input type="hidden" name="component" value="1">
    {/if}

    <div class="space-y-2">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <div class="w-24 shrink-0 text-xs font-bold uppercase text-gray-600">Output</div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                {foreach from=$framework_channel_filters item=channel_filter}
                    <label class="inline-flex items-center gap-1.5">
                        <input
                            type="checkbox"
                            name="channels[]"
                            value="{$channel_filter.value|escape:'html':'UTF-8'}"
                            {if $channel_filter.selected}checked{/if}
                        >
                        <span>{$channel_filter.label}</span>
                    </label>
                {/foreach}
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <div class="w-24 shrink-0 text-xs font-bold uppercase text-gray-600">Daten</div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                {foreach from=$framework_data_input_filters item=data_input_filter}
                    <label class="inline-flex items-center gap-1.5">
                        <input
                            type="checkbox"
                            name="data_input_modes[]"
                            value="{$data_input_filter.value|escape:'html':'UTF-8'}"
                            {if $data_input_filter.selected}checked{/if}
                        >
                        <span>{$data_input_filter.label}</span>
                    </label>
                {/foreach}
            </div>
        </div>

        {if !empty($framework_target_entity_filters)}
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <div class="w-24 shrink-0 text-xs font-bold uppercase text-gray-600">Entity</div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                    {foreach from=$framework_target_entity_filters item=target_entity_filter}
                        <label class="inline-flex items-center gap-1.5">
                            <input
                                type="checkbox"
                                name="target_entities[]"
                                value="{$target_entity_filter.value|escape:'html':'UTF-8'}"
                                {if $target_entity_filter.selected}checked{/if}
                            >
                            <span>{$target_entity_filter.label}</span>
                        </label>
                    {/foreach}
                </div>
            </div>
        {/if}
    </div>

    <div class="mt-3">
        <button type="submit" class="{$css_selector.button_secondary.default}">Anwenden</button>
    </div>
</form>

<div class="flex">
    <nav class="shrink-0 w-64 border-r-2 pr-1">
        {foreach from=$framework_navigation_sections item=navigation_section}
            <section class="mb-6">
                <h2 class="text-sm font-bold uppercase tracking-wide text-gray-700 mb-2">{$navigation_section.label}</h2>
                {if empty($navigation_section.items)}
                    <p class="text-xs text-gray-500">Keine Treffer</p>
                {/if}
                <ul>
                    {foreach from=$navigation_section.items item=navigation_item}
                        <li>
                            <a class="flex items-center justify-between gap-3 py-1 rounded {if $navigation_item.active}bg-gray-100 font-bold{/if}" href="{$navigation_item.url}">
                                <span>{$navigation_item.label}</span>
                                <span class="{$css_selector.badge_default.default}">{$navigation_item.count}</span>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </section>
        {/foreach}
    </nav>

    <div class="shrink w-full max-w-full min-w-0 ml-8">
        {$framework_content}
    </div>
</div>
