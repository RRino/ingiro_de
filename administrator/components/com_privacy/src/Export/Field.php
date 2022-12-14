<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_privacy
 *
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Privacy\Administrator\Export;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Data object representing a field within an item.
 *
 * @since  3.9.0
 */
class Field
{
    /**
     * The name of this field
     *
     * @var    string
     * @since  3.9.0
     */
    public $name;

    /**
     * The field's value
     *
     * @var    mixed
     * @since  3.9.0
     */
    public $value;
}
