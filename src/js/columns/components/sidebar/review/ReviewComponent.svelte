<script lang="ts">
    import AcPanel from "ACUi/acui-panel/AcPanel.svelte";
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../../utils/global";
    import ReviewButton from "./ReviewButton.svelte";
    import {fade} from 'svelte/transition';
    import AcIcon from "ACUi/AcIcon.svelte";
    import ReviewLink from "./ReviewLink.svelte";


    const config = getColumnSettingsConfig();
    const i18n = getColumnSettingsTranslation();
    let initial: boolean = true;
    let state: 'yes' | 'no' | '' = ''

    const changeState = (newState: 'yes' | 'no') => {
        title = '';
        initial = false;
        setTimeout(() => {
            state = newState;
        }, 400)
    }

    $: title = i18n.review.happy;
</script>

<AcPanel title={title}>
	{#if initial}
		<div out:fade>
			<div class="acu-flex acu-gap-2">
				<ReviewButton on:click={()=> changeState('yes')}>{i18n.review.yes}</ReviewButton>
				<ReviewButton on:click={()=> changeState('no')}>{i18n.review.no}</ReviewButton>
			</div>
		</div>
	{/if}

	{#if state === 'yes'}
		<div in:fade>
			<p>{i18n.review.glad}</p>
			<p>{i18n.review.give_rating}</p>
			<div class="acu-flex acu-gap-2">
				<ReviewLink href="https://wordpress.org/support/plugin/codepress-admin-columns/reviews/#postform">
					<AcIcon icon="star-empty" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
					{i18n.review.rate}
				</ReviewLink>
				<ReviewLink href="https://twitter.com/intent/tweet?text=I%27m+using+Admin+Columns+for+WordPress%21&url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fcodepress-admin-columns%2F&via=admincolumns&hashtags=admincolumns">
					<AcIcon icon="twitter" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
					{i18n.review.tweet}
				</ReviewLink>
				<ReviewLink href="{config.review.upgrade_url}">
					<AcIcon icon="cart" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
					{i18n.review.buy}
				</ReviewLink>
			</div>
		</div>
	{/if}

	{#if state === 'no'}
		<div in:fade>
			<p>{i18n.review.whats_wrong}</p>
			<p>{i18n.review.checkdocs}</p>
			<div class="acu-flex acu-gap-2">
				<ReviewLink href="{config.review.doc_url}">
					<AcIcon icon="editor-help" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
					{i18n.review.docs}
				</ReviewLink>
				<ReviewLink href="https://wordpress.org/support/plugin/codepress-admin-columns/">
					<AcIcon icon="wordpress" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
					{i18n.review.forum}
				</ReviewLink>
			</div>
		</div>
	{/if}

</AcPanel>