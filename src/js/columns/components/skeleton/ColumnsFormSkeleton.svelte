<script>
	import AcSkeleton from "ACUi/element/AcSkeleton.svelte";
	import {AcPanel, AcPanelFooter, AcPanelHeader} from "ACUi/acui-panel";
</script>

<AcPanel>
	<AcPanelHeader slot="header" border>

		<div class="acu-flex acu-gap-4">
			<div class="acu-flex acu-gap-2 acu-items-center">
				<AcSkeleton width="150px" height="40px"/>
			</div>
			<div class="acu-flex-grow acu-max-w-[400px]">
				<AcSkeleton height="40px"/>
			</div>
		</div>

	</AcPanelHeader>
	<div slot="body">
		{#each new Array( 5 ) as i}
			<div
				class="ac-column acu-px-4 acu-py-2 acu-flex acu-mb-2 acu-border-b-ui-border acu-border-b acu-border-solid-b" style="background-color: #fff">
				<div class="acu-flex-grow">
					<AcSkeleton width="260px"/>
				</div>
				<div class="ac-column-header__actions acu-hidden lg:acu-flex acu-items-center acu-gap-1 acu-justify-end">
					<div>
						<AcSkeleton width="34px" height="34px"/>
					</div>
					<div>
						<AcSkeleton width="34px" height="34px"/>
					</div>
					<div>
						<AcSkeleton width="34px" height="34px"/>
					</div>
					<div>
						<AcSkeleton width="34px" height="34px"/>
					</div>
					<div>
						<AcSkeleton width="34px" height="34px"/>
					</div>
					<div>
						<AcSkeleton width="34px" height="34px"/>
					</div>
					<div class="acu-px-2">
						<span class="dashicons dashicons-arrow-down-alt2"></span>
					</div>
				</div>

			</div>
		{/each}
	</div>
	<AcPanelFooter slot="footer" classNames={['acu-flex acu-justify-end']}>
		<div style="width: 120px">
		<AcSkeleton height="36px" width="120px"/>
		</div>
	</AcPanelFooter>
</AcPanel>