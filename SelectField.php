<?php

namespace wpscholar\WordPress;

use wpscholar\Elements\ElementFactory;
use wpscholar\Elements\EnclosingElement;

/**
 * Class SelectField
 *
 * @package wpscholar\WordPress
 *
 * @property array $options
 */
class SelectField extends Field {

	/**
	 * Select field element
	 *
	 * @var EnclosingElement
	 */
	public $el;

	/**
	 * Select field options
	 *
	 * @var array
	 */
	protected $options = [];

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

		// Setup select element
		$this->el = ElementFactory::createElement( 'select', array_map( 'esc_attr', $atts ) );

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

		// Remove all children
		$this->el->removeAll();

		// Set options
		foreach ( $this->options as $option ) {
			if ( isset( $option['options'] ) ) {
				$optgroup = new EnclosingElement( 'optgroup', [ 'label' => esc_attr( $option['label'] ) ] );
				foreach ( $option['options'] as $childOption ) {
					$optgroup->append( $this->_getOptionNode( $childOption['label'], $childOption['atts'] ) );
				}
				$this->el->append( $optgroup );
			} else {
				$this->el->append( $this->_getOptionNode( $option['label'], $option['atts'] ) );
			}
		}

		// Setup name with proper support for multiple submissions
		$name = $this->_name;
		if ( $this->el->atts->has( 'multiple' ) ) {
			$name .= '[]';
		}

		// Output the name and value properties
		$this->el->atts->set( 'name', esc_attr( $name ) );
		$this->el->atts->set( 'value', esc_attr( $this->_value ) );

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
	 * Sets up and returns a select option node.
	 *
	 * @param string $label
	 * @param array $atts
	 *
	 * @return EnclosingElement
	 */
	protected function _getOptionNode( $label, array $atts = [] ) {
		unset( $atts['selected'] );
		$value = isset( $atts['value'] ) ? $atts['value'] : $label;
		if ( $this->_isSelected( $value ) ) {
			$atts['selected'] = 'selected';
		}
		$option = new EnclosingElement( 'option', array_map( 'esc_attr', $atts ) );
		$option->content = esc_html( $label );

		return $option;
	}

	/**
	 * Checks if an option is selected.
	 *
	 * @param string $value
	 *
	 * @return bool
	 */
	protected function _isSelected( $value ) {
		$current = $this->value;
		if ( is_array( $current ) ) {
			$is_selected = in_array( $value, $current );
		} else {
			$is_selected = $current === $value;
		}

		return $is_selected;
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
			'atts'  => [],
		];

		// If value is a string, just normalize using that value
		if ( is_string( $data ) ) {
			$option = [
				'label' => $data,
				'atts'  => [],
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

				$childOptions = [];
				foreach ( $data['options'] as $childOption ) {
					$childOptions[] = $this->_normalizeOptionData( $childOption );
				}

				// Setup an option group
				$option = [
					'label'   => $label,
					'options' => $childOptions,
				];

			} else {

				$atts = isset( $data['atts'] ) && is_array( $data['atts'] ) ? $data['atts'] : [];

				// Setup a regular option
				if ( isset( $data['value'] ) ) {
					$atts['value'] = $data['value'];
				}

				$option = [
					'label' => $label,
					'atts'  => $atts,
				];

			}

		}

		return $option;
	}

}