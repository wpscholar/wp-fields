<?php

namespace wpscholar\WordPress;

/**
 * Class RepeatingTextField
 *
 * @package wpscholar\WordPress
 */
class RepeatingTextField extends Field {

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

		foreach ( $this->value as $value ) {
			$fields[] = new InputField( $this->name . '[]', [ 'value' => $value ] );
		}

		// Always add one extra empty field
		$fields[] = new InputField( $this->name . '[]' );

		$output = $templateHandler->asString( 'fieldset.twig', [
			'atts'    => $this->getData( 'atts', [] ),
			'content' => implode( '', array_map( function ( $field ) use ( $templateHandler ) {
				return $templateHandler->asString( 'repeating-text-field.twig', [ 'field' => $field ] );
			}, $fields ) ),
			'legend'  => $this->getData( 'label' ),
		] );

		return $this->_wrap( $output );
	}

	/**
	 * Set field value
	 *
	 * @param mixed $value
	 */
	protected function _set_value( $value ) {
		$this->_value = (array) $value;
	}

}