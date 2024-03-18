<script>
	import AcSkeleton from "ACUi/element/AcSkeleton.svelte";
</script>

<div class="ac-columns">
	<header class="ac-columns__header">
		<div class="ac-columns__header__table">
			<AcSkeleton width="150px" height="40px"/>
		</div>
		<div class="ac-columns__header__title">
			<AcSkeleton height="40px"/>
		</div>
		<div class="ac-columns__header__action">
			<AcSkeleton height="40px" width="60px"/>
		</div>
	</header>
	<div class="ac-columns__body">
		{#each new Array( 5 ) as i}
			<div class="ac-column">
				<header class="ac-column-header acu-flex acu-py-2 acu-pr-6 acu-items-center acu-bg-[#fff]">
					<div class="ac-column-header__move acu-cursor-move">

					</div>
					<div class="ac-column-header__label">
						<div class="acu-pb-4">
							<AcSkeleton width="260px"/>
						</div>
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
					</div>
					<div class="ac-column-header__open-indicator acu-flex acu-justify-end">
						<button class="ac-open-indicator"><span class="dashicons dashicons-arrow-down-alt2"></span>
						</button>
					</div>
				</header>
			</div>
		{/each}
	</div>
	<footer class="ac-columns__footer">
		<div>
			<AcSkeleton height="36px" width="120px"/>
		</div>
	</footer>
</div>