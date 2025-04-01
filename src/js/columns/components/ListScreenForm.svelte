<script lang="ts">
    import ColumnsForm from "./ColumnsForm.svelte";
    import {ListScreenData} from "../../types/requests";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import {saveListScreen} from "../ajax/ajax";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {currentListKey} from "../store/current-list-screen";
    import {AxiosError} from "axios";
    import {getColumnSettingsTranslation} from "../utils/global";

    export let config: any
    export let data: ListScreenData
    export let locked: boolean = false;

    const i18n = getColumnSettingsTranslation();

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

        }).catch((c: AxiosError) => {
            NotificationProgrammatic.open({message: c.message, type: 'error'})
            isSaving = false;
        });
    }

</script>
<section>
	<ColumnsForm bind:data={data} bind:config={config} locked={locked}></ColumnsForm>

	{#each ListScreenSections.getSections( 'after_columns' ) as component}
		<HtmlSection component={component}></HtmlSection>
	{/each}

</section>