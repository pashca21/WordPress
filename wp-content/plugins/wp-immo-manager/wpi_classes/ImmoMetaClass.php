<?php
/**
 * Created by Media-Store.net.
 * User: Artur
 * Date: 22.01.2018
 * Time: 17:10
 */

namespace wpi\wpi_classes;


class ImmoMetaClass {

	/**
	 * @var $id
	 * The ID of Property
	 */
	public $id;

	/**
	 * @var $metaName
	 * which Meta to return
	 * by Default all Meta unserialized as Array()
	 */
	public $metaName;

	/**
	 * @var $meta
	 * metaData of Property
	 */
	private $meta;

	public function __construct( $id, $arguments = '' ) {
		$this -> id       = $id;
		$this -> metaName = $arguments;

		$this -> meta = $this->unserializeMeta( get_post_meta( $this -> id ) );
	}

	public function get() {
		return $this -> checkMetaName( $this -> metaName );
	}

	private function unserializeMeta( $meta = array() ) {
		$newMata = array();

		if ( is_array( $meta ) ):
			foreach ( $meta as $item => $value ):
				if ( @unserialize( $value[ 0 ] ) ):
					$newMata[ $item ] = unserialize( $value[ 0 ] );
				else:
					$newMata[ $item ] = $value[ 0 ];
				endif;
			endforeach;

			return $newMata;
		endif;
	}

	private function checkMetaName( $metaName ) {
		if(!$metaName) return $this->meta;

		if ( is_string( $metaName ) ):
			return $this -> meta[ $metaName ];
		elseif ( is_array( $metaName ) ):
			$newArray = array();
			foreach ( $metaName as $item => $value ):
				if ( is_integer( $item ) ):
					$newArray[ $value ] = $this -> meta[ $value ];
				else:
					$newArray[ $item ] = $this -> meta[ $item ];
				endif;
			endforeach;

			return $newArray;
		else:
			return $this -> meta;
		endif;
	}


}