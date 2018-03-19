<?php

// sanitize message...
$this->message = wp_kses( $this->message, array(
	'strong' => array(),
	'br'     => array(),
	'a'      => array(
		'class' => true,
		'data'  => true,
		'href'  => true,
		'id'    => true,
		'title' => true,
	),
) );

// ... then add p container
$this->message = '<p>' . $this->message . '</p>';

require 'global-raw.php';