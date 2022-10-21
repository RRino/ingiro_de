<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_add_rtabs_giri
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

 use Joomla\CMS\Factory;
 use Joomla\CMS\Filesystem\Path;
 use Joomla\CMS\Form\Form;
 use Joomla\CMS\HTML\HTMLHelper;
 use Joomla\CMS\Language\Text;
 use Joomla\CMS\Filesystem\File;
 use Joomla\CMS\Language\LanguageHelper;
 use Joomla\CMS\Helper\ModuleHelper;
 use Rtabs_giriNamespace\Module\Rtabs_giri\Site\Helper\Rtabs_giriHelper;




// load the default Tmpl
require JModuleHelper::getLayoutPath('mod_rtabs_giri', $params->get('layout', 'default'));
?>


