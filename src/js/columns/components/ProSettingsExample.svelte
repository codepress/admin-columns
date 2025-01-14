<script>
	import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
	import PreferenceSection from "./layout/PreferenceSection.svelte";
	import AcToggle from "ACUi/element/AcToggle.svelte";
	import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../utils/global";
	import PreferenceSettingItem from "./layout/PreferenceSettingItem.svelte";
	import AcButton from "ACUi/element/AcButton.svelte";
	import AcIcon from "ACUi/AcIcon.svelte";

	const i18n = getColumnSettingsTranslation()
	const config = getColumnSettingsConfig()

</script>

<div class="acu-relative">
	<div
		class="acu-shadow acu-bg-[#fff] acu-opacity-50 acu-blur-[1px] acu-px-6 acu-py-6 acu-my-6 acu-rounded-lg acu-border acu-border-solid acu-border-ui-border">

		<PreferenceSection
			label={i18n.pro.settings.conditionals.conditionals}
			description={i18n.pro.settings.conditionals.description}>
			<AcInputGroup containerClasses="acu-mb-2">
				<input type="text" disabled readonly placeholder={i18n.pro.settings.conditionals.select_roles}>
			</AcInputGroup>
			<AcInputGroup>
				<input type="text" disabled readonly placeholder={i18n.pro.settings.conditionals.select_users}>
			</AcInputGroup>
		</PreferenceSection>


		<PreferenceSection
			label={i18n.pro.settings.elements.table_elements}
			description={i18n.pro.settings.elements.description}
		>
			<div class="xl:acu-flex acu-flex-1 acu-gap-3">
				<div class="acu-mb-8 xl:acu-w-1/2">
					<h4 class="acu-uppercase acu-mb-3 acu-font-medium acu-mt-[0]">{i18n.pro.settings.elements.default}</h4>
					{#each config.table_elements.default as element}
						<div class="acu-mb-1.5">
							<AcToggle disabled label={element}/>
						</div>
					{/each}
				</div>
				<div class="acu-mb-8 xl:acu-w-1/2">
					<h4 class="acu-uppercase acu-mb-3 acu-font-medium acu-mt-[0]">{i18n.pro.settings.elements.features}</h4>
					{#each config.table_elements.features as element}
						<div class="acu-mb-1.5">
							<AcToggle disabled label={element}/>
						</div>
					{/each}
				</div>
			</div>
		</PreferenceSection>

		<PreferenceSection
			label={i18n.pro.settings.preferences.preferences}
			description={i18n.pro.settings.preferences.description}
		>
			<PreferenceSettingItem label={i18n.pro.settings.preferences.horizontal_scrolling}>
				<AcToggle disabled/>
			</PreferenceSettingItem>
			<PreferenceSettingItem label={i18n.pro.settings.preferences.sorting}>
				<AcInputGroup>
					<input type="text" disabled readonly placeholder={i18n.pro.settings.conditionals.select_roles}>
				</AcInputGroup>
			</PreferenceSettingItem>
			<PreferenceSettingItem label={i18n.pro.settings.preferences.primary_column}>
				<AcInputGroup>
					<input type="text" disabled readonly placeholder='Default'>
				</AcInputGroup>
			</PreferenceSettingItem>
			<PreferenceSettingItem label={i18n.pro.settings.preferences.wrapping}>
				<AcInputGroup>
					<input type="text" disabled readonly placeholder={i18n.pro.settings.preferences.wrapping_options.clip}>
				</AcInputGroup>
			</PreferenceSettingItem>

		</PreferenceSection>

	</div>
	<div class="acu-absolute acu-left-1/2 acu-top-1/2 acu-bg-[white] acu-p-2 acu-flex acu-gap-2 acu-items-center acu-justify-center acu-text-center acu-transition-all-0.3s ease-in-out"
		style="box-shadow: 0 0 19px 0 rgba(0,0,0,.44); transform: translateX(-100px);">
		<AcIcon icon="lock" pack="dashicons" customClass="acu-text-pink"/>
		<span class="acu-mr-2">{i18n.pro.settings.preferences.unlock}</span>
		<AcButton type="pink" label="Upgrade" href={config.urls.upgrade} target="_blank"/>
	</div>
</div>