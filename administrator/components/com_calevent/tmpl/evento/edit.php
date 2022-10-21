<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Calevent\Administrator\Helper\CaleventHelper;


/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$app = Factory::getApplication();
$input = $app->getInput();

$this->useCoreUI = true;

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>
<style>
.ar {
    margin-left: 200px;
}
</style>

<form
    action="<?php echo Route::_('index.php?option=com_calevent&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="evento-form"
    aria-label="<?php echo Text::_('COM_CALEVENT_EVENTO_' . ( (int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>"
    class="form-validate">

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div>
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_CALEVENT_GLOBAL_TAB_BASIC')); ?>
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
      
                        <?php echo $this->form->renderField('id_link'); ?>
                        <?php echo $this->form->renderField('data_inizio'); ?>
                        <?php echo $this->form->renderField('data_fine'); ?>
                        <?php echo $this->form->renderField('sigla'); ?>
                        <?php echo $this->form->renderField('colore'); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <?php $this->set('fields',
								array(
									'published',
									'created_by',
									'created',
									'access',
									'language'
								)
						); ?>
                        <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                        <?php $this->set('fields', null); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>

    <div class="jext-cli-footnote"
        style="min-height: 80px;background: #ffffff;margin-top: 100px;display: flex;flex-direction: column;justify-content: center;align-items: center;border-radius: 10px;box-shadow: 1px 1px 2px 0px #2222225c;font-family: 'helvetica', sans-serif;font-size: 14px;color: #757575;">
        <p style="margin: 0;">Powered by <a href="https://github.com/ahamed/jext-cli"
                style="text-decoration: none;"><code>JEXT-CLI</code></a>, Developed by <a
                href="https://ahamed.github.io" style="text-decoration: none;">Sajeeb Ahamed</a></p><a
            href="https://ahamed.github.io" style="text-decoration: none;"><small>&copy; 2022, Sajeeb Ahamed</small></a>
    </div>

    <input type="hidden" name="task" value="">
    <input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>