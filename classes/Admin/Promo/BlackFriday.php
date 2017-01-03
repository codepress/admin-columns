<?php

class AC_Admin_Promo_BlackFriday extends AC_Admin_Promo {

	public function __construct() {

		$this->set_title( '30% Off from Black Friday until Cyber Monday' );
		$this->set_discount( 30 );

		// 2016
		$this->add_date_range( '2016-11-25','2016-11-29' );

		// 2017
		$this->add_date_range( '2017-11-24','2017-11-28' );
	}

}