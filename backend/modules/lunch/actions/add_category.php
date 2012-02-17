<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the add category action, it will display a form to add a category.
 * @author ken.depelchin@netlash.com
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
		$this->frm->addText('name', null,50,'inputText title', 'inputTextError title', false);

		// meta
		$this->meta = new BackendMeta($this->frm, null, 'title', true);
	}

	// action (plonk) => doAdd
	private function validateForm()
	{
		if ($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			$cat_name = $this->frm->getField('name')->getValue();
			// validate fields
			if (BackendLunchModel::categoryAlreadyExists($cat_name)) {
				$this->frm->getField('name')->addError('This category already exists');
			} else {
				$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));
			}
			if($this->frm->isCorrect())
			{
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['language'] = BL::getWorkingLanguage();

				$item['id'] = BackendLunchModel::insertCategory($item);

				$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}
		}
	}
}