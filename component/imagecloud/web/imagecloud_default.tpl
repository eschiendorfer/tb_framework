<div class="tbfw_imagecloud_default">
	<div>
		{foreach from=$component.data item=element}
			{if isset($element.link.url) && $element.link.url}<a href="{$element.link.url}" {if isset($element.link.target)}target="{$element.link.target}"{/if}>{/if}
				<div>
					<img src="{$element.src}" alt="{$element.title}">
					<div>{$element.title}</div>
				</div>

			{if isset($element.link) && $element.link}</a>{/if}
		{/foreach}
	</div>

	{if isset($component.button)}
		<div>
			<a href="{$component.button.link}">{$component.button.title}</a>
		</div>
	{/if}
</div>