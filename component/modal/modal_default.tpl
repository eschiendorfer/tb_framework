<link rel="stylesheet" href="modules/tb_framework/node_modules/basiclightbox/dist/basicLightbox.min.css">
<script src="modules/tb_framework/node_modules/basiclightbox/dist/basicLightbox.min.js"></script>

<script>
	const instance = basicLightbox.create(`
	<div style="background: white; max-width: 400px; margin: 20px auto;">
	<h1>Dynamic Content</h1>
	<p>You can set the content of the lightbox with JS.</p>
	</div>
`).show();
</script>


<a class="button_primary" data-modal-open="{$component.id}">Open Modal {$component.id}</a>
{$component.show}

<div class="tbfw_modal_default" style="display:{if isset($component.show) && $component.show}block{else}none{/if};" id="{$component.id}">

	<div {if isset($component.close_background) && $component.close_background}data-modal-close="{$component.id}"{/if}></div>

	<div id="{$component.id}_content" class="">
		<div class="">
			<button type="button" data-modal-close="{$component.id}">Close</button>
		</div>
		{$component.html}
	</div>
</div>




{* This component requires JS *}
{literal}

	<script>

		var id_modal = 0;

		document.addEventListener('click', function(e) {

			if (e.target.hasAttribute('data-modal-open')) {
				id_modal = e.target.getAttribute('data-modal-open');
				openModal(id_modal);
			}

			if (e.target.hasAttribute('data-modal-close')) {
				id_modal = e.target.getAttribute('data-modal-close');
				closeModal(id_modal);
			}

		});


		function openModal(id) {
			document.getElementById(id).style.display = '';
		}

		function closeModal(id) {
			document.getElementById(id).style.display = 'none';
		}


	</script>
{/literal}