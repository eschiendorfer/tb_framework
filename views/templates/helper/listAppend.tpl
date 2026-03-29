<div
    data-ajax-append-id="{$ajax_list_unique_key}"
    {if !empty($ajax_list_items_class)}class="{$ajax_list_items_class}"{/if}
>
    {foreach from=$items_html item=itemHtml}
        {$itemHtml nofilter}
    {/foreach}
</div>
