<script lang="ts">
    import AdminHeaderBar from "../components/AdminHeaderBar.svelte";
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
    import AcPanelHeader from "ACUi/acui-panel/AcPanelHeader.svelte";
    import {getAdminSettingsConfig, getAdminSettingsTranslation} from "./utils/global";
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
    const config = getAdminSettingsConfig();
    let loadingShowButtonValue = true;
    let showButtonValue = true;

    getGeneralOption({name: 'show_edit_button', nonce: config._ajax_nonce}).then(r => {
        if (r.data.success) {
            showButtonValue = r.data.data === '1' || typeof r.data.data === 'undefined';
            loadingShowButtonValue = false;
        }
    })

    const handleToggleShowButton = () => {
        persistGeneralOption({
            nonce: config._ajax_nonce,
            name: 'show_edit_button',
            value: showButtonValue ? '1' : '0'
        }).then(() => {
            NotificationProgrammatic.open({
                message: i18n.settings_saved_successful,
                type: "success",
            })
        })
    }


</script>

<AdminHeaderBar title={i18n.settings}/>

<div class="acu-mx-[50px] acu-pt-[70px]">

    <div class="acu-max-w-[1520px]">
        <hr class="wp-header-end acu-hidden">
    </div>

    <main class="acu-flex acu-flex-col acu-gap-4 acu-w-full">
        <AcPanel classNames={['acu-mb-3','acu-flex-grow', 'acu-max-w-[1520px]']}>
            <AcPanelHeader slot="header" title={i18n.settings} type="h2" border/>
            <AcPanelBody slot="body" classNames={['acu-pb-10']}>

                <SettingSection title="General Settings" subtitle="These settings affect the list table.">
                    <div>
                        <AcToggle bind:checked={showButtonValue} disabled={loadingShowButtonValue}
                                  on:input={handleToggleShowButton}>{sprintf(i18n.show_x_button, `"${i18n.edit_button}"`)}</AcToggle>
                    </div>

                    {#each SettingSections.getSections('inside_general') as component}
                        <HtmlSection component={component}></HtmlSection>
                    {/each}

                </SettingSection>

                {#each SettingSections.getSections('after_general') as component}
                    <HtmlSection component={component}></HtmlSection>
                {/each}

            </AcPanelBody>
        </AcPanel>

        {#if config.upgrade_panel}
            <section class="ac-settings-banner acu-max-w-[1520px]">
                <div class="ac-settings-banner__top">
                    <span class="ac-settings-banner__badge">{config.upgrade_panel.badge}</span>
                    <h2 class="ac-settings-banner__title">{config.upgrade_panel.title}</h2>
                    <p class="ac-settings-banner__copy">{config.upgrade_panel.subtitle}</p>

                    <div class="ac-settings-banner__cta-row">
                        <a href={config.upgrade_panel.upgrade_url} target="_blank" class="ac-settings-banner__btn-primary">
                            {config.upgrade_panel.button}
                        </a>
                        <a href={config.upgrade_panel.upgrade_url} target="_blank" class="ac-settings-banner__btn-secondary">
                            {config.upgrade_panel.view_all}
                        </a>
                        <span class="ac-settings-banner__trust">
                            <span class="ac-settings-banner__star">&#9733;</span>
                            {config.upgrade_panel.trust}
                        </span>
                    </div>
                </div>

                <div class="ac-settings-banner__body">
                    <div class="ac-settings-banner__feature-grid">
                        {#each config.upgrade_panel.feature_groups as group}
                            <div class="ac-settings-banner__feature-group">
                                <h3>{group.title}</h3>
                                <ul>
                                    {#each group.features as feature}
                                        <li>{feature}</li>
                                    {/each}
                                </ul>
                            </div>
                        {/each}
                    </div>
                </div>

            </section>
        {/if}
    </main>

</div>
