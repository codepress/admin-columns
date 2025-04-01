<script lang="ts">

    import AcIcon from "../AcIcon.svelte";

    export let type: undefined | null | 'text' | 'primary' | 'default' | 'pink' = 'default';
    export let disabled: boolean = false;
    export let softDisabled: boolean = false;
    export let isDestructive: boolean | null = false;
    export let iconLeft: string | null = null;
    export let iconLeftPack: string | null = null;
    export let iconRight: string | null = null;
    export let iconRightPack: string | null = null;
    export let loading: boolean = false;
    export let customClass: string | undefined = '';
    export let label: string | undefined = undefined;
    export let size: 'small' | 'medium' | 'large' = 'medium';
    export let href: string | undefined = undefined;
    export let target: string = '_self';

    let classes = [
        'acui-button',
        customClass,
        `acui-button-${type}`
    ]

    if (size === 'small') classes.push('-small');
    if (isDestructive) classes.push('-destructive');
    if (disabled) classes.push('-disabled');

</script>


{#if href }
	<a class="{classes.join(' ')}"
		href={href}
		target={target}
		class:is-loading={loading}
		class:-disabled={disabled}
		on:click
	>
		{#if iconLeft }
			<AcIcon icon={iconLeft} pack={iconLeftPack} size="sm"></AcIcon>
		{/if}
		{#if label }
			{label}
		{:else}
			<slot></slot>
		{/if}

		{#if iconRight }
			<AcIcon icon={iconRight} pack={iconRightPack} size="sm"></AcIcon>
		{/if}
	</a>
{:else}
	<button class="{classes.join(' ')}"
		class:is-loading={loading}
		disabled={disabled || softDisabled}
		on:click
	>
		{#if iconLeft }
			<AcIcon icon={iconLeft} pack={iconLeftPack} size="sm"></AcIcon>
		{/if}
		{#if label }
			{label}
		{:else}
			<slot></slot>
		{/if}

		{#if iconRight }
			<AcIcon icon={iconRight} pack={iconRightPack} size="sm"></AcIcon>
		{/if}
	</button>
{/if}