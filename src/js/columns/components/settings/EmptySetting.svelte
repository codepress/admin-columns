<script lang="ts">
    import {createEventDispatcher, onDestroy} from "svelte";

    export let config: AC.Column.Settings.TextSetting;

    const dispatch = createEventDispatcher();

    onDestroy(() => {
        dispatch('destroy', config);
    });

</script>
