<script lang="ts">
    import {getColumnSettingsConfig, getColumnSettingsTranslation} from "../../../utils/global";
    import {fade} from 'svelte/transition';
    import AcIcon from "ACUi/AcIcon.svelte";
    import ReviewLink from "./ReviewLink.svelte";
    import {AcPanel} from "ACUi/acui-panel";
    import SideBarPanelBody from "../SideBarPanelBody.svelte";

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

<AcPanel>
    <div slot="body">
        {#if initial}
            <div out:fade>
                <SideBarPanelBody title={i18n.review.happy}>
                    {@html i18n.review.help_improve}
                    <div class="acu-flex acu-gap-1 acu-pt-2">
                        <button class="ac-feedback-button" on:click={() => changeState('yes')}>
                            &#128077; {i18n.review.all_good}</button>
                        <button class="ac-feedback-button" on:click={() => changeState('no')}>
                            &#128078; {i18n.review.need_feature}</button>
                    </div>
                </SideBarPanelBody>
            </div>
        {/if}
        {#if state === 'yes'}
            <div in:fade>
                <SideBarPanelBody title={i18n.review.glad}>
                    {i18n.review.give_rating}
                    <div class="acu-flex acu-gap-1 acu-pt-2">
                        <ReviewLink
                                href="https://wordpress.org/support/plugin/codepress-admin-columns/reviews/#postform">
                            <AcIcon customClass="acu-mt-[-2px]" icon="star-empty" pack="dashicons"></AcIcon>
                            {i18n.review.rate}
                        </ReviewLink>
                        <ReviewLink
                                href="https://twitter.com/intent/tweet?text=I%27m+using+Admin+Columns+for+WordPress%21&url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fcodepress-admin-columns%2F&via=admincolumns&hashtags=admincolumns">
                            <AcIcon customClass="acu-mt-[-2px]" icon="twitter" pack="dashicons"></AcIcon>
                            {i18n.review.tweet}
                        </ReviewLink>
                    </div>
                </SideBarPanelBody>
            </div>
        {/if}

        {#if state === 'no'}
            <div out:fade>
                <SideBarPanelBody title={i18n.review.whats_wrong}>
                    {i18n.review.checkdocs}
                    <div class="acu-flex acu-gap-1 acu-pt-2">
                        <a href="https://wordpress.org/support/plugin/codepress-admin-columns/" target="_blank"
                           class="ac-feedback-button">{i18n.review.forum}</a>
                        <a href="{config.review.upgrade_url}" target="_blank"
                           class="ac-feedback-button">{i18n.review.check_pro}</a>
                    </div>
                </SideBarPanelBody>
            </div>
        {/if}
    </div>
</AcPanel>
