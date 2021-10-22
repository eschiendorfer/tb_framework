{* This component has to be compatible with list_components *}

<div class="tbfw_list_compact">

	{if isset($component.title) && $component.title}
		<h5>{$component.title}</h5>
	{/if}

	<div>
		<ul>

			{foreach from=$component.data item=row}

				<li>

					<div>

						<div>
							{if $row.link.url}<a href="{$row.link.url}">{/if}
								<img src="{$row.img}" alt="">
							{if $row.link.url}</a>{/if}
						</div>
						<div>
							{if $row.link.url}<a href="{$row.link.url}">{/if}
								<p>
									{$row.title}
								</p>
								<p>
									{$row.subtitle}
								</p>
							{if $row.link.url}</a>{/if}
						</div>

						{foreach from=$row.element_columns item=element}
							<div>
								<span class="{$element.class}">{$element.content}</span>
							</div>
						{/foreach}

					</div>
				</li>
			{/foreach}

		</ul>
	</div>
	{if $component.button}
		<div>
			<a href="{$component.button.link}">{$component.button.title}</a>
		</div>
	{/if}
</div>