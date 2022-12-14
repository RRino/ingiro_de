<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

\defined('_JEXEC') or die('Restricted Direct Access!');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
HTMLHelper::_('behavior.multiselect');

// Just for the tests :(
HTMLHelper::_('jquery.framework');

$app       = Factory::getApplication();
$user      = $app->getIdentity();
$userId    = $user->get('id');
$component = $this->state->get('filter.component');
$section   = $this->state->get('filter.section');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$ordering  = ($listOrder === 'a.ordering');
$saveOrder = ($listOrder === 'a.ordering' && strtolower($listDirn) == 'asc');


if ($saveOrder && !empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_calevent&task=eventos.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}
?>

<form action="<?php echo Route::_('index.php?option=com_calevent&view=eventos', false); ?>" method="post"
    name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('selectorFieldName' => 'context'))); ?>
                <?php if (empty($this->items)) : ?>
                <div class="alert alert-info">
                    <span class="icon-info-circle" aria-hidden="true"></span><span
                        class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                    <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
                <?php else : ?>
                <table class="table" id="fieldList">
                    <caption class="visually-hidden">
                        <?php echo Text::_('COM_FIELDS_FIELDS_TABLE_CAPTION'); ?>,
                        <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                        <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                    </caption>
                    <thead>
                        <tr>
                            <td class="w-1 text-center">
                                <?php echo HTMLHelper::_('grid.checkall'); ?>
                            </td>
                            <th scope="col" class="w-1 text-center d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                            </th>
                            <th scope="col" class="w-1 text-center">
                                <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" class="w-10 d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" class="w-10 d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort',  'Data inizio', 'a.data_inizio', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" class="w-10 d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
                            </th>

                            <th scope="col" class="w-10 d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                            </th>
                            <?php if (Multilanguage::isEnabled()) : ?>
                            <th scope="col" class="w-10 d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
                            </th>
                            <?php endif; ?>
                            <th scope="col" class="w-5 d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>"
                        data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true" <?php endif; ?>>
                        <?php foreach ($this->items as $i => $item) : ?>
                        <?php $ordering   = ($listOrder === 'a.ordering'); ?>
                        <?php $canEdit    = $user->authorise('core.edit', $component . '.evento.' . $item->id); ?>
                        <?php $canCheckin = $user->authorise('core.admin', 'com_checkin') || $item->checked_out === $userId || is_null($item->checked_out); ?>
                        <?php $canEditOwn = $user->authorise('core.edit.own', $component . '.evento.' . $item->id) && $item->created_by === $userId; ?>
                        <?php $canChange  = $user->authorise('core.edit.state', $component . '.evento.' . $item->id) && $canCheckin; ?>

                        <tr class="row<?php echo $i % 2; ?>" data-draggable-group="1"
                            item-id="<?php echo $item->id; ?>">

                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
                            </td>

                            <td class="text-center d-none d-md-table-cell">
                                <?php $iconClass = ''; ?>
                                <?php if (!$canChange) : ?>
                                <?php $iconClass = ' inactive'; ?>
                                <?php elseif (!$saveOrder) : ?>
                                <?php $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED'); ?>
                                <?php endif; ?>
                                <span class="sortable-handler<?php echo $iconClass; ?>">
                                    <span class="icon-ellipsis-v" aria-hidden="true"></span>
                                </span>
                                <?php if ($canChange && $saveOrder) : ?>
                                <input type="text" class="hidden" name="order[]" size="5"
                                    value="<?php echo $item->ordering; ?>">
                                <?php endif; ?>
                            </td>
                           
							<td class="text-center">
                                <?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'eventos.', $canChange, 'cb'); ?>
                            </td>
                            
							<th scope="row">
                                <div class="break-word">
                                    <?php if ($item->checked_out) : ?>
                                    <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'eventos.', $canCheckin); ?>
                                    <?php endif; ?>
                                    <?php if ($canEdit || $canEditOwn) : ?>
                                    <a href="<?php echo Route::_('index.php?option=com_calevent&task=evento.edit&id=' . $item->id, false); ?>"
                                        title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
                                        <?php echo $this->escape($item->title); ?></a>
                                    <?php else : ?>
                                    <?php echo $this->escape($item->title); ?>
                                    <?php endif; ?>
                                </div>
                            </th>
                           
							<td class="small d-none d-md-table-cell">
                                <?php echo HTMLHelper::_('date', $item->data_inizio, 'd-m-Y'); ?>
                            </td>
                           
							<td class="small d-none d-md-table-cell">
                                <?php if ((int) $item->created_by !== 0) : ?>
                                <a
                                    href="<?php echo Route::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>">
                                    <?php echo $this->escape($item->author_name); ?>
                                </a>
                                <?php else : ?>
                                <?php echo Text::_('JNONE'); ?>
                                <?php endif; ?>
                            </td>

                            <td class="small d-none d-md-table-cell">
                                <?php echo $this->escape($item->access_level); ?>
                            </td>
                          
							<?php if (Multilanguage::isEnabled()) : ?>
                           
								<td class="small d-none d-md-table-cell">
                                <?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                            </td>
                            <?php endif; ?>
                          
							<td class="d-none d-md-table-cell">
                                <span><?php echo (int) $item->id; ?></span>
                            </td>
                        </tr>
                       
						<?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Load the pagination -->
                <?php echo $this->pagination->getListFooter(); ?>

                <?php endif; ?>

                <div class="jext-cli-footnote"
                    style="min-height: 80px;background: #ffffff;margin-top: 100px;display: flex;flex-direction: column;justify-content: center;align-items: center;border-radius: 10px;box-shadow: 1px 1px 2px 0px #2222225c;font-family: 'helvetica', sans-serif;font-size: 14px;color: #757575;">
                    <p style="margin: 0;">Powered by <a href="https://github.com/ahamed/jext-cli"
                            style="text-decoration: none;"><code>JEXT-CLI</code></a>, Developed by <a
                            href="https://ahamed.github.io" style="text-decoration: none;">Sajeeb Ahamed</a></p><a
                        href="https://ahamed.github.io" style="text-decoration: none;"><small>&copy; 2022, Sajeeb
                            Ahamed</small></a>
                </div>

                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>