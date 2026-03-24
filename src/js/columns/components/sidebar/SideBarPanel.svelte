<script lang="ts">
    import {AcPanel} from "ACUi/acui-panel";
    import SideBarPanelBody from "./SideBarPanelBody.svelte";

    export let title: string;
</script>

<AcPanel>
    <svelte:fragment slot="body">
        <SideBarPanelBody title={title}>
            <slot></slot>
        </SideBarPanelBody>
    </svelte:fragment>
</AcPanel>