<?php

namespace wpscholar\WordPress;

/**
 * Class FieldStorageFactory
 *
 * @package wpscholar\WordPress
 */
class FieldStorageFactory {

	/**
	 * Factory for generating a field storage object from a string
	 *
	 * @param string $storageType
	 *
	 * @return FieldStorage
	 */
	public static function create( $storageType = null ) {
		switch ( $storageType ) {
			default:
				return new PostMetaStorage();
		}
	}

}