<script lang="ts">
    import {IntegrationItem, toggleIntegrationStatus} from "../ajax/requests";
    import AcToggle from "ACUi/element/AcToggle.svelte";
    import AcButton from "ACUi/element/AcButton.svelte";
    import AcBadge from "ACUi/element/AcBadge.svelte";
    import AcIcon from "ACUi/AcIcon.svelte";
    import {getAddonsConfig, getAddonsTranslation} from "../global";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";

    export let integration: IntegrationItem;
    export let isPro: boolean;
    export let recommended: boolean = false;

    let checked: boolean = integration.active;

    const handleStatusChange = () => {
        toggleIntegrationStatus({
            integration: integration.slug,
            status: checked
        }).then(response => {
            NotificationProgrammatic.open({
                type: "success",
                message: response.data.data
            });
        })
    }

    const addons = getAddonsConfig();
    const i18n = getAddonsTranslation();

</script>
<article class="integration-card">
	<div class="integration-card__header">
		{#if integration.plugin_active || recommended}
			<div class="integration-card__badges">
				{#if integration.plugin_active}
					<AcBadge type="success" classList={['integration-card__badge']}>
						&#10003; {i18n.plugin_detected}
					</AcBadge>
				{/if}
				{#if recommended}
					<span class="integration-card__badge-recommended">{i18n.recommended}</span>
				{/if}
			</div>
		{/if}
		<div class="integration-card__header-main">
			<a href="{integration.external_link}" target="_blank" class="integration-card__logo">
				<img src="{addons.asset_location}/{integration.plugin_logo}" alt="{integration.title}"/>
			</a>
			<div class="integration-card__header-copy">
				<h3 class="integration-card__title">{integration.title}</h3>
				<p class="integration-card__description">{integration.description}</p>
			</div>
		</div>
	</div>
	<div class="integration-card__body">
		{#if integration.features.length > 0}
			<ul class="integration-card__features">
				{#each integration.features as feature}
					<li>{feature}</li>
				{/each}
			</ul>
		{/if}

		{#if integration.audience}
			<div class="integration-card__audience-separator"></div>
			<p class="integration-card__audience">{integration.audience}</p>
		{/if}

		<div class="integration-card__actions">
			{#if isPro}
				{#if integration.plugin_active}
					<div class="integration-card__toggle">
						<strong>{i18n.enable_integration}</strong>
						<AcToggle bind:checked={checked} on:input={handleStatusChange}/>
					</div>
				{:else}
					<div class="integration-card__not-detected">
						<AcIcon icon="no-alt" pack="dashicons" customClass="acu-text-[#D63638]"/>
						<span>{i18n.plugin_not_detected}</span>
					</div>
				{/if}
			{:else}
				<AcButton iconLeft="lock" iconLeftPack="dashicons" type="pink" label="{i18n.buy_now}"
					href={addons.buy_url} target="_blank"/>
				<AcButton type="default" label="{i18n.learn_more}"
					href={integration.external_link} target="_blank"/>
			{/if}
		</div>
	</div>
</article>

<style>
	.integration-card {
		background: #fff;
		border: 1px solid var(--ac-border-color, #ccd6e4);
		border-radius: 16px;
		overflow: hidden;
		box-shadow: 0 4px 16px rgba(36, 48, 67, 0.05);
		display: flex;
		flex-direction: column;
		width: 100%;
		transition: box-shadow 0.2s ease;
	}

	.integration-card:hover {
		box-shadow: 0 8px 24px rgba(36, 48, 67, 0.1);
	}

	.integration-card__header {
		background: #f7f9fc;
		border-bottom: 1px solid var(--ac-border-color, #ccd6e4);
		padding: 20px 24px;
	}

	.integration-card__badges {
		display: flex;
		align-items: center;
		gap: 8px;
		margin-bottom: 14px;
	}

	.integration-card__badges :global(.integration-card__badge) {
		align-items: center;
		gap: 2px;
		font-weight: 600;
	}

	.integration-card__badge-recommended {
		display: inline-flex;
		align-items: center;
		padding: 4px 12px;
		border-radius: 999px;
		background: #fdf3d7;
		color: #7c6a2e;
		font-size: 13px;
		font-weight: 600;
	}

	.integration-card__header-main {
		display: flex;
		align-items: center;
		gap: 16px;
	}

	.integration-card__logo {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 72px;
		height: 72px;
		flex: 0 0 72px;
		border-radius: 16px;
		overflow: hidden;
		text-decoration: none;
	}

	.integration-card__logo img {
		max-width: 100%;
		max-height: 100%;
		object-fit: contain;
	}

	.integration-card__header-copy {
		flex: 1;
		min-width: 0;
	}

	.integration-card__title {
		margin: 0 0 4px;
		font-size: 1.1rem;
		font-weight: 700;
		line-height: 1.3;
		letter-spacing: -0.02em;
	}

	.integration-card__description {
		margin: 0;
		font-size: 0.95rem;
		line-height: 1.5;
		color: #617086;
	}

	.integration-card__body {
		padding: 24px;
		display: flex;
		flex-direction: column;
		flex: 1;
	}

	.integration-card__features {
		margin: 0 0 20px;
		padding: 0 0 0 20px;
		list-style: disc;
	}

	.integration-card__features li {
		font-size: 0.95rem;
		line-height: 1.6;
		color: #1d2327;
		padding: 2px 0;
	}

	.integration-card__audience-separator {
		height: 1px;
		background: var(--ac-border-color, #ccd6e4);
		margin-bottom: 16px;
	}

	.integration-card__audience {
		margin: 0 0 20px;
		font-size: 0.95rem;
		line-height: 1.6;
		color: #617086;
	}

	.integration-card__actions {
		margin-top: auto;
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.integration-card__toggle {
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 100%;
		padding-top: 8px;
		border-top: 1px solid var(--ac-border-color, #ccd6e4);
	}

	.integration-card__not-detected {
		display: flex;
		align-items: center;
		gap: 8px;
		width: 100%;
		padding-top: 8px;
		border-top: 1px solid var(--ac-border-color, #ccd6e4);
		color: #617086;
		font-size: 0.95rem;
	}
</style>
