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

			// If 'fieldClass' is not set, but 'field' is, then use that to determine the appropriate field class.
			switch ( $args['field'] ) {
				case 'radio-group':
					$fieldClass = __NAMESPACE__ . '\\RadioGroupField';
					break;
				case 'select':
					$fieldClass = __NAMESPACE__ . '\\SelectField';
					break;
				case 'textarea':
					$fieldClass = __NAMESPACE__ . '\\TextareaField';
					break;
			}

		}

		return new $fieldClass( $name, $args );

	}

}