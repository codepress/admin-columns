<script lang="ts">
	export let title: string|undefined|null;
	export let border: boolean = false;

    let classes = ['acu-px-[20px] acu-pt-[20px]'];

    if( border ){
        classes.push('acu-pb-[20px] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border')
    } else {

	}

</script>

<div class={classes.join(' ')}>
	{#if title }
		<h3 class="acu-my-[0]">{title}</h3>
	{/if}
	<slot></slot>
</div>