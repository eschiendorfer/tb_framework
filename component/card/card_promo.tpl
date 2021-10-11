<div class="tbfw_card_promo">

	<img src="{$component.image.src}">

	<div>

		{$component.subtitle}
		{$component.title}
		{$component.description}

		{if isset($component.button)}
			<a href="{$component.button.link}" target="{$component.button.target}">{$component.button.title}</a>
		{/if}

	</div>

</div>
