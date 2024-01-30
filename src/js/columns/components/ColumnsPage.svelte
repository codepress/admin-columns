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

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[];
    export let initialListId: string | null = null;

    let config: { [key: string]: AC.Vars.Settings.ColumnSetting[] };
    let tableUrl: string;
    let loadedListId: string | null = null;

    const handleMenuSelect = (e: CustomEvent<string>) => {
        if ($currentListKey === e.detail) {
            return;
        }

        refreshListScreenData(e.detail);
    }

    const refreshListScreenData = (listKey: string, listId: string = '') => {
        getListScreenSettings(listKey, listId).then(response => {
            initialListId = '';
            config = response.data.data.column_settings
            tableUrl = response.data.data.table_url;
            $currentListKey = listKey;
            loadedListId = response.data.data.settings.list_screen.id;
            $currentListId = response.data.data.settings.list_screen.id;
            $columnTypesStore = response.data.data.column_types;
            listScreenIsReadOnly.set(response.data.data.read_only);
            listScreenDataStore.update(() => {
                return response.data.data.settings.list_screen;
            })
        }).catch((response) => {
            NotificationProgrammatic.open({message: response.message, type: 'error'})
        });
    }

    onMount(() => {
        currentListKey.subscribe(listKey => {
            if (initialListId === '') {
                refreshListScreenData(listKey);
            }
        });

        currentListId.subscribe((listId) => {
            if (listId && loadedListId !== listId) {
                refreshListScreenData($currentListKey, listId);
            }
        });
    });
</script>

<div class="ac-admin-page lg:acu-flex acu-gap-4">
	<aside class="ac-admin-page-menu lg:acu-w-[220px] xl:acu-w-[250px]">
		<ListScreenMenu
			menu={menu}
			openedGroups={openedGroups}
			on:itemSelect={handleMenuSelect}
		/>
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