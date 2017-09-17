<?php

namespace wpscholar\WordPress;

/**
 * Class FieldFactory
 *
 * @package wpscholar\WordPress
 */
class FieldFactory {

	/**
	 * Factory for generating a field
	 *
	 * @param string $name
	 * @param array $args
	 *
	 * @return Field
	 */
	public static function create( $name, array $args ) {

		// Default to input field
		$fieldClass = __NAMESPACE__ . '\\InputField';

		if ( isset( $args['fieldClass'] ) && class_exists( $args['fieldClass'] ) ) {

			// If 'fieldClass' is explicitly passed, then use it.
			$fieldClass = $args['fieldClass'];

		} else if ( isset( $args['field'] ) ) {

			// Derive 'fieldClass' based on 'field' name

			$fieldType = str_replace( ' ', '',
				ucwords( str_replace( [ '-', '_' ], ' ', strtolower( $args['field'] ) ) )
			);

			$class = __NAMESPACE__ . '\\' . $fieldType . 'Field';

			if ( class_exists( $class ) ) {
				$fieldClass = $class;
			}

		}

		return new $fieldClass( $name, $args );

	}

}