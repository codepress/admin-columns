<script lang="ts">
	import ColumnsForm from "./ColumnsForm.svelte";
    import {MappedListScreenData} from "../../types/admin-columns";
    import {ListScreenData} from "../../types/requests";
    import {listScreenDataStore} from "../store/list-screen-data";


	export let config: ListScreenData
</script>

<section>
	<ColumnsForm bind:data={$listScreenDataStore} bind:config={config}></ColumnsForm>
</section>