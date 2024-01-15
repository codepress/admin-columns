<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import {onMount} from "svelte";
    import {getListScreenSettings} from "../ajax/ajax";
    import {currentListId, currentListKey} from "../store/current-list-screen";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import ListScreenMenu from "./ListScreenMenu.svelte";
    import {listScreenDataStore} from "../store/list-screen-data";
    import {columnTypesStore} from "../store/column-types";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {listScreenIsReadOnly} from "../store/read_only";
    import ColumnSetting = AC.Vars.Settings.ColumnSetting;

    export let menu: AC.Vars.Admin.Columns.MenuItems;

    let config: { [key: string]: ColumnSetting[] };
    let tableUrl: string;
    let loadedListId = null;

    const handleMenuSelect = (e: CustomEvent<string>) => {
        if ($currentListKey === e.detail) {
            return;
        }

        refreshListScreenData(e.detail);
    }

    const refreshListScreenData = (listKey: string, listId: null|string = null) => {
        getListScreenSettings(listKey, listId).then(response => {
            config = response.data.data.column_settings
            tableUrl = response.data.data.table_url;
            $currentListKey = listKey;
            loadedListId = response.data.data.settings.list_screen.id;
            $currentListId = response.data.data.settings.list_screen.id;
            $columnTypesStore = response.data.data.column_types;

            listScreenDataStore.update(() => {
                return response.data.data.list_screen_data.list_screen;
            })
        }).catch((response) => {
            NotificationProgrammatic.open({message: response.message, type: 'error'})
        });
    }


    const handleListIdChange = (listId: string) => {
        getListScreenSettings($currentListKey, listId).then(response => {
            if (response.data.success) {
                config = response.data.data.settings;
                tableUrl = response.data.data.table_url;
                listScreenIsReadOnly.set(response.data.data.read_only);
                listScreenDataStore.update(() => {
                    return response.data.data.list_screen_data.list_screen;
                });
            } else {
                NotificationProgrammatic.open({message: response.data.data.message, type: 'error'})
            }
        }).catch(d => {
            alert(d.message);
            NotificationProgrammatic.open({message: d.message, type: 'error'})
        })
    }

    onMount(() => {
        currentListKey.subscribe(listKey => {
            refreshListScreenData(listKey);
        });

        currentListId.subscribe((listId) => {
            if (listId && loadedListId !== listId) {
                refreshListScreenData($currentListKey, listId);
            }
        });
    });
</script>

<div class="ac-admin-page">
	<aside class="ac-admin-page-menu">
		<ListScreenMenu menu={menu} on:itemSelect={handleMenuSelect}>

		</ListScreenMenu>
	</aside>
	<main class="ac-admin-page-main">
		{#each ListScreenSections.getSections( 'before_columns' ) as component}
			<HtmlSection component={component}></HtmlSection>
		{/each}

		{#if $listScreenDataStore !== null}
			<ListScreenForm bind:config={config} bind:data={$listScreenDataStore} tableUrl={tableUrl}></ListScreenForm>
		{/if}
	</main>
</div>