<script lang="ts">
    export let data: ListScreenData;
    export let config: { [key: string]: AC.Column.Settings.ColumnSettingCollection };

    let showData: boolean = false;
    let showConfig: boolean = false;

</script>


<div class="acu-bg-white">
    <div class="acu-mb-3">
    <button class="button" on:click={ () => showData = !showData}>Toggle Data</button>
    <button class="button" on:click={ () => showConfig = !showConfig}>Toggle Config</button>
    </div>
    {#if showData}
        <textarea value={JSON.stringify(data, null, 4)} class="acu-w-full" style="height:600px; font-size: 10px; line-height: 10px;"/>
    {/if}

    {#if showConfig}
    <textarea value={JSON.stringify(config, null, 4)} class="acu-w-full" style="height:600px; font-size: 10px; line-height: 10px;"/>
    {/if}
</div>