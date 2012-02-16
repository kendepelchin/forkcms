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

class BackendLunchDeleteCategory extends BackendBaseActionDelete
{
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendLunchModel::existsCategory($this->id))
		{
			// get data
			$this->record = (array) BackendLunchModel::getCategory($this->id);

			// allowed to delete the category?
			if(BackendLunchModel::deleteCategoryAllowed($this->id))
			{
				// call parent, this will probably add some general CSS/JS or other required files
				parent::execute();

				// delete item
				BackendLunchModel::deleteCategory($this->id);

				// trigger event
				//BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('id' => $this->id));

				// category was deleted, so redirect
				$this->redirect(BackendModel::createURLForAction('categories') . '&report=deleted-category&var=' . urlencode($this->record['name']));
			}

			// delete category not allowed
			else $this->redirect(BackendModel::createURLForAction('categories') . '&error=delete-category-not-allowed&var=' . urlencode($this->record['name']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}