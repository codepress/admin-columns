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
    import AcButton from "ACUi/element/AcButton.svelte";
    import AdminHeaderBar from "../../components/AdminHeaderBar.svelte";
    import ProSideBanner from "./sidebar/pro-banner/ProSideBanner.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../utils/global";
    import ReviewComponent from "./sidebar/review/ReviewComponent.svelte";
    import SupportPanel from "./sidebar/SupportPanel.svelte";

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[];

    let debounceTimeout: any;
    let config: { [key: string]: AC.Vars.Settings.ColumnSetting[] };
    let tableUrl: string;
    let loadedListId: string | null = null;

    let form: ListScreenForm;

    let abortController: AbortController;
    let queuedListId: string | null = null;
    let queuedListKey: string | null = null;

    const i18n = getColumnSettingsTranslation();
    const localConfig = getColumnSettingsConfig();

    const handleMenuSelect = (e: CustomEvent<string>) => {
        if ($currentListKey === e.detail) {
            return;
        }

        $currentListKey = e.detail;
    }

    const refreshListScreenData = (listKey: string, listId: string = '') => {
        queuedListKey = null;
        queuedListId = null;
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        getListScreenSettings(listKey, listId, abortController).then(response => {
            loadedListId = response.data.data.settings.list_screen.id;
            config = response.data.data.column_settings
            tableUrl = response.data.data.table_url;
            $columnTypesStore = response.data.data.column_types.sort(columnTypeSorter);
            listScreenIsReadOnly.set(response.data.data.read_only);
            $listScreenDataStore = response.data.data.settings.list_screen;
            $currentListId = loadedListId;
        }).catch((response) => {
            NotificationProgrammatic.open({message: response.message, type: 'error'})
        });
    }


    const processQueuedChanges = () => {
        clearTimeout(debounceTimeout);

        if (!queuedListKey) {
            queuedListKey = $currentListKey;
        }

        refreshListScreenData(queuedListKey, queuedListId ?? '')
    }


    const debounceFetch = (delay: number = 400) => {
        clearTimeout(debounceTimeout);

        debounceTimeout = setTimeout(processQueuedChanges, delay);
    }

    onMount(() => {
        currentListKey.subscribe(listKey => {
            queuedListKey = listKey;
            debounceFetch()
        });

        currentListId.subscribe((listId) => {
            if (listId && loadedListId !== listId) {
                queuedListId = listId;
                debounceFetch();
            }
        });

    });
</script>

<AdminHeaderBar title="Columns">
	<div class="acu-flex acu-justify-end">
		<a href="{tableUrl}" class="acui-button acui-button-default acu-mr-2">{i18n.editor.label.view}</a>
		<AcButton type="primary" on:click={() => form.saveSettings()}>{i18n.editor.label.save}</AcButton>
	</div>
</AdminHeaderBar>

<div class="ac-admin-page acu-flex acu-flex-col acu-min-h-[calc(100vh_-_70px)] acu-w-full acu-transform
			2xl:acu-flex-row ">
	<aside class="ac-admin-page-menu acu-relative acu-pl-4 acu-pr-[30px] acu-py-8
				  2xl:acu-w-[250px] 2xl:acu-pt-[30px]">
		<ListScreenMenu
			menu={menu}
			openedGroups={openedGroups}
			on:itemSelect={handleMenuSelect}
		/>
	</aside>
	<div class="acu-flex acu-flex-col acu-flex-grow">
		<div class="acu-px-4 2xl:acu-px-[50px] ">
			<hr class="wp-header-end">
		</div>
		<main class="ac-admin-page-main acu-px-4 acu-pt-2 2xl:acu-pt-[30px] 2xl:acu-px-[50px]">
			<div class="xl:acu-flex xl:acu-gap-6 xl:acu-flex-row">
				<div class="acu-flex-grow acu-max-w-[1200px]">
					{#if $listScreenDataStore !== null}
						<ListScreenForm bind:this={form} bind:config={config}
							bind:data={$listScreenDataStore}
							tableUrl={tableUrl}></ListScreenForm>
					{/if}
				</div>
				<aside class="acu-hidden xl:acu-block acu-w-[320px]">
					{#each ListScreenSections.getSections( 'sidebar' ) as component}
						<HtmlSection component={component}></HtmlSection>
					{/each}

					{#if localConfig.pro_banner}
						<ProSideBanner proBannerConfig={localConfig.pro_banner}/>
					{/if}

					<ReviewComponent/>
					<SupportPanel/>
				</aside>
			</div>
		</main>
	</div>

</div>