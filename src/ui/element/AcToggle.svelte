<script type="ts">
    import {generateGuid} from "../helpers/input";

    export let checked: boolean;

    let name: string = generateGuid();
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

