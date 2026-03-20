<script>
	import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../utils/global";

	const i18n = getColumnSettingsTranslation().support;
	const config = getColumnSettingsConfig();
</script>

<div class="ac-feedback-card">
	<h3 class="ac-feedback-card__title">{i18n.title}</h3>
	<p class="ac-feedback-card__description">{@html config.support.description}</p>
</div>