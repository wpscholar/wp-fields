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
	 * @return PostMetaStorage
	 */
	public static function create( $storageType ) {
		switch ( $storageType ) {
			case 'post_meta':
			default:
				return new PostMetaStorage();
		}
	}

}