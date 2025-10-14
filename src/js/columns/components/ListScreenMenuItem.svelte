<script lang="ts">
    import {currentListKey, favoriteListKeysStore} from "../store";
    import {createEventDispatcher} from "svelte";
    import {persistMenuFavorite} from "../ajax/menu";
    import {NotificationProgrammatic} from "../../ui-wrapper/notification";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {fade} from 'svelte/transition';

    const dispatch = createEventDispatcher();
    const i18n = getColumnSettingsTranslation();

    export let key: string;
    export let label: string
    export let showActiveFavorite: boolean = false;

    const showUnknownErrorMessage = () => {
        NotificationProgrammatic.open({type: 'error', message: i18n.errors.ajax_unknown})
    }

    const favoriteItem = () => {
        persistMenuFavorite(key, true).then((response) => {
            if (!response.data.success) {
                showUnknownErrorMessage()
            }
        }).catch(() => showUnknownErrorMessage());

        favoriteListKeysStore.favorite(key);
        dispatch('favorite', key)
    }

    const unfavoriteItem = () => {
        persistMenuFavorite(key, false).then((response) => {
            if (!response.data.success) {
                showUnknownErrorMessage()
            }
        }).catch(() => showUnknownErrorMessage());

        favoriteListKeysStore.unfavorite(key);
        dispatch('unfavorite', key)
    }

    const selectValue = () => {
        dispatch('selectItem', key)
    }

</script>

<li class="ac-menu-group-list__item" class:active={$currentListKey === key} transition:fade={{ duration: 300 }}>
	<a
		class:active={$currentListKey === key}
		class="ac-menu-group-list__link"
		href={'#'}
		on:click|preventDefault={ selectValue }>{label}

		{#if $favoriteListKeysStore.includes( key ) }
			{#if showActiveFavorite}
			<span
				role="none"
				class="dashicons dashicons-star-filled ac-menu-group-list__favorite"
				on:click|preventDefault|stopPropagation={unfavoriteItem}
			>

			</span>
			{/if}

		{:else }
			<span
				role="none"
				class="dashicons dashicons-star-empty ac-menu-group-list__favorite"
				on:click|preventDefault|stopPropagation={favoriteItem}>

			</span>
		{/if}

	</a>
</li>
