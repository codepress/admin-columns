<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {ListScreenColumnData} from "../../../types/requests";
    import WidthInput from "./input/WidthInput.svelte";

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: ListScreenColumnData;
    export let disabled: boolean = false;

    let widthRowSetting = setting as unknown as AC.Column.Settings.WidthSetting;

</script>

<ColumnSetting description="" label={widthRowSetting.label}>
	<WidthInput
		bind:data={data}
		disabled={disabled}/>
</ColumnSetting>