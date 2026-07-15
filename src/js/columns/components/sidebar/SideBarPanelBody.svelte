<script lang="ts">
    import {AcPanelBody, AcPanelHeader} from "ACUi/acui-panel";

    export let title: string;
    export let type: 'h3' | 'h2' | 'h3-alt' = 'h3-alt';
</script>


<AcPanelHeader title={title} type={type}/>
<AcPanelBody classNames={['acu-pt-1']}>
    <slot></slot>
</AcPanelBody>

