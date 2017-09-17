<?php

namespace wpscholar\WordPress;

use wpscholar\Elements\ElementFactory;
use wpscholar\Elements\EmptyElement;
use wpscholar\Elements\EnclosingElement;
use wpscholar\Elements\TextNode;

/**
 * Class RadioGroupField
 *
 * @package wpscholar\WordPress
 *
 * @property array $options
 */
class RadioGroupField extends Field {

	/**
	 * Field element
	 *
	 * @var EnclosingElement
	 */
	public $el;

	/**
	 * Field options
	 *
	 * @var array
	 */
	protected $options = [];

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
			$this->value = $args['value'];
		}

		// Setup options
		$options = isset( $args['options'] ) && is_array( $args['options'] ) ? $args['options'] : [];
		$this->_set_options( $options );

		// Setup child fields
		foreach ( $this->options as $option ) {
			if ( $option['label'] && $option['value'] ) {

				$label = new EnclosingElement( 'label' );

				$input = new EmptyElement( 'input', [
					'name'  => $this->name,
					'type'  => 'radio',
					'value' => $option['value']
				] );

				$label->content = [
					$input,
					esc_html( " {$option['label']}" )
				];

				$this->fields[] = $label;
			}
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
		return $this->label;
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

		foreach ( $this->fields as $field ) {
			/**
			 * @var EnclosingElement $input
			 */
			$input = $field->children[0];
			$value = $input->atts->get( 'name' );
			$input->atts->remove( 'checked' );
			if ( $value === $this->value ) {
				$input->atts->set( 'checked', 'checked' );
			}
			$this->el->append( new TextNode( "{$field}" ) );
		}

		return "{$this->el}";
	}

	/**
	 * Getter for options
	 *
	 * @return array
	 */
	protected function _get_options() {
		return $this->options;
	}

	/**
	 * Setter for options
	 *
	 * @param array $options
	 */
	protected function _set_options( array $options ) {
		$this->options = [];
		foreach ( $options as $option ) {
			$this->options[] = $this->_normalizeOptionData( $option );
		}
	}

	/**
	 * Normalize option data.
	 *
	 * @param string|object|array $data
	 *
	 * @return array
	 */
	protected function _normalizeOptionData( $data ) {

		$option = [
			'label' => '',
			'value' => '',
		];

		// If value is an object, convert to an array
		if ( is_object( $data ) ) {
			$data = (array) $data;
		}

		// If value is an array, normalize alternative data structures
		if ( is_array( $data ) ) {
			$option['label'] = isset( $data['label'] ) ? $data['label'] : '';
			$option['value'] = isset( $data['value'] ) ? $data['value'] : '';
		}

		return $option;
	}

}