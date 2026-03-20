<script lang="ts">
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../../utils/global";
    import {fade} from 'svelte/transition';
    import AcIcon from "ACUi/AcIcon.svelte";
    import ReviewLink from "./ReviewLink.svelte";

    const config = getColumnSettingsConfig();
    const i18n = getColumnSettingsTranslation();
    let initial: boolean = true;
    let state: 'yes' | 'no' | '' = ''

    const changeState = (newState: 'yes' | 'no') => {
        initial = false;
        setTimeout(() => {
            state = newState;
        }, 400)
    }
</script>

<div class="ac-feedback-card">
    {#if initial}
        <div out:fade>
            <h3 class="ac-feedback-card__title">{i18n.review.happy}</h3>
            <p class="ac-feedback-card__description">{i18n.review.help_improve}</p>
            <div class="ac-feedback-card__actions">
                <button class="ac-feedback-card__button" on:click={() => changeState('yes')}>&#128077; {i18n.review.all_good}</button>
                <button class="ac-feedback-card__button" on:click={() => changeState('no')}>&#128078; {i18n.review.need_feature}</button>
            </div>
        </div>
    {/if}

    {#if state === 'yes'}
        <div in:fade>
            <h3 class="ac-feedback-card__title">{i18n.review.glad}</h3>
            <p class="ac-feedback-card__description">{i18n.review.give_rating}</p>
            <div class="ac-feedback-card__actions">
                <ReviewLink href="https://wordpress.org/support/plugin/codepress-admin-columns/reviews/#postform">
                    <AcIcon icon="star-empty" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
                    {i18n.review.rate}
                </ReviewLink>
                <ReviewLink href="https://twitter.com/intent/tweet?text=I%27m+using+Admin+Columns+for+WordPress%21&url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fcodepress-admin-columns%2F&via=admincolumns&hashtags=admincolumns">
                    <AcIcon icon="twitter" pack="dashicons" customClass="acu-mt-[-2px]"></AcIcon>
                    {i18n.review.tweet}
                </ReviewLink>
            </div>
        </div>
    {/if}

    {#if state === 'no'}
        <div in:fade>
            <h3 class="ac-feedback-card__title">{i18n.review.whats_wrong}</h3>
            <p class="ac-feedback-card__description">{i18n.review.checkdocs}</p>
            <div class="ac-feedback-card__actions">
                <a href="https://wordpress.org/support/plugin/codepress-admin-columns/" target="_blank" class="ac-feedback-card__button">{i18n.review.forum}</a>
                <a href="{config.review.upgrade_url}" target="_blank" class="ac-feedback-card__button">{i18n.review.check_pro}</a>
            </div>
        </div>
    {/if}
</div>
