<?php
class BackendLunchDeleteMenuItem extends BackendBaseActionDelete{
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendLunchModel::existsMenuItem($this->id))
		{
			// get data
			$this->record = (array) BackendLunchModel::getItem($this->id);

			// call parent, this will probably add some general CSS/JS or other required files
				parent::execute();

				// delete item
				BackendLunchModel::deleteMenuItem($this->id);

				// category was deleted, so redirect
				$this->redirect(BackendModel::createURLForAction('index') . '&report=deleted-menu_item&var=' . urlencode($this->record['name']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('categories') . '&error=non-existing');
	}
}