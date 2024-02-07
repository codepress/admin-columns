<script lang="ts">
    import ColumnsForm from "./ColumnsForm.svelte";
    import {ListScreenData} from "../../types/requests";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";
    import {saveListScreen} from "../ajax/ajax";
    import {listScreenIsReadOnly} from "../store/read_only";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {currentListKey} from "../store/current-list-screen";
    import {AxiosError} from "axios";

    export let config: any
    export let data: ListScreenData
    export let tableUrl: string;

    let isSaving = false;

    const saveSettings = () => {
        isSaving = true;
        saveListScreen(data, $currentListKey).then((response) => {
            if (response.data.success) {
                isSaving = false;
                NotificationProgrammatic.open({message: response.data.data.message, type: 'success'})
            } else {
                NotificationProgrammatic.open({message: response.data.data.message, type: 'error'})
            }

        }).catch((c: AxiosError) => {
            NotificationProgrammatic.open({message: c.message, type: 'error'})
            isSaving = false;
        });
    }

</script>
<section>
	<ColumnsForm bind:data={data} bind:config={config} {tableUrl}></ColumnsForm>
	{#each ListScreenSections.getSections( 'after_columns' ) as component}
		<HtmlSection component={component}></HtmlSection>
	{/each}

	{#if !$listScreenIsReadOnly}
		<div class="acp-footer-bar acu-flex acu-justify-end acu-mt-[-35px] acu-rounded-b-lg acu-bg-[white] acu-sticky acu-bottom-[0] acu-p-4 acu-border acu-border-solid acu-border-ui-border rounded-t-none">
			<AcButton on:click={saveSettings} type="primary" loading={isSaving}>Save</AcButton>
		</div>
	{/if}

</section>