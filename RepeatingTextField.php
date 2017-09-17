<?php

namespace wpscholar\WordPress;

use wpscholar\Elements\ElementFactory;
use wpscholar\Elements\EmptyElement;
use wpscholar\Elements\EnclosingElement;

/**
 * Class RepeatingTextField
 *
 * @package wpscholar\WordPress
 */
class RepeatingTextField extends Field {

	/**
	 * Field element
	 *
	 * @var EnclosingElement
	 */
	public $el;

	/**
	 * Input type
	 *
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Set up
	 *
	 * @param array $args
	 */
	protected function _setUp( array $args ) {

		// Setup attributes
		$atts = isset( $args['atts'] ) ? $args['atts'] : [];

		// Set default value
		if ( isset( $args['value'] ) ) {
			$this->value = (array) $args['value'];
		}

		// Setup fieldset
		$this->el = ElementFactory::createElement( 'fieldset', array_map( 'esc_attr', $atts ) );

		// Setup label element
		$this->labelEl = null;

	}

	/**
	 * Get label HTML
	 *
	 * @return string
	 */
	public function getLabel() {
		return "{$this->label}";
	}

	/**
	 * Get field HTML
	 *
	 * @return string
	 */
	public function getField() {

		$this->el->removeAll();

		if ( $this->label ) {
			$legend = new EnclosingElement( 'legend' );
			$legend->content = esc_html( $this->label );
			$this->el->append( $legend );
		}

		$field_container = new EnclosingElement( 'div' );

		// Setup child fields
		$values = (array) $this->value;
		foreach ( $values as $value ) {
			$field = new EnclosingElement( 'div' );
			$field->content = [
				new EmptyElement( 'input', [
					'name'  => esc_attr( $this->name . '[]' ),
					'type'  => 'text',
					'value' => esc_attr( $value ),
				] ),
				'<span>X</span>',
			];
			$field_container->append( $field );
		}

		// Always add one empty field to the end
		$empty_field = new EnclosingElement( 'div' );
		$empty_field->content = [
			new EmptyElement( 'input', [
				'name'  => esc_attr( $this->name . '[]' ),
				'type'  => 'text',
				'value' => '',
			] ),
			'<span>X</span>',
		];
		$field_container->append( $empty_field );
		$this->el->append( $field_container );

		// Add a button for adding new fields
		$this->el->append( '<button type="button">+ Add</button>' );

		return "{$this->el}";
	}

}