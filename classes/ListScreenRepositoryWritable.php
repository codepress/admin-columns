<?php

namespace AC;

interface ListScreenRepositoryWritable extends ListScreenRepository {

	public function save( ListScreen $list_screen ): void;

	public function delete( ListScreen $list_screen ): void;

}