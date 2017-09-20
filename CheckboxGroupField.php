<?php

namespace wpscholar\WordPress;

/**
 * Class CheckboxGroupField
 *
 * @package wpscholar\WordPress
 */
class CheckboxGroupField extends Field {

	/**
	 * Field constructor.
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function __construct( $name, array $args = [] ) {
		parent::__construct( $name, $args );

		$this->_sanitize = function ( array $data ) {
			return array_map( 'sanitize_text_field', $data );
		};
	}

	/**
	 * Return field markup as a string
	 *
	 * @return string
	 */
	public function __toString() {

		$templateHandler = FieldTemplateHandler::getInstance();

		$fields = [];
		$options = $this->_normalizeOptions( $this->getData( 'options', [] ) );

		foreach ( $options as $option ) {

			$isSelected = in_array( $option->value, (array) $this->value );

			$fields[] = new InputField( $this->name . '[]', [
				'atts'           => $isSelected ? [ 'checked' => 'checked' ] : [],
				'label'          => $option->label,
				'label_position' => 'after',
				'type'           => 'checkbox',
				'value'          => $option->value,
			] );

		}

		return $templateHandler->asString( 'fieldset.twig', [
			'atts'    => $this->getData( 'atts', [] ),
			'content' => implode( '', array_map( function ( $field ) {
				return "{$field}";
			}, $fields ) ),
			'legend'  => $this->getData( 'label' ),
		] );
	}

	/**
	 * Set field value
	 *
	 * @param mixed $value
	 */
	protected function _set_value( $value ) {
		$this->_value = (array) $value;
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
				$option['label'] = isset( $data['label'] ) ? $data['label'] : '';
				$option['value'] = isset( $data['value'] ) ? $data['value'] : '';
			}

			$options[ $index ] = (object) $option;

		}

		return $options;
	}

}