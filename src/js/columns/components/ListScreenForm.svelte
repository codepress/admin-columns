<script lang="ts">
	import ColumnsForm from "./ColumnsForm.svelte";
    import {MappedListScreenData} from "../../types/admin-columns";

	export let data: MappedListScreenData
</script>

<section>
	<ColumnsForm bind:data={data}></ColumnsForm>
</section>