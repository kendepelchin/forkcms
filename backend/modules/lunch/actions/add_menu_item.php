<?php

/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the add menu item action, it will display a form to add a menu item.
 * @author ken.depelchin@netlash.com
 */

class BackendLunchAddMenuItem extends BackendBaseActionAdd
{
	public function execute()
	{
		parent::execute();

		$this->categoryId = SpoonFilter::getGetValue('category', null, null, 'int');
		if ($this->categoryId == 0) $this->categoryId = null;
		else
		{
			// get category
			$this->category = BackendLunchModel::getCategory($this->categoryId);

			// reset
			if(empty($this->category))
			{
				// reset GET to trick Spoon
				$_GET['category'] = null;

				// reset
				$this->categoryId = null;
			}
		}

		$this->loadForm();
		$this->validateForm();
		$this->parse();
		$this->display();
	}

	protected function loadForm()
	{
		$this->frm = new BackendForm('addMenuItem');
		$this->frm->addText('name', null, 75, 'inputText title', 'inputTextError title', false);
		$this->frm->addText('price', null, 75, 'inputText title', 'inputTextError title', false);
		$categories = BackendLunchModel::getCategories();
		$this->frm->addDropdown('category', $categories, $this->categoryId);
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

			// price numeric
			$this->frm->getField('price')->isFloat(BL::err('PriceNotNumeric'));

			// get the selected category id
			$cat_id = (int) $this->frm->getField('category')->getValue();

			// category exists?
			if (!BackendLunchModel::existsCategory($cat_id))
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
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['price'] = $this->frm->getField('price')->getValue();
				$item['language'] = BL::getWorkingLanguage();
				$item['lunch_category_id'] = $this->frm->getField('category')->getValue();

				// add the menu item
				BackendLunchModel::addMenuItem($item);

				// redirect
				$this->redirect(BackendModel::createURLForAction('index') . '&report=added-item&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}