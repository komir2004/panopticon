<?php
/**
 * @package   panopticon
 * @copyright Copyright (c)2023-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License, version 3 or later
 */

namespace Akeeba\Panopticon\View\Users;

defined('AKEEBA') || die;

use Akeeba\Panopticon\View\Trait\ShowOnTrait;
use Awf\Mvc\DataView\Html as BaseHtmlView;
use Awf\Text\Text;
use Awf\Utils\Template;

class Html extends BaseHtmlView
{
	use ShowOnTrait;

	public function onBeforeBrowse(): bool
	{
		$document = $this->container->application->getDocument();
		$toolbar  = $document->getToolbar();
		$buttons  = [
			[
				'title'   => Text::_('PANOPTICON_BTN_ADD'),
				'class'   => 'btn btn-success',
				'onClick' => 'akeeba.System.submitForm(\'add\')',
				'icon'    => 'fa fa-plus',
			],
			[
				'title'   => Text::_('PANOPTICON_BTN_EDIT'),
				'class'   => 'btn btn-secondary border-light',
				'onClick' => 'akeeba.System.submitForm(\'edit\')',
				'icon'    => 'fa fa-pen-to-square',
			],
			[
				'title'   => Text::_('PANOPTICON_BTN_DELETE'),
				'class'   => 'btn btn-danger',
				'onClick' => 'akeeba.System.submitForm(\'remove\')',
				'icon'    => 'fa fa-trash-can',
			],
		];

		array_walk($buttons, function (array $button)
		{
			$this->container->application->getDocument()->getToolbar()->addButtonFromDefinition($button);
		});

		$toolbar->setTitle(Text::_('PANOPTICON_USERS_TITLE'));

		return parent::onBeforeBrowse();
	}

	protected function onBeforeAdd()
	{
		Template::addJs('media://js/showon.js', $this->getContainer()->application, async: true);

		$document = $this->container->application->getDocument();
		$toolbar  = $document->getToolbar();
		$buttons  = [
			[
				'title'   => Text::_('PANOPTICON_BTN_SAVE'),
				'class'   => 'btn btn-primary',
				'onClick' => 'akeeba.System.submitForm(\'save\');',
				'icon'    => 'fa fa-save',
			],
			[
				'title'   => Text::_('PANOPTICON_BTN_APPLY'),
				'class'   => 'btn btn-success',
				'onClick' => 'akeeba.System.submitForm(\'apply\');',
				'icon'    => 'fa fa-check',
			],
			[
				'title'   => Text::_('PANOPTICON_BTN_CANCEL'),
				'class'   => 'btn btn-danger',
				'onClick' => 'akeeba.System.submitForm(\'cancel\');',
				'icon'    => 'fa fa-cancel',
			],
		];

		array_walk($buttons, function (array $button)
		{
			$this->container->application->getDocument()->getToolbar()->addButtonFromDefinition($button);
		});

		$toolbar->setTitle(Text::_('PANOPTICON_USERS_TITLE_NEW'));

		return parent::onBeforeAdd();
	}

	protected function onBeforeEdit()
	{
		Template::addJs('media://js/showon.js', $this->getContainer()->application, async: true);

		$document = $this->container->application->getDocument();
		$toolbar  = $document->getToolbar();
		$buttons  = [
			[
				'title'   => Text::_('PANOPTICON_BTN_SAVE'),
				'class'   => 'btn btn-primary',
				'onClick' => 'akeeba.System.submitForm(\'save\');',
				'icon'    => 'fa fa-save',
			],
			[
				'title'   => Text::_('PANOPTICON_BTN_APPLY'),
				'class'   => 'btn btn-success',
				'onClick' => 'akeeba.System.submitForm(\'apply\');',
				'icon'    => 'fa fa-check',
			],
			[
				'title'   => Text::_('PANOPTICON_BTN_CANCEL'),
				'class'   => 'btn btn-danger',
				'onClick' => 'akeeba.System.submitForm(\'cancel\');',
				'icon'    => 'fa fa-cancel',
			],
		];

		array_walk($buttons, function (array $button)
		{
			$this->container->application->getDocument()->getToolbar()->addButtonFromDefinition($button);
		});

		$toolbar->setTitle(Text::_('PANOPTICON_USERS_TITLE_EDIT'));

		return parent::onBeforeEdit();
	}

}