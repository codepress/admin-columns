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
        listScreenIsTemplate,
        proBannerStore
    } from "../store";
    import {config} from "../service/list-screen-service";
    import {startListScreenWatcher} from "../service/list-screen-watcher";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AdminHeaderBar from "../../components/AdminHeaderBar.svelte";
    import AdminPageWithSidebar from "../../components/AdminPageWithSidebar.svelte";
    import ProSideBanner from "./sidebar/pro-banner/ProSideBanner.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../utils/global";
    import ReviewComponent from "./sidebar/review/ReviewComponent.svelte";
    import SupportPanel from "./sidebar/SupportPanel.svelte";
    import ProSettingsExample from "./ProSettingsExample.svelte";
    import {AcNotice, AcPanel} from "ACUi/index";
    import JSONTree from "svelte-json-tree";
    import {checkChangesWarning} from "../utils/unsaved-changes";

    export let menu: AC.Vars.Admin.Columns.MenuItems;
    export let openedGroups: string[];

    let form: ListScreenForm;
    let isSaving: boolean = false;
    let menuCollapsed: boolean = false;

    const i18n = getColumnSettingsTranslation();
    const localConfig = getColumnSettingsConfig();

    proBannerStore.set(localConfig.pro_banner_context ?? null);

    startListScreenWatcher();

    $: screenLocked = (localConfig.screen_notices ?? []).some(n => n.list_key === $currentListKey && n.locked);

    const handleMenuSelect = async (e: CustomEvent<string>) => {
        if ($currentListKey === e.detail) {
            return;
        }

        const passChangesCheck = await checkChangesWarning();

        if (!passChangesCheck) {
            return false;
        }

        currentListKey.set(e.detail);
    }
</script>

<AdminHeaderBar>
	<div class="acu-flex acu-gap-4 acu-items-center acu-max-w-[1490px] xl:acu-mr-[360px] 2xl:acu-mr-[390px]">
		<h1 class="ac-header-bar__title acu-text-[22px]">Columns</h1>
		<div class="acu-flex acu-flex-grow acu-justify-end acu-gap-2">
			{#each ListScreenSections.getSections( 'header_bar' ) as component}
				<HtmlSection component={component}></HtmlSection>
			{/each}
			<a href="{$currentTableUrl}" class="acui-button acui-button-default">{i18n.editor.label.view}</a>
			{#if !$listScreenIsReadOnly && $hasUsagePermissions && !screenLocked}
				<AcButton
					type="primary"
					loading={isSaving}
					softDisabled={isSaving}
					customClass="ac-button--save-settings"
					disabled={!$listScreenDataHasChanges && $listScreenIsStored}
					on:click={() => form.saveSettings()}
					label={i18n.editor.label.save}
				/>
			{/if}
		</div>
	</div>
</AdminHeaderBar>

<AdminPageWithSidebar collapsible bind:collapsed={menuCollapsed} contentClass="acu-max-w-[1640px]">
	<ListScreenMenu
		slot="sidebar"
		menu={menu}
		openedGroups={openedGroups}
		on:itemSelect={handleMenuSelect}
	/>

	<div class="acu-px-4 2xl:acu-px-[50px] acp-columns-notices" data-ac-notices>

			<hr class="wp-header-end">
			{#each (localConfig.screen_notices ?? []).filter(n => n.list_key === $currentListKey) as notice}
				<AcNotice type="info" styled showIcon>
					{@html notice.message}
					{#if notice.cta_url && notice.cta_label}
						<a href={notice.cta_url} target="_blank"><strong>{notice.cta_label}</strong></a>
					{/if}
				</AcNotice>
			{/each}
			{#if !$listScreenIsTemplate && $listScreenDataStore && 'inactive' === $listScreenDataStore.status}
				<AcNotice type="info" styled showIcon>
					<span class="acu-mr-4">{@html i18n.notices.inactive}</span>
					<AcButton label={i18n.pro.settings.status.activate} on:click={()=>{ $listScreenDataStore.status = ''}}/>
				</AcNotice>
			{/if}
			{#if $listScreenDataStore?.title && $listScreenIsReadOnly && !$listScreenIsTemplate}
				<AcNotice type="info" styled showIcon>{@html i18n.editor.sentence.columns_read_only}</AcNotice>
			{/if}
			{#if $listScreenDataStore?.title && !$listScreenIsStored && !screenLocked}
				<AcNotice type="info" styled showIcon>{@html i18n.notices.not_saved_settings}</AcNotice>
			{/if}
			{#each ListScreenSections.getSections( 'notices' ) as component}
				<HtmlSection component={component}></HtmlSection>
			{/each}
		</div>
		<main class="ac-admin-page-main acu-px-4 acu-pt-2 2xl:acu-pt-[30px] 2xl:acu-px-[50px]">
			<div class="acu-flex acu-flex-col-reverse xl:acu-gap-6 xl:acu-flex-row">
				<div class="acu-flex-grow acu-max-w-[1200px]">
					{#if $listScreenDataStore}
					<ListScreenForm
						bind:this={form}
						bind:isSaving={isSaving}
						config={$config}
						bind:data={$listScreenDataStore}
						locked={$listScreenIsReadOnly || !$hasUsagePermissions || screenLocked}
					/>
					{/if}

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
								<ProSideBanner proBannerConfig={$proBannerStore ? {...localConfig.pro_banner, ...$proBannerStore} : localConfig.pro_banner}/>
							{/if}
							<ReviewComponent/>
						{/if}
						<SupportPanel/>
					</div>

				</aside>
			</div>
		</main>
</AdminPageWithSidebar>