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
    import {currentTableUrl} from "../store/table_url";
    import {hasUsagePermissions} from "../store/permissions";
    import ProSettingsExample from "./ProSettingsExample.svelte";
    import {AcNotice} from "ACUi/index";
    import {sprintf} from "@wordpress/i18n";
    import {isLoadingColumnSettings} from "../store/loading";

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[];

    let debounceTimeout: any;
    let config: { [key: string]: AC.Vars.Settings.ColumnSetting[] };
    let loadedListId: string | null = null;

    let form: ListScreenForm;
    let isSaving: boolean = false;

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
        $isLoadingColumnSettings = true

        getListScreenSettings(listKey, listId, abortController).then(response => {
            loadedListId = response.data.data.settings.list_screen.id;
            config = response.data.data.column_settings
            $currentTableUrl = response.data.data.table_url;
            $columnTypesStore = response.data.data.column_types.sort(columnTypeSorter);
            listScreenIsReadOnly.set(response.data.data.read_only);
            $listScreenDataStore = response.data.data.settings.list_screen;
            $currentListId = loadedListId;
        }).catch((response) => {
            NotificationProgrammatic.open({message: response.message, type: 'error'})
        }).finally( () => {
            $isLoadingColumnSettings = false
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
		<a href="{$currentTableUrl}" class="acui-button acui-button-default acu-mr-2">{i18n.editor.label.view}</a>
		{#if !$listScreenIsReadOnly && $hasUsagePermissions }
			<AcButton
				type="primary"
				loading={isSaving}
				softDisabled={isSaving}
				on:click={() => form.saveSettings()}
				label={i18n.editor.label.save}
			/>
		{/if}
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
		<div class="acu-px-4 2xl:acu-px-[50px]" data-ac-notices>

			<hr class="wp-header-end">
			{#if $listScreenDataStore !== null && $listScreenIsReadOnly}
				<AcNotice type="info" styled showIcon>{@html sprintf( i18n.editor.sentence.columns_read_only,
					`<strong>${$listScreenDataStore?.title}</strong>` )}</AcNotice>
			{/if}

		</div>
		<main class="ac-admin-page-main acu-px-4 acu-pt-2 2xl:acu-pt-[30px] 2xl:acu-px-[50px]">
			<div class="acu-flex acu-flex-col-reverse xl:acu-gap-6 xl:acu-flex-row">
				<div class="acu-flex-grow acu-max-w-[1200px]">
					{#if $listScreenDataStore !== null}
						<ListScreenForm
							bind:this={form}
							bind:isSaving={isSaving}
							bind:config={config}
							bind:data={$listScreenDataStore}
							locked={$listScreenIsReadOnly || ! $hasUsagePermissions}
						/>
					{/if}
					{#if !localConfig.is_pro }
						<ProSettingsExample/>
					{/if}
				</div>
				<aside class="xl:acu-w-[320px]">
					{#each ListScreenSections.getSections( 'sidebar' ) as component}
						<HtmlSection component={component}></HtmlSection>
					{/each}
					<div class="acu-hidden xl:acu-block">
						{#if !localConfig.is_pro }
							{#if localConfig.pro_banner }
								<ProSideBanner proBannerConfig={localConfig.pro_banner}/>
							{/if}
							<ReviewComponent/>
							<SupportPanel/>
						{/if}
					</div>

				</aside>
			</div>
		</main>
	</div>


</div>