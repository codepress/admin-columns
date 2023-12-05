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
	export let tableUrl: string;

	let isSaving = false;

    const saveSettings = () => {
        isSaving = true;
        saveListScreen(data).then( () => {
            isSaving = false;
		});
    }

</script>
<style>
	.acp-footer-bar {
		display:flex;
		justify-content: right;
		margin-top: -40px;
		border-radius: 0 0 10px 10px;
		background: #fff;
		padding: 20px;

		border: 1px solid #CBD5E1;
		position: sticky;
		bottom: 0;
	}
</style>
<section>
	<ColumnsForm bind:data={data} bind:config={config} {tableUrl}></ColumnsForm>
	{#each ListScreenSections.getSections( 'after_columns' ) as component}
		<HtmlSection component={component}></HtmlSection>
	{/each}

	<div class="acp-footer-bar">
		<AcButton on:click={saveSettings} type="primary" loading={isSaving}>Save</AcButton>
	</div>

</section>