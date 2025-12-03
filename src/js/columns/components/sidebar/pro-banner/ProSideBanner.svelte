<script lang="ts">
    import {getColumnSettingsTranslation} from "../../../utils/global";
    import {sprintf} from "@wordpress/i18n";
    import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";

    export let proBannerConfig: AC.Vars.Admin.Columns.ProBanner;

    const i18n = getColumnSettingsTranslation().pro.banner;
    const features = proBannerConfig.features;
    const integrations = proBannerConfig.integrations ?? [];
    const promo = proBannerConfig.promo;

</script>

<div class="acu-bg-gray-dark acu-p-[20px] ac-probanner acu-rounded-t-[10px]">
	<h3 class="acu-text-[white] acu-text-[34px] acu-leading-snug acu-my-[0] ">
		{i18n.title}
		<span class="acu-text-pink">{i18n.title_pro}</span>
	</h3>
	<div class="acu-text-[white]">
		<p>{i18n.sub_title}</p>
		<ul class="acu-mb-4">
			{#each features as feature}
				<li><a href="{feature.url}">{feature.label}</a></li>
			{/each}
		</ul>

		{#if integrations.length > 0}
			<p class="acu-font-bold">{i18n.integrations}</p>
			<ul class="acu-mb-4 -special">
				{#each integrations as integration}
					<li><a href="{integration.url}">{integration.label}</a></li>
				{/each}
			</ul>
		{/if}
		{#if !promo}
			<a target="_blank"
				href="{proBannerConfig.promo_url}"
				class="acui-button acui-button-pink acu-block acu-text-center acu-text-[15px]">
				{i18n.get_acp}
			</a>
		{/if}
	</div>
</div>
{#if promo}
	<AcPanel title={promo.title} rounded={false} classNames={['acu-bg-[#FDEF95] acu-rounded-b-[10px]']}>
		<a target="_blank"
			href="{promo.url}" class="acui-button acui-button-pink acu-block acu-text-center acu-text-[15px] ">
			{promo.button_label}</a>
		<p>
			{promo.discount_until}
		</p>
	</AcPanel>
{:else}
	<AcPanel title={sprintf( i18n.get_percentage_off, proBannerConfig.discount + '%' )} rounded={false}
		classNames={['acu-rounded-b-[10px]']}>

		<p class="acu-mt-[0]">
			{sprintf( i18n.submit_email, proBannerConfig.discount + '%' )}
		</p>
		<form method="post" action="https://www.admincolumns.com/admin-columns-pro/?utm_source=plugin-installation&utm_medium=send-coupon">
			<input name="action" type="hidden" value="mc_upgrade_pro">
			<div class="acu-mb-2">
				<AcInputGroup>
					<input type="email" name="EMAIL" required placeholder={i18n.your_email}>
				</AcInputGroup>
			</div>
			<div class="acu-mb-2">
				<AcInputGroup>
					<input type="text" name="FNAME" placeholder={i18n.your_first_name} required>
				</AcInputGroup>
			</div>
			<input type="submit"
				class="acui-button acui-button-pink acu-block acu-w-full acu-text-center acu-text-[15px]"
				value={i18n.send_discount}/>
		</form>
	</AcPanel>
{/if}