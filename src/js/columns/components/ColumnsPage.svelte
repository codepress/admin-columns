<script lang="ts">
    import ListScreenForm from "./ListScreenForm.svelte";
    import ListScreenSections from "../store/list-screen-sections";
    import HtmlSection from "./HtmlSection.svelte";
    import ListScreenMenu from "./ListScreenMenu.svelte";
    import {
        currentListKey,
        currentTableUrl,
        debugMode,
        hasUsagePermissions,
        listScreenDataHasChanges,
        listScreenDataStore,
        listScreenIsReadOnly,
        listScreenIsStored,
        listScreenIsTemplate
    } from "../store";
    import {config} from "../service/list-screen-service";
    import {startListScreenWatcher} from "../service/list-screen-watcher";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AdminHeaderBar from "../../components/AdminHeaderBar.svelte";
    import ProSideBanner from "./sidebar/pro-banner/ProSideBanner.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../utils/global";
    import ReviewComponent from "./sidebar/review/ReviewComponent.svelte";
    import SupportPanel from "./sidebar/SupportPanel.svelte";
    import ProSettingsExample from "./ProSettingsExample.svelte";
    import {AcNotice, AcPanel} from "ACUi/index";
    import JSONTree from "svelte-json-tree";
    import ListKeys from "../utils/list-keys";
    import AcConfirmation from "../../plugin/ac-confirmation";
    import {checkChangesWarning} from "../utils/unsaved-changes";

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[];

    let form: ListScreenForm;
    let isSaving: boolean = false;

    const i18n = getColumnSettingsTranslation();
    const localConfig = getColumnSettingsConfig();

    startListScreenWatcher();

    const handleMenuSelect = async (e: CustomEvent<string>) => {
		const passChangesCheck = await checkChangesWarning();

        if( ! passChangesCheck) {
            return false;
		}

        if ($currentListKey === e.detail) {
            return;
        }

        currentListKey.set(e.detail);
    }
</script>

<AdminHeaderBar title="Columns">
	<div class="acu-flex acu-justify-end">
		<a href="{$currentTableUrl}" class="acui-button acui-button-default acu-mr-2">{i18n.editor.label.view} {ListKeys.getLabelForKey( $currentListKey ) ?? ''}</a>
		{#if !$listScreenIsReadOnly && $hasUsagePermissions }
			<AcButton
				type="primary"
				loading={isSaving}
				softDisabled={isSaving}
				disabled={!$listScreenDataHasChanges && $listScreenIsStored}
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
	<div class="acu-flex acu-flex-col acu-flex-grow acu-max-w-[1640px]">

		<div class="acu-px-4 2xl:acu-px-[50px] acu-pt-[10px]" data-ac-notices>

			<hr class="wp-header-end">
			{#if !$listScreenIsTemplate && $listScreenDataStore && 'inactive' === $listScreenDataStore.status}
				<AcNotice type="info" styled showIcon>
					<span class="acu-mr-4">{@html i18n.notices.inactive}</span>
					<AcButton label={i18n.pro.settings.status.activate} on:click={()=>{ $listScreenDataStore.status = ''}}/>
				</AcNotice>
			{/if}
			{#if $listScreenDataStore?.title && $listScreenIsReadOnly && !$listScreenIsTemplate}
				<AcNotice type="info" styled showIcon>{@html i18n.editor.sentence.columns_read_only}</AcNotice>
			{/if}
			{#if $listScreenDataStore?.title && !$listScreenIsStored}
				<AcNotice type="info" styled showIcon>{@html i18n.notices.not_saved_settings}</AcNotice>
			{/if}
			{#each ListScreenSections.getSections( 'notices' ) as component}
				<HtmlSection component={component}></HtmlSection>
			{/each}
		</div>
		<main class="ac-admin-page-main acu-px-4 acu-pt-2 2xl:acu-pt-[30px] 2xl:acu-px-[50px]">
			<div class="acu-flex acu-flex-col-reverse xl:acu-gap-6 xl:acu-flex-row">
				<div class="acu-flex-grow acu-max-w-[1200px]">
					<ListScreenForm
						bind:this={form}
						bind:isSaving={isSaving}
						config={$config}
						bind:data={$listScreenDataStore}
						locked={$listScreenIsReadOnly || ! $hasUsagePermissions}
					/>

					{#if !localConfig.is_pro }
						<ProSettingsExample/>
					{/if}

					{#if $debugMode}
						<AcPanel>
							<JSONTree value={$listScreenDataStore}/>
						</AcPanel>
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