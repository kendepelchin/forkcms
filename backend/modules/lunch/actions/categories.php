<?php

/*
* This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the index-action (default), it will display the overview of lunch categories
 * @author ken.depelchin@netlash.com
 */

class BackendLunchCategories extends BackendBaseActionIndex
{
	public function execute()
	{
		parent::execute();
		$this->loadDataGrid();
		$this->parse();
		$this->display();
	}

	protected function loadDataGrid()
	{
		$this->dataGrid = new BackendDataGridDB(BackendLunchModel::QRY_DATAGRID_BROWSE_FOR_CATEGORY, array(BL::getWorkingLanguage()));

		// set headers
		$this->dataGrid->setHeaderLabels(array('num_items' => SpoonFilter::ucfirst(BL::lbl('Amount'))));

		// sorting columns
		$this->dataGrid->setSortingColumns(array('name', 'num_items'), 'name');

		// convert the count into a readable and clickable one
		$this->dataGrid->setColumnFunction(array(__CLASS__, 'setClickableCount'), array('[num_items]', BackendModel::createURLForAction('index') . '&amp;category=[id]'), 'num_items', true);

		// disable paging
		$this->dataGrid->setPaging(false);

		// add attributes, so the inline editing has all the needed data
		$this->dataGrid->setColumnAttributes('name', array('data-id' => '{id:[id]}'));

		// check if this action is allowed
		if(BackendAuthentication::isAllowedAction('edit_category'))
		{
			// set column URLs
			$this->dataGrid->setColumnURL('name', BackendModel::createURLForAction('index') . '&amp;id=[id]');

			// add column
			$this->dataGrid->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_category') . '&amp;id=[id]', BL::lbl('Edit'));
		}
	}

	protected function parse()
	{
		parent::parse();

		$this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);
	}

	/**
	 * Convert the count in a human readable one.
	 *
	 * @param int $count The count.
	 * @param string $link The link for the count.
	 * @return string
	 */
	public static function setClickableCount($count, $link)
	{
		$count = (int) $count;
		$link = (string) $link;
		$return = '';

		if($count > 1) $return = '<a href="' . $link . '">' . $count . ' ' . BL::getLabel('LunchItems') . '</a>';
		elseif($count == 0) $return = 'No items available';
		elseif($count == 1) $return = '<a href="' . $link . '">' . $count . ' ' . BL::getLabel('LunchItem') . '</a>';

		return $return;
	}
}