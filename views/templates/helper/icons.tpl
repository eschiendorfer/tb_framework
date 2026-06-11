<h1>{$title|escape:'html':'UTF-8'}</h1>

<div class="mb-6 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
    <div class="font-bold text-gray-900">Nutzung</div>
    <div class="mt-2 grid gap-2">
        <code class="block rounded bg-gray-50 px-2 py-1 font-mono text-xs text-gray-900">&lt;i class=&quot;icon icon-trash&quot;&gt;&lt;/i&gt;</code>
        <code class="block rounded bg-gray-50 px-2 py-1 font-mono text-xs text-gray-900">&lt;i class=&quot;icon icon-trash w-6 h-6 bg-gray-700&quot;&gt;&lt;/i&gt;</code>
        <code class="block rounded bg-gray-50 px-2 py-1 font-mono text-xs text-gray-900">&lt;i class=&quot;icon-big icon-boardgame-big&quot;&gt;&lt;/i&gt;</code>
    </div>
</div>

{if empty($icon_groups)}
    <p>Keine Icons gefunden.</p>
{/if}

{foreach from=$icon_groups item=icon_group}
    <section class="mb-10">
        <header class="mb-4 flex flex-wrap items-center gap-2">
            <h2 class="text-lg font-bold text-gray-900">{$icon_group.label|escape:'html':'UTF-8'}</h2>
            <span class="{$css_selector.badge_default.small|escape:'html':'UTF-8'}">{$icon_group.icons|count}</span>
        </header>

        <div class="grid grid-cols-2 gap-3 md:grid-cols-4 xl:grid-cols-6">
            {foreach from=$icon_group.icons item=icon}
                <article class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                    <div class="flex h-16 items-center justify-center rounded bg-gray-50">
                        {if $icon.is_large}
                            <i class="icon-big {$icon.class|escape:'html':'UTF-8'}" style="width:4.5rem;height:3rem;"></i>
                        {else}
                            <i class="icon {$icon.class|escape:'html':'UTF-8'} h-8 w-8 bg-gray-700"></i>
                        {/if}
                    </div>
                    <code class="mt-3 block break-all rounded font-mono text-xs text-gray-700">{$icon.usage|escape:'html':'UTF-8'}</code>
                </article>
            {/foreach}
        </div>
    </section>
{/foreach}
