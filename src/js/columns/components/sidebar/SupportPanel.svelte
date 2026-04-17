<script>
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";
    import SideBarPanel from "./SideBarPanel.svelte";

    const i18n = getColumnSettingsTranslation().support;
    const config = getColumnSettingsConfig();
</script>

<SideBarPanel title={i18n.title}>
    {@html config.support.description}
</SideBarPanel>