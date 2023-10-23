<script lang="ts">

    import AcIcon from "../AcIcon.svelte";

    export let type: null | 'text' | 'primary' = null;
    export let disabled: boolean = false;
    export let iconLeft: string = '';
    export let loading: boolean = false;

    let dynamicClass = `button-${type}`;

</script>

<button class="acui-button {dynamicClass}"
		class:is-loading={loading}
		{disabled}
		on:click
>
	{#if iconLeft !== '' }
		<AcIcon icon={iconLeft} size="sm"></AcIcon>
	{/if}
	<slot></slot>
</button>