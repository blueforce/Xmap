<?php
/**
 * @version	     $Id$
 * @copyright			Copyright (C) 2005 - 2009 Joomla! Vargas. All rights reserved.
 * @license	     GNU General Public License version 2 or later; see LICENSE.txt
 * @author	      Guillermo Vargas (guille@vargas.co.cr)
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// Create shortcut to parameters.
$params = $this->item->params;

if ($this->displayer->_isAdmin) {
	JHTML::_('behavior.mootools');
	$live_site = JURI::root();
	$ajaxurl = "$live_site/index.php?option=com_xmap&tmpl=component&task=editElement&action=toggleElement";

	$css = '.xmapexcl img{ border:0px; }'."\n";
	$css .= '.xmapexcloff { text-decoration:line-through; }';
	//$css .= "\n.".$this->item->classname .' li {float:left;}';

	$js = "
		window.addEvent('domready',function (){
			$$('.xmapexcl').each(function(el){
				el.onclick = function(){
					if (this && this.rel) {
						options = JSON.decode(this.rel);
						this.onComplete = checkExcludeResult
						var myAjax = new Request.JSON({url:'{$ajaxurl}',
						                 	onComplete: checkExcludeResult.bind(this)
						                 }).get({id:{$this->item->id},uid:options.uid,itemid:options.itemid});
					}
					return false;
				};

			});
		});
		checkExcludeResult = function (response) {
			//this.set('class','xmapexcl xmapexcloff');
			var imgs = this.getElementsByTagName('img');
			if (response.result == 'OK') {
				var state = response.state;
				if (state==0) {
					imgs[0].src='{$live_site}administrator/images/publish_x.png';
				} else {
					imgs[0].src='{$live_site}administrator/images/tick.png';
				}
			} else {
				alert('The element couldn\\'t be published or upublished!');
			}
		}";

	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration ($css);
	$doc->addScriptDeclaration ($js);
}
?>
<?php if ($params->get('show_page_title', 1) && $params->get('page_title') != $this->item->title) : ?>
	<h1>
		<?php echo $this->escape($params->get('page_title')); ?>
	</h1>
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
	<h2>
		<?php if ($params->get('link_titles') && !empty($this->item->rlink)) : ?>
		<a href="<?php echo $this->item->rlink; ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</h2>
<?php endif; ?>

<?php if ($params->get('access-edit') || $params->get('show_title') ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<ul>
	<?php if (!$this->print) : ?>
		<?php if ($params->get('show_print_icon')) : ?>
		<li>
			<?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?>
		</li>
		<?php endif; ?>

		<?php if ($params->get('show_email_icon')) : ?>
		<li>
			<?php echo JHtml::_('icon.email',  $this->item, $params); ?>
		</li>
		<?php endif; ?>
	<?php else : ?>
		<li>
			<?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
		</li>
	<?php endif; ?>
	</ul>
<?php endif; ?>

<?php if ($params->get('showintro', 1) )  : ?>
<?php echo $this->item->introtext; ?>
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<span class="article_separator">&nbsp;</span>