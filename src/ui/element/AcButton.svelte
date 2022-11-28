<script type="ts">

    import AcIcon from "../AcIcon.svelte";

    export let type: string = '';
    export let disabled: boolean = false;
    export let iconLeft: string = '';
    export let loading: boolean = false;

    let dynamicClass = `button-${type}`;

</script>
<style>
	@keyframes spin {
		from {
			transform: rotate(0deg);
		}
		to {
			transform: rotate(360deg);
		}
	}

	.acui-button {
		position: relative;
	}

	.acui-button.button-text {
		border-color: transparent;
		background: transparent;
	}

	.acui-button :global(.acui-icon) {
		transform: translateY(-2px);
	}

	.acui-button.is-loading {
		color: transparent;
		pointer-events: none;
	}

	.acui-button.is-loading:before {
		border: 2px solid #fff;
		border-radius: 9999px;
		border-right-color: transparent;
		border-top-color: transparent;
		content: "";
		display: block;
		height: 14px;
		position: absolute;
		width: 14px;
		left: calc(50% - 7px);
		top: calc(50% - 10px);
		animation-name: spin;
		animation-duration: 1000ms;
		animation-iteration-count: infinite;
		animation-timing-function: linear;
	}
</style>

<button class="button acui-button {dynamicClass}"
		class:is-loading={loading}
		{disabled}
		on:click
>
	{#if iconLeft !== '' }
		<AcIcon icon={iconLeft} size="sm"></AcIcon>
	{/if}
	<slot></slot>
</button>