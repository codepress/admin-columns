<script lang="ts">
    import {getColumnSettingsTranslation} from "../../../utils/global";
    import {AcPanel, AcPanelBody, AcPanelHeader} from "ACUi/acui-panel";

    export let proBannerConfig: AC.Vars.Admin.Columns.ProBanner;

    const i18n = getColumnSettingsTranslation().pro.banner;
</script>

<div class="ac-probanner">
    <div class="ac-probanner__card">
        <div class="ac-probanner__accent"></div>
        <div class="ac-probanner__header">
            <span class="ac-probanner__badge">{proBannerConfig.badge ?? i18n.badge}</span>
            <h2 class="ac-probanner__title">{proBannerConfig.title ?? i18n.title}</h2>
            <p class="ac-probanner__description">
                {#if proBannerConfig.description_intro}<strong>{proBannerConfig.description_intro}</strong><br>{/if}
                {proBannerConfig.description ?? i18n.description}
            </p>
        </div>
        <div class="ac-probanner__body">
            {#if proBannerConfig.quote}
                <blockquote class="ac-probanner__quote">
                    <p>{proBannerConfig.quote.text}</p>
                    <cite>— {proBannerConfig.quote.cite}</cite>
                </blockquote>
            {/if}

            <div class="ac-probanner__feature-box">
                <p class="ac-probanner__feature-label">{proBannerConfig.features_label ?? i18n.features_label}</p>
                <ul class="ac-probanner__feature-list">
                    {#each proBannerConfig.features as feature}
                        <li>{feature.label}</li>
                    {/each}
                </ul>
            </div>

            {#if (proBannerConfig.integrations ?? []).length > 0}
                <p class="ac-probanner__integrations">
                    {i18n.works_with}
                    {#each proBannerConfig.integrations as integration, i}
                        {#if i > 0} ·{/if}
                        <a target="_blank" href="{integration.url}">{integration.label}</a>
                    {/each}
                </p>
            {/if}

            <div class="ac-probanner__trust">
                <span class="ac-probanner__star">&#9733;</span>
                <span>{i18n.trust}</span>
            </div>
            <a class="ac-probanner__cta" href="{proBannerConfig.promo_url}" target="_blank">
                {proBannerConfig.upgrade_cta ?? i18n.upgrade_cta}
            </a>
            <p class="ac-probanner__guarantee">{i18n.guarantee}</p>
            <a class="ac-probanner__see-all" href="{proBannerConfig.promo_url}" target="_blank">
                {i18n.see_all} &rarr;
            </a>
        </div>
    </div>


    {#if proBannerConfig.promo}
        <AcPanel>
            <svelte:fragment slot="body">
                <AcPanelHeader title={proBannerConfig.promo.title} type="h3-alt"/>
                <AcPanelBody classNames={['acu-pt-1']}>
                    <p class="acu-mt-[0]">{proBannerConfig.promo.discount_until}</p>
                    <a class="ac-probanner__cta" href={proBannerConfig.promo.url} target="_blank">
                        {proBannerConfig.promo.button_label}
                    </a>
                </AcPanelBody>
            </svelte:fragment>
        </AcPanel>
    {:else}
        <AcPanel classNames={['ac-probanner__discount-card']}>
            <svelte:fragment slot="body">
                <AcPanelHeader title={i18n.discount_title} type="h3-alt"/>
                <AcPanelBody classNames={['acu-pt-1']}>
                    <p class="acu-mt-[0]">{i18n.discount_description}</p>
                    <form method="post"
                          action="https://www.admincolumns.com/admin-columns-pro/?utm_source=plugin-installation&utm_medium=send-coupon">
                        <input name="action" type="hidden" value="mc_upgrade_pro">
                        <input type="email" name="EMAIL" required placeholder="{i18n.your_email}">
                        <input type="submit" class="ac-probanner__cta-secondary" value="{i18n.send_discount}">
                    </form>
                    <p class="ac-probanner__note">{i18n.discount_note}</p>
                </AcPanelBody>
            </svelte:fragment>
        </AcPanel>

    {/if}
</div>