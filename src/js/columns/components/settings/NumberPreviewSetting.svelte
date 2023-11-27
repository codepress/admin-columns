<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";

    export let config: AC.Column.Settings.NumberSettings;
    export let data: any;

    const previewNumber: number = 7500000;
    let preview: string = previewNumber.toString();

    const refreshPreview = (decimals: string, decimal_point: string, thousands_sep: string) => {
        let formatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: parseInt(decimals),
        });

        const replaceThousands = '[ac1]';
        const replaceDecimal = '[ac2]';
        decimal_point = decimal_point ? decimal_point : '.';

        preview = formatter.format(previewNumber);
        preview = preview
					.replaceAll(',', replaceThousands)
					.replaceAll('.', replaceDecimal)
					.replaceAll(replaceThousands, thousands_sep)
					.replaceAll(replaceDecimal, decimal_point);
    }


    const watchData = (data: any) => {
        refreshPreview(
            data['number_decimals'] ?? 0,
            data['number_decimal_point'] ?? '.',
            data['number_thousands_separator'] ?? ''
        )
    }

    $: watchData(data)

</script>

<ColumnSetting label={config.label} name="number_preview">
	<code data-preview="">{preview}</code>
</ColumnSetting>