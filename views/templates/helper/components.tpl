<h1>{$title}</h1>

{if empty($components)}
    <p>Keine Eintr&auml;ge f&uuml;r die gew&auml;hlten Filter.</p>
{/if}

{foreach from=$components item=component}
    <section class="mb-12">
        <header class="mb-4">
            <div class="rounded-lg border border-gray-200 bg-white px-3 py-3 shadow-sm">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <div class="font-bold text-gray-900">{$component.name|escape:'html':'UTF-8'}</div>
                </div>

                <div class="space-y-2 text-xs text-gray-700">
                    {if !empty($component.styles)}
                        <div class="flex flex-wrap items-start gap-x-4 gap-y-1">
                            <div class="w-24 shrink-0 text-xs font-bold uppercase text-gray-600">Styles</div>
                            <div class="flex min-w-0 flex-1 flex-wrap items-center gap-2">
                                {foreach from=$component.styles item=style}
                                    <span class="{$css_selector.badge_default.small|escape:'html':'UTF-8'}">{$style|escape:'html':'UTF-8'}</span>
                                {/foreach}
                            </div>
                        </div>
                    {/if}

                    {if !empty($component.json_demo_structure)}
                        <div class="flex flex-wrap items-start gap-x-4 gap-y-2 pt-1">
                            <div class="w-24 shrink-0 text-xs font-bold uppercase text-gray-600">JSON</div>
                            <div class="grid min-w-0 flex-1 gap-2">
                                <details class="rounded-md border border-gray-200 bg-gray-50 px-3 py-2">
                                    <summary class="cursor-pointer font-bold text-gray-900">Demo-Struktur anzeigen</summary>
                                    <pre class="mt-2 overflow-auto rounded bg-white p-3 text-xs text-gray-900">{$component.json_demo_structure|escape:'html':'UTF-8'}</pre>
                                </details>
                            </div>
                        </div>
                    {/if}

                    {if !empty($component.usage_examples)}
                        <div class="flex flex-wrap items-start gap-x-4 gap-y-1 pt-1">
                            <div class="w-24 shrink-0 text-xs font-bold uppercase text-gray-600">Beispiele</div>
                            <div class="grid min-w-0 flex-1 gap-2">
                                {foreach from=$component.usage_examples item=usage_example}
                                    <div class="rounded-md border border-gray-200 bg-gray-50 px-3 py-2">
                                        <div class="mb-2 flex flex-wrap items-center gap-2">
                                            <span class="font-bold text-gray-900">{$usage_example.label|escape:'html':'UTF-8'}</span>
                                            {if !empty($usage_example.target_entity_types)}
                                                {foreach from=$usage_example.target_entity_types item=target_entity_type}
                                                    <span class="rounded bg-white px-2 py-0.5 font-mono text-gray-600">{$target_entity_type|escape:'html':'UTF-8'}</span>
                                                {/foreach}
                                            {/if}
                                        </div>

                                        {if !empty($usage_example.examples)}
                                            <div class="flex flex-wrap gap-2">
                                                {foreach from=$usage_example.examples item=example}
                                                    <code class="rounded bg-white px-2 py-1 font-mono text-sm text-gray-900">{$example|escape:'html':'UTF-8'}</code>
                                                {/foreach}
                                            </div>
                                        {/if}
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        </header>

        <div class="flex flex-wrap items-stretch gap-8 mt-4">
            {foreach from=$component.variants item=variant}
                <div class="flex self-stretch flex-col items-center justify-between mb-3">
                    <div class="mb-3 flex flex-wrap items-center justify-center gap-2">
                        <span class="{$variant.output_badge.class|escape:'html':'UTF-8'}">
                            {if !empty($variant.output_badge.icon)}
                                <span class="icon {$variant.output_badge.icon|escape:'html':'UTF-8'}" style="width:0.75rem;height:0.75rem;background-color:currentColor;"></span>
                            {/if}
                            {$variant.output_badge.label|escape:'html':'UTF-8'}
                        </span>
                        <span class="{$css_selector.badge_default.small|escape:'html':'UTF-8'}">{$variant.style|escape:'html':'UTF-8'}</span>
                    </div>
                    <div>
                        {$variant.output}
                    </div>
                </div>
            {/foreach}
        </div>
    </section>
{/foreach}
