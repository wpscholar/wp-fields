<?php

namespace wpscholar\WordPress;

use wpscholar\Elements\ElementFactory;
use wpscholar\Elements\EnclosingElement;

/**
 * Class TextareaField
 *
 * @package wpscholar\WordPress
 */
class TextareaField extends Field {

	/**
	 * Field element
	 *
	 * @var EnclosingElement
	 */
	public $el;

	/**
	 * Sanitization callback
	 *
	 * @var callable
	 */
	public $sanitize = 'wp_kses_post';

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
			$this->value = $args['value'];
		}

		// Setup textarea element
		$this->el = ElementFactory::createElement(
			'textarea',
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

		// Output the name and value
		$this->el->atts->set( 'name', esc_attr( $this->_name ) );
		$this->el->content = esc_textarea( stripslashes( $this->value ) );

		return "{$this->el}";
	}

}