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

<div class="acui-checkbox">
	<label>
		<input type="checkbox">
		<span class="acui-checkbox__label"><slot></slot></span>
	</label>
</div>

