<?php
/**
 * @version     1.5
 * @package     mod_jm_kick_cass
 * @copyright   Copyright (C) 2021. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @author      Maarten Blokdijk / www.kickstartcassiopeia.com
 */
//No Direct Access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

require JModuleHelper::getLayoutPath('mod_jm_kick_cass', $params->get('layout', 'default'));
?>
