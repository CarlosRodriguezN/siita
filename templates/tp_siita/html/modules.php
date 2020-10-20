<?php
/**
 * @package		Joomla.Site
 * @subpackage	Template Master
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.


defined('_JEXEC') or die('Restricted access');

function modChrome_lhtml($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletabletop<?php echo $params->get('moduleclass_sfx'); ?>">
				<?php if ($module->showtitle != 0) : ?>
				<h3><?php echo $module->title; ?></h3>
				<?php endif; ?>
                <div class="content-module">
				<?php echo $module->content; ?>
                </div>
        </div>
	<?php endif;
}

function modChrome_yhtml($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo $params->get('moduleclass_sfx'); ?>">
				<?php if ($module->showtitle != 0) : ?>
				<h3><?php echo $module->title; ?></h3>
				<?php endif; ?>
                <div class="content-module">
				<?php echo $module->content; ?>
                </div>
        </div>
	<?php endif;
}

function modChrome_chtml($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletablecampaign<?php echo $params->get('moduleclass_sfx'); ?>">
				<?php if ($module->showtitle != 0) : ?>
				<h3><?php echo $module->title; ?></h3>
				<?php endif; ?>
                <div class="content-module">
				<?php echo $module->content; ?>
                </div>
        </div>
	<?php endif;
}

function modChrome_mhtml($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="modulebottom<?php echo $params->get('moduleclass_sfx'); ?>">
				<?php if ($module->showtitle != 0) : ?>
				<h3><?php echo $module->title; ?></h3>
				<?php endif; ?>
                <div class="content-bottom">
				<?php echo $module->content; ?>
                </div>
        </div>
	<?php endif;
}

?>