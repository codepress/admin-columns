<script lang="ts">
    import AdminHeaderBar from "../components/AdminHeaderBar.svelte";
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
    import AcPanelHeader from "ACUi/acui-panel/AcPanelHeader.svelte";
    import {getAdminSettingsTranslation} from "./utils/global";
    import AcPanelBody from "ACUi/acui-panel/AcPanelBody.svelte";
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import HtmlSection from "../columns/components/HtmlSection.svelte";
    import SettingSections from "./utils/page-sections";
    import SettingSection from "./component/SettingSection.svelte";
    import {sprintf} from "@wordpress/i18n";
    import {getGeneralOption, persistGeneralOption} from "./ajax/requests";
    import {NotificationProgrammatic} from "../ui-wrapper/notification";
    import AcToggleButtons from "ACUi/acui-toggle-buttons/AcToggleButtons.svelte";


    const i18n = getAdminSettingsTranslation();
    let loadingShowButtonValue = true;
    let showButtonValue = true;

    getGeneralOption({name: 'show_edit_button', nonce: AC_SETTINGS._ajax_nonce}).then(r => {
        if (r.data.success) {
            showButtonValue = r.data.data === '1';
            loadingShowButtonValue = false;
        }
    })

    const handleToggleShowButton = () => {
        persistGeneralOption({
            nonce: AC_SETTINGS._ajax_nonce,
            name: 'show_edit_button',
            value: showButtonValue ? '1' : '0'
        }).then(() => {
            NotificationProgrammatic.open({
                message: "Success",
                type: "success",
            })
        })
    }


</script>

<AdminHeaderBar title={i18n.settings}/>

<div class="acu-mx-[50px] acu-pt-[70px]">

	<div class=" acu-hidden">
		<hr class="wp-header-end acu-hidden">
	</div>

	<main class="acu-flex acu-gap-4 acu-w-full">
		<AcPanel classNames={['acu-mb-3','acu-flex-grow', 'acu-max-w-[1520px]']}>
			<AcPanelHeader slot="header" title={i18n.settings} type="h2" border/>
			<AcPanelBody slot="body" classNames={['acu-pb-10']}>

				<SettingSection title="General Settings" subtitle="These settings affect the list table.">
					<div>
						<AcToggle bind:checked={showButtonValue} disabled={loadingShowButtonValue}
							on:input={handleToggleShowButton}>{sprintf( i18n.show_x_button, `"${i18n.edit_button}"` )}</AcToggle>
					</div>

					{#each SettingSections.getSections( 'inside_general' ) as component}
						<HtmlSection component={component}></HtmlSection>
					{/each}

				</SettingSection>

				{#each SettingSections.getSections( 'after_general' ) as component}
					<HtmlSection component={component}></HtmlSection>
				{/each}

			</AcPanelBody>
		</AcPanel>
	</main>

</div>