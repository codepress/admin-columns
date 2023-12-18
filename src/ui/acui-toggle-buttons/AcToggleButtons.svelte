<script lang="ts">

    export let value: string
    export let options: Array<{ value: string, label: string }>
    export let onChange: (v: string)=>void

    const handleValue = (v: any) => {
        value = v;
        onChange(v);
    }

</script>

<div class="acui-toggle-buttons">
	{#each options as option}
		<button class="button" class:button-primary={value===option.value} on:click|preventDefault={() => handleValue(option.value) } value={option.value}>
			{#if value === option.value}
				<span class="dashicons dashicons-yes"></span>
			{/if}
			{option.label}
		</button>
	{/each}
</div>