<script>
	import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
	import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";

	const i18n = getColumnSettingsTranslation().support;
	const config = getColumnSettingsConfig();
</script>

<AcPanel title={i18n.title}>
	<p class="acu-mt-[0]">{@html config.support.description}</p>
</AcPanel>