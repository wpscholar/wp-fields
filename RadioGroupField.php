<?php

namespace wpscholar\WordPress;

/**
 * Class RadioGroupField
 *
 * @package wpscholar\WordPress
 */
class RadioGroupField extends Field {

	/**
	 * Return field markup as a string
	 *
	 * @return string
	 */
	public function __toString() {

		$templateHandler = FieldTemplateHandler::getInstance();

		$fields  = [];
		$options = $this->_normalizeOptions( $this->getData( 'options', [] ) );

		foreach ( $options as $option ) {

			$isSelected = $this->value === $option->value;

			$fields[] = new InputField( $this->name, [
				'atts'           => $isSelected ? [ 'checked' => 'checked' ] : [],
				'label'          => $option->label,
				'label_position' => 'after',
				'type'           => 'radio',
				'value'          => $option->value,
			] );

		}

		$output = $templateHandler->asString( 'fieldset.twig', [
			'atts'    => $this->getData( 'atts', [] ),
			'content' => implode( '', array_map( function ( $field ) {
				return "{$field}";
			}, $fields ) ),
			'legend'  => $this->getData( 'label' ),
		] );

		return $this->_wrap( $output );
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