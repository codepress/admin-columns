<script type="ts">
    import {fade} from 'svelte/transition';
    import {onMount} from "svelte";

    export let trigger: HTMLElement;
    export let appendToBody: boolean = false;

    let rootElement: HTMLElement;
    let menuElement: HTMLElement;
    let pos: any = {};

    export let position: string = 'bottom-right';

    onMount(() => {
        if (appendToBody && rootElement) {
            let triggetBox = trigger.getBoundingClientRect();
            rootElement.append(menuElement);
            document.body.append(rootElement);



            pos = {
                position: 'absolute',
                top: (triggetBox.top + triggetBox.height).toString() + 'px',
                left: triggetBox.left + 'px'
            }

            if (position === 'bottom-left') {
                pos.left = (triggetBox.left - menuElement.getBoundingClientRect().width + triggetBox.width) + 'px';
            }

        }
    });

    $:rootElementStyle = Object.entries(pos)
        .map(([key, value]) => `${key}:${value}`)
        .join(';');

</script>


{#if appendToBody}
	<div style="position: absolute; left:0; top:0;" bind:this={rootElement}>

	</div>
{/if}

<div class="acui-dropdown-menu"
		class:-append-to-body={appendToBody}
		class:-bottom-left={!appendToBody && position ==='bottom-left'}
		style={rootElementStyle}
		in:fade={{ duration: 100}} out:fade={{ duration: 100}}
		bind:this={menuElement}
>
	<div class="acui-dropdown-content" role="list" aria-modal="true" on:itemSelect>
		<slot/>
	</div>
</div>



