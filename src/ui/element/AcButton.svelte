<script lang="ts">

    import AcIcon from "../AcIcon.svelte";

    export let type: undefined | null | 'text' | 'primary' | 'default' = 'default';
    export let disabled: boolean = false;
    export let iconLeft: string | null = null;
    export let iconLeftPack: string | null = null;
    export let loading: boolean = false;
    export let customClass: string | undefined = '';

    let dynamicClass = ` acui-button-${type}`;
</script>

<button class="acui-button {customClass}{dynamicClass} "
		class:is-loading={loading}
		{disabled}
		on:click
>
	{#if iconLeft !== '' }
		<AcIcon icon={iconLeft} pack={iconLeftPack} size="sm"></AcIcon>
	{/if}
	<slot></slot>
</button>