<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_calevent
 *
 * @copyright   (C) 2022, rino
 * @license     MIT
 */

defined('_JEXEC') or die('Restricted Direct Access!');

$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_calevent.styles');

?>

<div class="com-calevent view-evento view-singular-details <?php echo $this->pageclass_sfx; ?>">
	<h2 class="calevent-view-singular-title"><?php echo $this->item->title; ?></h2>
	<div class="jext-cli-footnote" style="min-height: 80px;background: #ffffff;margin-top: 100px;display: flex;flex-direction: column;justify-content: center;align-items: center;border-radius: 10px;box-shadow: 1px 1px 2px 0px #2222225c;font-family: 'helvetica', sans-serif;font-size: 14px;color: #757575;"><p style="margin: 0;">Powered by <a href="https://github.com/ahamed/jext-cli" style="text-decoration: none;"><code>JEXT-CLI</code></a>, Developed by <a href="https://ahamed.github.io" style="text-decoration: none;">Sajeeb Ahamed</a></p><a href="https://ahamed.github.io" style="text-decoration: none;"><small>&copy; 2022, Sajeeb Ahamed</small></a></div>
</div>