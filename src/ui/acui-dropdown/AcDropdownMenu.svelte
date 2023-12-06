<script lang="ts">
    import {fade} from 'svelte/transition';
    import {onMount} from "svelte";

    export let trigger: HTMLElement;
    export let appendToBody: boolean = false;
    export let maxHeight: string|null = null;
    export let position: string = 'bottom-right';

    let rootElement: HTMLElement;
    let menuElement: HTMLElement;
    let pos: any = {};

    const positionBodyElement = () => {
        let triggetBox = trigger.getBoundingClientRect();
        rootElement.append(menuElement);
        document.body.append(rootElement);

        pos = {
            position: 'absolute',
            top: (triggetBox.top + triggetBox.height).toString() + 'px',
            left: triggetBox.left + 'px'
        }

        console.log(pos);

        if (position === 'bottom-left') {
            pos.left = (triggetBox.left - menuElement.getBoundingClientRect().width + triggetBox.width) + 'px';
        }
    }

    onMount(() => {
        if (appendToBody && rootElement) {
            positionBodyElement();
            window.addEventListener('resize', () => {
                positionBodyElement();
            });
        }


    });

    $:rootElementStyle = Object.entries(pos)
        .map(([key, value]) => `${key}:${value}`)
        .join(';');

</script>

{#if appendToBody}
	<div style="position: absolute; left:0; top:0;" style:max-height={maxHeight} bind:this={rootElement}>

	</div>
{/if}

<div class="acui-dropdown-menu"
		class:-append-to-body={appendToBody}
		class:-bottom-left={!appendToBody && position ==='bottom-left'}
		style={rootElementStyle}
		style:max-height={maxHeight}
		in:fade={{ duration: 100}} out:fade={{ duration: 100}}
		bind:this={menuElement}
>
	<div class="acui-dropdown-content" role="list" on:itemSelect>
		<slot/>
	</div>
</div>



