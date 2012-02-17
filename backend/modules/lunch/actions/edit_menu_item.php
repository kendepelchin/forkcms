<?php

/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the edit menu item action, it will display a form to edit an existing menu item.
 * @author ken.depelchin@netlash.com
 */

class BackendLunchEditMenuItem extends BackendBaseActionEdit
{
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists
		if($this->id !== null && BackendLunchModel::existsMenuItem($this->id))
		{
			parent::execute();
			$this->getData();
			$this->loadForm();
			$this->validateForm();
			$this->parse();
			$this->display();
		}

		// no item found, throw an exception, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}

	private function getData()
	{
		$this->record = BackendLunchModel::getItem($this->id);
	}

	protected function parse()
	{
		parent::parse();

		$this->tpl->assign('item', $this->record);
	}

	protected function loadForm()
	{
		$this->frm = new BackendForm('editMenuItem');
		$this->frm->addText('name', $this->record['name'], 75, 'inputText title', 'inputTextError title', false);
		$this->frm->addText('price', $this->record['price'], 75, 'inputText title', 'inputTextError title', false);
		$categories = BackendLunchModel::getCategories();
		$this->frm->addDropdown('category', $categories, $this->record['category']);
		$this->frm->getField('category')->setDefaultElement('');

		// meta
		$this->meta = new BackendMeta($this->frm, null, 'title', true);
	}

	private function validateForm()
	{
		if ($this->frm->isSubmitted())
		{
			// name not empty?
			$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));

			// price not empty?
			$this->frm->getField('price')->isFilled(BL::err('FieldIsRequired'));

			// price float
			$this->frm->getField('price')->isFloat(BL::err('PriceNotNumeric'));

			// get the selected category id
			$catId = (int) $this->frm->getField('category')->getValue();

			// category exists?
			if (!BackendLunchModel::existsCategory($catId))
			{
				$this->frm->getField('category')->addError('This category does not exist');
			} else
			{
				$this->frm->getField('category')->isFilled(BL::err('FieldIsRequired'));
			}

			// all ok
			if ($this->frm->isCorrect())
			{
				// build item
				$item['id'] = $this->id;
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['price'] = $this->frm->getField('price')->getValue();
				$item['language'] = BL::getWorkingLanguage();
				$item['lunch_category_id'] = $this->frm->getField('category')->getValue();
				//Spoon::dump($item);
				// add the menu item
				BackendLunchModel::updateMenuItem($item);

				// redirect
				$this->redirect(BackendModel::createURLForAction('index') . '&report=edited-menu-item&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}