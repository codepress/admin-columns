<script lang="ts">
    import {fade} from 'svelte/transition';
    import {onMount} from "svelte";

    export let trigger: HTMLElement;
    export let appendToBody: boolean = false;
    export let maxHeight: string | null = null;
    export let position: string | null = 'bottom-right';
    export let zIndex: number | null = null;
    export let style: string | undefined = '';

    let rootElement: HTMLElement;
    let menuElement: HTMLElement;
    let pos: any = {};

    const positionBodyElement = () => {
        rootElement.append(menuElement);
        document.body.append(rootElement);
        let triggerBox = trigger.getBoundingClientRect();

        pos = {
            position: 'absolute',
            top: (Math.round(triggerBox.top + window.scrollY + triggerBox.height)).toString() + 'px',
            left:Math.round(triggerBox.left) + 'px'
        }

        if (position === 'bottom-left') {
            pos.left = Math.round((triggerBox.left - menuElement.getBoundingClientRect().width + triggerBox.width)) +
				'px';
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
        .join(';') + style;

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
	style:z-index={zIndex}
	in:fade={{ duration: 100}} out:fade={{ duration: 100}}
	bind:this={menuElement}
>
	<div class="acui-dropdown-content" role="list">
		<slot/>
	</div>
</div>



