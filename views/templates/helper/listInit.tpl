<div data-ajax-replacement-id="{$ajax_list_unique_key}">

    {include file="./listAppend.tpl"}

    <div class="text-center" data-ajax-list-show-more>
        {if $itemsTotal > $offset_next}
            <div
                class="text-xs mt-5 mb-2"
                data-ajax-list-counter
                data-items-label="{$itemsLabel}"
            >{$offset_next} / {$itemsTotal} {$itemsLabel}</div>
            <a class="{$css_selector.button_secondary.default}"
               data-ajax="true"
               data-ajax-loadingSpinner="true"
               data-ajax-callback="updateAjaxListShowMore"
               data-ajax-list_action="append"
               data-items-total="{$itemsTotal}"
               data-ajax-offset="{$offset_next}"
               data-step-limit="{$step_limit_next}"
               data-href="{$ajax_list_url}"
               href="{$public_list_url}"
            >
                {l s='Show more' mod='tb_framework'}
            </a>
        {/if}
    </div>

</div>
