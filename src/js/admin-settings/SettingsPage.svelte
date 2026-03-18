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
            <AcPanel classNames={['acu-mb-3','acu-flex-grow', 'acu-max-w-[1520px]']}>
                <AcPanelHeader slot="header" title="Admin Columns Pro" type="h2" border/>
                <AcPanelBody slot="body" classNames={['acu-pb-10']}>
                    <p class="acu-font-bold acu-mt-0 acu-mb-4">{config.upgrade_panel.subtitle}</p>

                    <div class="acu-grid md:acu-grid-cols-3 acu-grid-cols-2 acu-gap-x-8 acu-gap-y-2 acu-mb-6">
                        {#each config.upgrade_panel.features as feature}
                            <div class="feature-item">
                                <div class="acu-flex acu-items-center acu-gap-1.5">
                                    <span class="acu-text-pink acu-font-bold">+</span>
                                    <span class="feature-label">{feature.label}</span>
                                </div>
                                <div class="feature-tooltip">{feature.tooltip}</div>
                            </div>
                        {/each}
                        <div>
                            <a href={config.upgrade_panel.upgrade_url} target="_blank">{config.upgrade_panel.view_all}</a>
                        </div>
                    </div>

                    <a href={config.upgrade_panel.upgrade_url}
                       target="_blank"
                       class="acui-button acui-button-pink">
                        {config.upgrade_panel.button}
                    </a>
                </AcPanelBody>
            </AcPanel>
        {/if}
    </main>

</div>

<style>
    .feature-item {
        position: relative;
    }

    .feature-label {
        border-bottom: 1px dotted currentColor;
        text-decoration: none;
    }

    .feature-tooltip {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        z-index: 100;
        width: 300px;
        padding: 12px 15px;
        background: #3D4350;
        color: #fff;
        font-size: 13px;
        line-height: 1.6;
    }

    .feature-item:hover .feature-tooltip {
        display: block;
    }
</style>