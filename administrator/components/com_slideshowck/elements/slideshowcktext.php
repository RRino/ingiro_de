<?php

/**
 * @copyright	Copyright (C) 2011-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('JPATH_PLATFORM') or die;

class JFormFieldSlideshowcktext extends JFormFieldText {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'slideshowcktext';

	protected function getInput() {
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];

		$html = $icon ? '<div class="slideshowck-field-icon" ' . ($suffix ? 'data-has-suffix="1"' : '') . '><img src="' . SLIDESHOWCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div class="slideshowck-field-icon"></div>';

		$html .= parent::getInput();
		if ($suffix)
			$html .= '<span class="slideshowck-field-suffix">' . $suffix . '</span>';
		return $html;
	}

}
