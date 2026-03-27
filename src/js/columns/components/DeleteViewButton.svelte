<script lang="ts">
    import {ListScreenData} from "../../types/requests";
    import {Writable} from "svelte/store";
    import {AcButton, AcInlineConfirmation} from "ACUi/index";
    import {deleteTableView} from "../ajax/ajax";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../utils/global";
    import {createEventDispatcher} from "svelte";

    export let listScreenData: Writable<ListScreenData>;
    export let readonlyListScreen: Writable<boolean>;
    export let isSaved: Writable<boolean>;

    const dispatch = createEventDispatcher();
    const i18n = getColumnSettingsTranslation();
    const config = getColumnSettingsConfig();

    let showConfirmation = false;

    const deleteView = async (removeId: string) => {
        deleteTableView(removeId).then(() => {
            dispatch('deleteView', {id: removeId});
        });
    }

    const handleDeleteView = () => {
        if (config.confirm_delete) {
            showConfirmation = true;
        } else {
            deleteView($listScreenData.id);
        }
    }

    const handleConfirm = () => {

        deleteView($listScreenData.id);
    }
</script>

{#if $listScreenData && !$readonlyListScreen && $isSaved }
    {#key $listScreenData.id}
        <AcInlineConfirmation
                bind:open={showConfirmation}
                on:confirm={handleConfirm}
        >
            <AcButton
                    on:click={handleDeleteView}
                    type="text"
                    isDestructive
                    label={i18n.table_views.delete_view}
            />
        </AcInlineConfirmation>
    {/key}
{/if}
