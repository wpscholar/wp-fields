<?php

namespace wpscholar\WordPress;

/**
 * Class InputField
 *
 * @package wpscholar\WordPress
 */
class InputField extends Field {

	/**
	 * Return field markup as a string
	 *
	 * @return string
	 */
	public function __toString() {

		$templateHandler = FieldTemplateHandler::getInstance();

		$output = $templateHandler->asString( 'input.twig', [
			'type'  => $this->getData( 'type', $this->getData( [ 'atts', 'type' ], 'text' ) ),
			'name'  => $this->name,
			'value' => $this->value,
			'atts'  => $this->getData( 'atts', [] ),
		] );

		$label = $this->getData( 'label' );
		if ( $label ) {
			$output = $this->_applyLabel( $output, $label, $this->getData( 'label_position' ) );
		}

		return $output;
	}

}