<script lang="ts">
    import {createEventDispatcher, onDestroy} from "svelte";

    export let config: AC.Column.Settings.TextSetting;
    export let data: any;

    const dispatch = createEventDispatcher();

    onDestroy(() => {
        dispatch('destroy', config);
    });

</script>
