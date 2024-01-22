<script lang="ts">
    import {currentListKey} from "../store/current-list-screen";
    import {createEventDispatcher} from "svelte";
    import {favoriteListKeysStore} from "../store/favorite-listkeys";

    const dispatch = createEventDispatcher();

    export let key: string;
    export let label: string

    const favoriteItem = () => {
        favoriteListKeysStore.favorite( key );
        dispatch('favorite', key)
    }

    const unfavoriteItem = () => {
        favoriteListKeysStore.unfavorite( key );
        dispatch('unfavorite', key)
    }

    const selectValue = () => {
        dispatch('selectItem', key)
    }

</script>

<li class="ac-menu-group-list__item" class:active={$currentListKey === key}>
	<a
		class:active={$currentListKey === key}
		class="ac-menu-group-list__link"
		href={'#'}
		on:click|preventDefault={ selectValue }>{label}

		{#if $favoriteListKeysStore.includes( key ) }
			<span
				role="none"
				class="dashicons dashicons-star-filled ac-menu-group-list__favorite"
				on:click|preventDefault|stopPropagation={unfavoriteItem}
			>

			</span>
		{:else}
			<span
				role="none"
				class="dashicons dashicons-star-empty ac-menu-group-list__favorite"
				on:click|preventDefault|stopPropagation={favoriteItem}>

			</span>
		{/if}

	</a>
</li>
