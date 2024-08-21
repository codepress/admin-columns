<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import {onMount} from "svelte";
    import {getListScreenSettings} from "../ajax/ajax";
    import {currentListId, currentListKey} from "../store/current-list-screen";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import ListScreenMenu from "./ListScreenMenu.svelte";
    import {listScreenDataStore} from "../store/list-screen-data";
    import {columnTypeSorter, columnTypesStore} from "../store/column-types";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {listScreenIsReadOnly} from "../store/read_only";

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[];
    export let initialListId: string | null = null;

    let loadingSettings: boolean = false;
    let abort: AbortController | null = null;
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
        if (abort) {
            abort.abort();
        }

        if( loadingSettings) {
            return;
		}

        if (listKey === $currentListKey && loadedListId === listId && typeof $listScreenDataStore !== 'undefined') {
            return;
        }

        abort = new AbortController();
        loadingSettings = true;

        getListScreenSettings(listKey, listId, abort).then(response => {
            initialListId = '';
            config = response.data.data.column_settings
            tableUrl = response.data.data.table_url;
            $currentListKey = listKey;
            loadedListId = response.data.data.settings.list_screen.id;
            $currentListId = response.data.data.settings.list_screen.id;
            $columnTypesStore = response.data.data.column_types.sort(columnTypeSorter);
            listScreenIsReadOnly.set(response.data.data.read_only);
            listScreenDataStore.update(() => {
                return response.data.data.settings.list_screen;
            });
            loadingSettings = false;
        }).catch((response) => {
            loadingSettings = false;
            if( response.message === 'canceled' ){
                return;
			}
            NotificationProgrammatic.open({message: response.message, type: 'error'})
            loadingSettings = false;
        });
    }

    onMount(() => {
        currentListKey.subscribe(listKey => {
            abort?.abort();
            if (initialListId === '') {
                refreshListScreenData(listKey);
            }
        });

        currentListId.subscribe((listId) => {
            abort?.abort();
            if (listId && loadedListId !== listId) {
                refreshListScreenData($currentListKey, listId);
            }
        });
    });
</script>

<div class="ac-admin-page acu-flex acu-flex-col acu-min-h-[calc(100vh_-_70px)] acu-w-full acu-transform
			xl:acu-flex-row ">
	<aside class="ac-admin-page-menu acu-pl-4 acu-pr-[30px] acu-py-8
				  xl:acu-w-[250px] xl:acu-bg-[#EAF0F6] xl:acu-pt-[60px]">
		<ListScreenMenu
			menu={menu}
			openedGroups={openedGroups}
			on:itemSelect={handleMenuSelect}
		/>
	</aside>
	<main class="ac-admin-page-main acu-px-4 acu-pt-2 xl:acu-pt-[60px] xl:acu-px-[50px]">
		<div class="xl:acu-flex xl:acu-gap-6 xl:acu-flex-row-reverse">
			<div>
				{#each ListScreenSections.getSections( 'sidebar' ) as component}
					<HtmlSection component={component}></HtmlSection>
				{/each}
			</div>
			<div class="acu-flex-grow">
				{#if $listScreenDataStore !== null}
					<ListScreenForm bind:config={config} bind:data={$listScreenDataStore} tableUrl={tableUrl}></ListScreenForm>
				{/if}
			</div>
		</div>
	</main>
</div>