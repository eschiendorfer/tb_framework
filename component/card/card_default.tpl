{* This component has to be compatible with list_components *}

<div class="tbfw_card_default">
	{if isset($component.link)}<a href="{$component.link.url}">{/if}
		<img class="" src="{$component.image.src}" alt="">
	{if isset($component.link)}</a>{/if}
	<div class="">

		{$component.section}

		{$component.title}

		{$component.description}

		{if isset($component.footer) && $component.footer}
			{$component.footer}
		{/if}
	</div>
</div>


