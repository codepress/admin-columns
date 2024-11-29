<script lang="ts">
    import {getAdminSettingsTranslation} from "./utils/global";
    import SettingSection from "./component/SettingSection.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AcConfirmation from "../plugin/ac-confirmation";
    import {restoreSettings} from "./ajax/requests";
    import {NotificationProgrammatic} from "../ui-wrapper/notification";

    const i18n = getAdminSettingsTranslation();

    const handleRestoreSettings = () => {
        restoreSettings({nonce: AC_SETTINGS._ajax_nonce}).then(( r ) => {
			if( r.data.success ) {
                NotificationProgrammatic.open({
					type: "success",
                    message: r.data.data.message
				});
			}
        });
    }

    const handleClick = () => {
        new AcConfirmation({
            message: i18n.restore_settings_warning,
            confirm: handleRestoreSettings
        }).create()
    }

</script>


<SettingSection title={i18n.restore_settings} subtitle={i18n.restore_settings_description}>

	<AcButton label={i18n.restore_settings} on:click={handleClick}/>

</SettingSection>

