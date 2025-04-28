<script lang="ts">
    import ColumnsForm from "./ColumnsForm.svelte";
    import {ListScreenData} from "../../types/requests";
    import HtmlSection from "./HtmlSection.svelte";
    import {saveListScreen} from "../ajax/ajax";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {currentListKey, initialListScreenData, listScreenDataHasChanges} from "../store";
    import {AxiosError} from "axios";
    import ListScreenSections from "../store/list-screen-sections";
    import cloneDeep from 'lodash-es/cloneDeep';

    export let config: any
    export let data: ListScreenData
    export let locked: boolean = false;
    export let isSaving = false;

    export const saveSettings = () => {
        isSaving = true;
        saveListScreen(data, $currentListKey).then((response) => {
            if (response.data.success) {
                isSaving = false;
                NotificationProgrammatic.open({message: response.data.data.message, type: 'success'})
            } else {
                NotificationProgrammatic.open({message: response.data.data.message, type: 'error'})
            }

            initialListScreenData.set(cloneDeep(data));
            listScreenDataHasChanges.set(false);

        }).catch((c: AxiosError) => {
            NotificationProgrammatic.open({message: c.message, type: 'error'})
            isSaving = false;
        });
    }

</script>
<section>
	<ColumnsForm
		bind:data={data}
		bind:config={config}
		locked={locked}
		on:saveListScreen={saveSettings}
		{isSaving}/>

	{#each ListScreenSections.getSections( 'after_columns' ) as component}
		<HtmlSection component={component}></HtmlSection>
	{/each}

</section>