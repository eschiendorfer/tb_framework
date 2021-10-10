<h1>Cards</h1>

{foreach from=$components item=component}
    <section>
        <b>{$component.name}:</b><br>
        {$component.output}
    </section>
{/foreach}

