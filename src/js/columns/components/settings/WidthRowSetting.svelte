<script lang="ts">
    import {listScreenIsReadOnly} from "../../store/read_only";
    import ColumnSetting from "../ColumnSetting.svelte";
    import {ListScreenColumnData} from "../../../types/requests";
    import WidthInput from "./input/WidthInput.svelte";

    export let setting: AC.Column.Settings.AbstractColumnSetting;
    export let data: ListScreenColumnData;

    let widthRowSetting = setting as unknown as AC.Column.Settings.WidthSetting;

</script>

<ColumnSetting description="" label={widthRowSetting.label}>
	<WidthInput
		bind:data={data}
		disabled={$listScreenIsReadOnly}
		config={widthRowSetting}/>
</ColumnSetting>