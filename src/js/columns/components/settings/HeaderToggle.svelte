<script lang="ts">
    export let value: string = 'off';
    export let title: string;

	const values = ['off', 'on'];

    const toggle = () => {
        value = value === 'on' ? 'off' : 'on';
	}

    $: isOn = value === 'on';

</script>
<button class="ac-header-toggle" class:-active={isOn} on:click={toggle} title={title}>
	<slot></slot>
</button>