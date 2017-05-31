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

		$atts = isset( $args['atts'] ) ? $args['atts'] : [];

		if ( isset( $args['type'] ) ) {
			$this->type = $atts['type'] = $args['type'];
		}

		$this->el = ElementFactory::createElement( 'input', $atts );

		/*// Setup input
		$this->el = new EmptyElement( 'input' );
		$this->el->atts->set( 'id', esc_attr( $this->name ) );
		$this->el->atts->set( 'name', esc_attr( $this->name ) );*/

		// Setup label
		$this->labelEl = new EnclosingElement( 'label' );
		$this->labelEl->atts->set( 'for', esc_attr( $this->name ) );
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
		$this->el->atts->set( 'type', esc_attr( $this->type ) );
		$this->el->atts->set( 'value', esc_attr( $this->_value ) );

		return "{$this->el}";
	}

}