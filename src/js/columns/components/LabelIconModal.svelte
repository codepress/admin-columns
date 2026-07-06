<script lang="ts">
    import {AcDashiconPicker} from "ACUi/index";
    import {getColumnSettingsTranslation} from "../utils/global";
    import {createEventDispatcher} from "svelte";

    export let value: string;

    const i18n = getColumnSettingsTranslation();
    const dispatch = createEventDispatcher();

    // The picker returns a dashicon slug; the column label stores it as markup.
    const handleChange = (event: CustomEvent<string>) => {
        value = `<span class="dashicons ${event.detail}"></span>`;
        dispatch('change', value);
    }
</script>

<AcDashiconPicker
	searchLabel={i18n.global.search}
	selectLabel={i18n.global.select}
	on:change={handleChange}
	on:close
/>
