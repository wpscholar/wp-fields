<?php

namespace wpscholar\Fields\Fields;

use wpscholar\Fields\FieldTemplateHandler;

/**
 * Class TextareaField
 *
 * @package wpscholar\Fields\Fields
 */
class TextareaField extends Field {

	/**
	 * Sanitization callback
	 *
	 * @var callable
	 */
	protected $_sanitize = 'sanitize_textarea_field';

	/**
	 * Return field markup as a string
	 *
	 * @return string
	 */
	public function __toString() {

		$templateHandler = FieldTemplateHandler::getInstance();

		$output = $templateHandler->asString( 'textarea.twig', [
			'name'  => $this->name,
			'value' => $this->value,
			'atts'  => $this->getData( 'atts', [] ),
		] );

		$label = $this->getData( 'label' );
		if ( $label ) {
			$output = $this->_applyLabel( $output, $label, $this->getData( 'label_position' ) );
		}

		return $this->_wrap( $output );
	}

}