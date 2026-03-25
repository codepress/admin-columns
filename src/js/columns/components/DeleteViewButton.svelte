<script lang="ts">
    import {ListScreenData} from "../../types/requests";
    import {Writable} from "svelte/store";
    import {AcButton} from "ACUi/index";
    import AcConfirmation from "../../plugin/ac-confirmation";
    import {sprintf} from "@wordpress/i18n";
    import {deleteTableView} from "../ajax/ajax";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../utils/global";
    import {createEventDispatcher} from "svelte";

    export let listScreenData: Writable<ListScreenData>;
    export let readonlyListScreen: Writable<boolean>;
    export let isSaved: Writable<boolean>;

    const dispatch = createEventDispatcher();
    const i18n = getColumnSettingsTranslation();
    const config = getColumnSettingsConfig();

    const deleteView = async (removeId: string) => {
        deleteTableView(removeId).then(() => {
            dispatch('deleteView', {id: removeId});
        });
    }

    const handleDeleteView = () => {
        const removeId = $listScreenData.id;

        if (config.confirm_delete) {
            new AcConfirmation({
                message: sprintf(i18n.table_views.delete_message, `<strong>${$listScreenData.title}</strong>`),
                confirm: () => {
                    deleteView(removeId);
                }
            }).create();
        } else {
            deleteView(removeId);
        }
    }
</script>

{#if $listScreenData && !$readonlyListScreen && $isSaved }
	{#key $listScreenData.id}
		<AcButton
			on:click={handleDeleteView}
			type="text"
			isDestructive
			label={i18n.table_views.delete_view}
		/>
	{/key}
{/if}
