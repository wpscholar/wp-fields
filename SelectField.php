<?php

namespace wpscholar\WordPress;

/**
 * Class SelectField
 *
 * @package wpscholar\WordPress
 *
 * @property bool $isMultiSelect
 */
class SelectField extends Field {

	/**
	 * Field constructor.
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function __construct( $name, array $args = [] ) {
		parent::__construct( $name, $args );

		// Update sanitization to properly handle array of data if is a multi-select.
		if ( $this->isMultiSelect ) {
			$this->_sanitize = function ( array $data ) {
				return array_map( 'sanitize_text_field', $data );
			};
		}
	}

	/**
	 * Return field markup as a string
	 *
	 * @return string
	 */
	public function __toString() {

		$templateHandler = FieldTemplateHandler::getInstance();

		$output = $templateHandler->asString( 'select.twig', [
			'name'    => $this->isMultiSelect ? $this->name . '[]' : $this->name,
			'value'   => (array) $this->value,
			'atts'    => $this->getData( 'atts', [] ),
			'options' => $this->_normalizeOptions( $this->getData( 'options', [] ) ),
		] );

		$label = $this->getData( 'label' );
		if ( $label ) {
			$output = $this->_applyLabel( $output, $label, $this->getData( 'label_position' ) );
		}

		return $output;
	}

	/**
	 * Check if field is multi-select
	 *
	 * @return bool
	 */
	protected function _get_isMultiSelect() {
		return (bool) $this->getData( [ 'atts', 'multiple' ] );
	}

	/**
	 * Set field value
	 *
	 * @param mixed $value
	 */
	protected function _set_value( $value ) {
		$this->_value = $this->isMultiSelect ? (array) $value : $value;
	}

	/**
	 * Normalize options
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	protected function _normalizeOptions( array $options ) {

		foreach ( $options as $index => $data ) {

			$option = [
				'label' => '',
				'value' => '',
			];

			// If value is scalar, just normalize using that value
			if ( is_scalar( $data ) ) {
				$option = [
					'label' => $data,
					'value' => $data,
				];
			}

			// If value is an object, convert to an array
			if ( is_object( $data ) ) {
				$data = (array) $data;
			}

			// If value is an array, normalize alternative data structures
			if ( is_array( $data ) ) {

				$label = isset( $data['label'] ) ? $data['label'] : '';

				if ( isset( $data['options'] ) && is_array( $data['options'] ) ) {

					// Setup an option group
					$option = [
						'label'   => $label,
						'options' => $this->_normalizeOptions( $data['options'] ),
					];

				} else {

					$option = [
						'label' => $label,
						'value' => isset( $data['value'] ) ? $data['value'] : '',
					];

				}

			}

			$options[ $index ] = $option;

		}

		return $options;
	}

}