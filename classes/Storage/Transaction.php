<?php

namespace AC\Storage;

use LogicException;
use wpdb;

final class Transaction {

	const START = 1;
	const COMMIT = 2;
	const ROLLBACK = 3;

	/**
	 * @var bool
	 */
	private $started = false;

	/**
	 * @param bool $start Will start a transaction on creation if true
	 */
	public function __construct( $start = true ) {
		if ( $start === true ) {
			$this->start();
		}
	}

	/**
	 * @param integer $type
	 */
	private function statement( $type ) {
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
		if ( $this->started ) {
			throw new LogicException( 'Transaction has started already.' );
		}

		$this->started = true;

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