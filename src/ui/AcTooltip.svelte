<script type="ts">
    import {tick} from "svelte";

    export let label: string;
    export let active: boolean = false;
    export let appendToBody: boolean = false;
    export let delay: number = 1000;

    //TODO buildin delay
    // TODO build in outdelay
    let hover: boolean = false;
    let contentEl: HTMLElement;
    let triggerEl: HTMLElement;

    const wait = (milliseconds) => {
        return new Promise(resolve => setTimeout(resolve, milliseconds));
    }

    const handleMouseEnter = async () => {
        hover = true;
        active = true;

        await tick();

        if (appendToBody && contentEl) {
            document.body.append(contentEl);
            attachBodyPosition();
        }
    }

    const handleMouseOut = () => {
        hover = true;
        active = false;
    }

    const attachBodyPosition = () => {
        const bodyOffset = document.body.getBoundingClientRect();
        const viewportOffset = triggerEl.getBoundingClientRect();

        contentEl.style.left = ((viewportOffset.left - bodyOffset.left) + triggerEl.offsetWidth / 2) + 'px';
        contentEl.style.top = ((viewportOffset.top) + triggerEl.offsetHeight) + 'px';
    }

</script>
<style>
	.acui-tooltip {
		display: inline-flex;
		position: relative;
		background: #000;
	}

	.acui-tooltip-content {
		position: absolute;
		left: 50%;
		top: 100%;
		transform: translateX(-50%);
		z-index: 100000;
	}
</style>

<div class="acui-tooltip">
	<div class="acui-tooltip-trigger" on:mouseenter={handleMouseEnter} on:mouseleave={handleMouseOut} bind:this={triggerEl}>
		<slot></slot>
	</div>
	{#if active }
		<div class="acui-tooltip-content" style:display={ active === true ? 'block' : 'none' } bind:this={contentEl}>
			{label}
		</div>
	{/if}
</div>