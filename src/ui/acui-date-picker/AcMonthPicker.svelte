<script type="ts">

	import AcDropdown from "../acui-dropdown/AcDropdown.svelte";
    import AcDateModal from "./AcDateModal.svelte";
    import {onMount} from "svelte";

    export let value: string;
    export let closeOnSelect: boolean = true;

    const pattern = '[0-9]{4}-[0-9]{2}';
    let dropdown: AcDropdown;

    const handleChange = ( e ) => {
        if( closeOnSelect ){
			dropdown.toggle();
		}
	}

</script>

<AcDropdown --menu-z-index="30000" bind:this={dropdown} closeOnClick={true}>
	<input type="text" bind:value={value} slot="trigger" {pattern}/>
	<AcDateModal bind:value={value} on:change={ handleChange } />
</AcDropdown>
