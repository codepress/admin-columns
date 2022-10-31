<script type="ts">
    import {onMount} from "svelte";

    export let checked: boolean;
    export let name: string = '';

    const guid = () => {
        let s4 = () => {
            return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
        }

        return 'ac' + s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4();
    }

    onMount(() => {
        if (name === '') {
            name = guid();
        }
    });
</script>

<div class="ac-toggle-v2" data-dependent="" data-setting="hide_inline_edit">
	<span class="ac-toggle-v2__toggle">
		<input class="ac-toggle-v2__toggle__input" type="checkbox" value="off" bind:checked={checked} id={name}>
		<span class="ac-toggle-v2__toggle__track"></span>
		<span class="ac-toggle-v2__toggle__thumb"></span>
	</span>

	<label class="ac-toggle-v2__label" for={name}>
		<slot></slot>
	</label>
</div>

