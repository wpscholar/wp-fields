<?php

namespace wpscholar\WordPressFields\Storage;

/**
 * Class FieldStorageFactory
 *
 * @package wpscholar\WordPressFields\Storage
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

		// Default storage class
		$storageClass = __NAMESPACE__ . '\\PostMetaStorage';

		if ( class_exists( $storageType ) ) {

			// If class is explicitly passed, just use that
			$storageClass = $storageType;

		} else {

			// Otherwise, derive class name from storage type
			$storageType = str_replace( ' ', '',
				ucwords( str_replace( [ '-', '_' ], ' ', strtolower( $storageType ) ) )
			);

			$class = __NAMESPACE__ . '\\' . $storageType . 'Storage';

			if ( class_exists( $class ) ) {
				$fieldClass = $class;
			}
		}

		return new $storageClass();

	}

}