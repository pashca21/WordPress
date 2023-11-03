<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 03.11.2017
 * Time: 09:15
 */

namespace wpi\wpi_classes;

/**
 * Class Wpi_Loop_Builder
 * @package wpi\wpi_classes
 * @scince 2.2.4
 */
class Wpi_Loop_Builder extends \WP_Query {

	public function __construct( $args ) {

		$this -> getQuery($args);

	}

	public function getQuery($args) {
		zeigen($args);
		return new \WP_Query($args);
	}
}