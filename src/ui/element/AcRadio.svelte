<script lang="ts">
    import {createEventDispatcher, onMount} from "svelte";

    export let value: string;
    export let group: string | number = '';
    export let disabled: boolean = false;
    export let inline: boolean = false;

    let dispatch = createEventDispatcher();


    onMount(() => {

    });
</script>

<label class="acui-radio" class:disabled={disabled} class:-inline={inline}>
	<input type="radio" bind:group={group} {value} {disabled}>
	<span class="acui-radio__check">

	</span>
	<span class="acui-radio__label">
			<slot></slot>
	</span>
</label>


