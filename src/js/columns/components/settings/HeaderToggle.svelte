<script lang="ts">
    import {onMount} from "svelte";

    export let value: string = '';
    export let title: string;
    export let disabled: boolean = false
	export let defaultValue: string;

    const toggle = () => {
        if (disabled) {
            return;
        }
        value = value === 'on' ? 'off' : 'on';
    }

    onMount( () => {
        if( value === '' ){
         	value = defaultValue
		}
	})

    $: isOn = value === 'on';

</script>
<button class="ac-header-toggle [&.-active]:acu-bg-[#E2E8F0] [&.-active]:acu-text-[#475569] acu-bg-[transparent]"
	class:-active={isOn}
	on:click={toggle}
	{disabled}
	title={title}>
	<slot></slot>
</button>