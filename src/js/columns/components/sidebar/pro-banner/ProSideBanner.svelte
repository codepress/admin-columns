<script lang="ts">
    import {getColumnSettingsTranslation} from "../../../utils/global";

    export let proBannerConfig: AC.Vars.Admin.Columns.ProBanner;

    const i18n = getColumnSettingsTranslation().pro.banner;
    const features = proBannerConfig.features;
    const integrations = proBannerConfig.integrations ?? [];
    const promo = proBannerConfig.promo;
</script>

<div class="ac-probanner">
    <div class="ac-probanner__card">
        <div class="ac-probanner__accent"></div>
        <div class="ac-probanner__header">
            <span class="ac-probanner__badge">{i18n.badge}</span>
            <h2 class="ac-probanner__title">{i18n.title}</h2>
            <p class="ac-probanner__description">{i18n.description}</p>
        </div>
        <div class="ac-probanner__body">
            <div class="ac-probanner__feature-box">
                <p class="ac-probanner__feature-label">{i18n.features_label}</p>
                <ul class="ac-probanner__feature-list">
                    {#each features as feature}
                        <li>{feature.label}</li>
                    {/each}
                </ul>
            </div>

            {#if integrations.length > 0}
                <p class="ac-probanner__integrations">
                    {i18n.works_with}
                    {#each integrations as integration, i}
                        {#if i > 0} · {/if}
                        <a target="_blank" href="{integration.url}">{integration.label}</a>
                    {/each}
                </p>
            {/if}

            <div class="ac-probanner__trust">
                <span class="ac-probanner__star">&#9733;</span>
                <span>{i18n.trust}</span>
            </div>

            <a target="_blank" href="{proBannerConfig.promo_url}" class="ac-probanner__cta">
                {i18n.upgrade_cta}
            </a>
            <p class="ac-probanner__guarantee">{i18n.guarantee}</p>
            <a target="_blank" href="{proBannerConfig.promo_url}" class="ac-probanner__see-all">
                {i18n.see_all} &rarr;
            </a>
        </div>
    </div>

    {#if promo}
        <div class="ac-probanner__discount-card">
            <h3>{promo.title}</h3>
            <p>{promo.discount_until}</p>
            <a target="_blank" href="{promo.url}" class="ac-probanner__cta">
                {promo.button_label}
            </a>
        </div>
    {:else}
        <div class="ac-probanner__discount-card">
            <h3>{i18n.discount_title}</h3>
            <p>{i18n.discount_description}</p>
            <form method="post" action="https://www.admincolumns.com/admin-columns-pro/?utm_source=plugin-installation&utm_medium=send-coupon">
                <input name="action" type="hidden" value="mc_upgrade_pro">
                <input type="email" name="EMAIL" required placeholder="{i18n.your_email}">
                <input type="submit" class="ac-probanner__cta-secondary" value="{i18n.send_discount}">
            </form>
            <p class="ac-probanner__note">{i18n.discount_note}</p>
        </div>
    {/if}
</div>
