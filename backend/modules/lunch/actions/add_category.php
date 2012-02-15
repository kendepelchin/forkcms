<?php

/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the add category action, it will display a form to add a category.
 */

class BackendLunchAddCategory extends BackendBaseActionAdd
{
	public function execute()
	{
		parent::execute();
		$this->loadForm();
		$this->validateForm();
		$this->parse();
		$this->display();
	}

	protected function loadForm()
	{
		$this->frm = new BackendForm('addCategory');
		$this->frm->addText('name', null,50,'inputText title', 'inputTextError title',false);
	}

	// action (plonk) => doAdd
	private function validateForm()
	{
		if ($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));

			if($this->frm->isCorrect())
			{
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['language'] = BL::getWorkingLanguage();

				//echo BackendLunchModel::categoryAlreadyExists($item['name']);
				//echo BackendLunchModel::categoryAlreadyExists($item['name']) == 1 ? 'true' : 'false';
				//echo 'zit er al in ';
				// check if category already exists=
				/*if (BackendLunchModel::categoryAlreadyExists($item['name'])) {
					$this->frm->getField('name')->isSubmitted(BL::err('CategoryAlreadyExists'));
				}*/
				// insert the item
				/*else*/ $item['id'] = BackendLunchModel::insertCategory($item);

				//CategoryAlreadyExists
				// redirect
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);

			}
		}
	}
}