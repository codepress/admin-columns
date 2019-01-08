<?php

namespace AC;

use LogicException;
use wpdb;

class Transaction {

	const START = 1;
	const COMMIT = 2;
	const ROLLBACK = 3;

	/**
	 * @param integer $type
	 */
	protected function statement( $type ) {
		global $wpdb;

		if ( ! $wpdb instanceof wpdb ) {
			throw new LogicException( 'The WordPress database is not yet initialized.' );
		}

		switch ( $type ) {
			case self::START:
				$sql = 'START TRANSACTION';

				break;
			case self::COMMIT:
				$sql = 'COMMIT';

				break;
			case self::ROLLBACK:
				$sql = 'ROLLBACK';

				break;
			default:
				throw new LogicException( sprintf( 'Found invalid transaction statement: %s.', $type ) );
		}

		$wpdb->hide_errors();
		$wpdb->query( $sql );
	}

	/**
	 * Start a MySQL transaction
	 */
	public function start() {
		$this->statement( self::START );
	}

	/**
	 * Commit a MySQL transaction
	 */
	public function commit() {
		$this->statement( self::COMMIT );
	}

	/**
	 * Rollback a MySQL transaction
	 */
	public function rollback() {
		$this->statement( self::ROLLBACK );
	}

}