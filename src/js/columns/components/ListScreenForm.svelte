<script lang="ts">
	import ColumnsForm from "./ColumnsForm.svelte";
    import {MappedListScreenData} from "../../types/admin-columns";
    import {ListScreenData} from "../../types/requests";
    import {listScreenDataStore} from "../store/list-screen-data";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";
    import {saveListScreen} from "../ajax/ajax";

	export let config: any
	export let data: ListScreenData


    const saveSettings = () => {
        saveListScreen(data);
    }

</script>

<section>
	<ColumnsForm bind:data={data} bind:config={config}></ColumnsForm>
	{#each ListScreenSections.getSections( 'after_columns' ) as component}
		<HtmlSection component={component}></HtmlSection>
	{/each}

	<div>
		<AcButton on:click={saveSettings}>Save</AcButton>
	</div>

</section>