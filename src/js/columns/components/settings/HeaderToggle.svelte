<script lang="ts">
    export let value: string = 'off';
    export let title: string;
    export let disabled: boolean = false

    const values = ['off', 'on'];

    const toggle = () => {
        if (disabled) {
            return;
        }
        value = value === 'on' ? 'off' : 'on';
    }

    $: isOn = value === 'on';

</script>
<button class="ac-header-toggle [&.-active]:acu-bg-[#E2E8F0] [&.-active]:acu-text-[#475569] acu-bg-[transparent]"
	class:-active={isOn}
	on:click={toggle}
	{disabled}
	title={title}>
	<slot></slot>
</button>