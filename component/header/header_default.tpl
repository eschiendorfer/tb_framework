<div class="tbfw_header_default">
	{if isset($component.subtitle) && $component.subtitle}
		<h2>{$component.subtitle}</h2>
	{/if}

	{if isset($component.title) && $component.title}
		<p>{$component.title}</p>
	{/if}

	{if isset($component.description) && $component.description}
		<p>{$component.description}</p>
	{/if}
</div>