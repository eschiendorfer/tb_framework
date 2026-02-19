<h1>{$title}</h1>

{foreach from=$components item=component}
    <section>
        <b>{$component.name}{if isset($component.channel)} ({$component.channel}){/if}:</b>
        <div class="flex flex-wrap items-stretch gap-4 mt-2">
            {foreach from=$component.variants item=variant}
                <div class="flex self-stretch flex-col items-center justify-between">
                    <span class="text-xs text-gray-600 mb-3">{$variant.style}</span>
                    <div>
                        {$variant.output}
                    </div>
                </div>
            {/foreach}
        </div>
    </section>
{/foreach}

