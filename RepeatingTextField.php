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

		$output = $templateHandler->asString( 'repeating-text-field.twig', [
			'atts'   => $this->getData( 'atts', [] ),
			'legend' => $this->getData( 'label' ),
			'name'   => $this->name,
			'value'  => array_filter( $this->value ),
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