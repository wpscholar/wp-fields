<?php

namespace wpscholar\WordPress;

use wpscholar\Elements\ElementFactory;
use wpscholar\Elements\EmptyElement;
use wpscholar\Elements\EnclosingElement;

/**
 * Class InputField
 *
 * @package wpscholar\WordPress
 */
class InputField extends Field {

	/**
	 * Field element
	 *
	 * @var EmptyElement
	 */
	public $el;

	/**
	 * Input type
	 *
	 * @var string
	 */
	public $type = 'text';

	/**
	 * Set up
	 *
	 * @param array $args
	 */
	protected function _setUp( array $args ) {

		// Setup attributes
		$atts = isset( $args['atts'] ) ? $args['atts'] : [];

		if ( isset( $args['type'] ) ) {
			$this->type = $atts['type'] = $args['type'];
		}

		// Setup input element
		$this->el = ElementFactory::createElement(
			'input',
			array_map( 'esc_attr', $atts )
		);

		// Setup label element
		$this->labelEl = new EnclosingElement( 'label' );

		// Ensure HTML id is set on input and the for attribute on label matches
		$id = isset( $atts['id'] ) ? $atts['id'] : $this->_name;
		$this->el->atts->set( 'id', esc_attr( $id ) );
		$this->labelEl->atts->set( 'for', esc_attr( $id ) );
	}

	/**
	 * Get label HTML
	 *
	 * @return string
	 */
	public function getLabel() {

		// If there is no label text, don't display a label
		if ( empty( $this->label ) ) {
			return '';
		}

		$this->labelEl->content = esc_html( $this->label );

		return "{$this->labelEl}";
	}

	/**
	 * Get field HTML
	 *
	 * @return string
	 */
	public function getField() {

		// Output the name and value properties
		$this->el->atts->set( 'name', esc_attr( $this->_name ) );
		$this->el->atts->set( 'value', esc_attr( $this->_value ) );

		return "{$this->el}";
	}

}