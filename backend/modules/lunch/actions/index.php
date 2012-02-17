<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the index-action (default), it will display the overview orders
 * @author ken.depelchin@netlash.com
 */

class BackendLunchIndex extends BackendBaseActionIndex
{

	/**
	 * DataGrids
	 *
	 * @var	SpoonDataGrid
	 */

	private $category;


	public function execute()
	{
		parent::execute();

		// set category id
		$this->categoryId = SpoonFilter::getGetValue('category', null, null, 'int');
		if($this->categoryId == 0) $this->categoryId = null;
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

		$this->loadDataGridAllPosts();
		$this->parse();
		$this->display();
	}
	/**
	 * Loads the datagrid with all the posts
	 */
	private function loadDataGridAllPosts()
	{
		// filter on category?
		if($this->categoryId != null)
		{
			// create datagrid
			$this->dgPosts = new BackendDataGridDB(BackendLunchModel::QRY_DATAGRID_BROWSE_FOR_LUNCH_MENU_ITEMS, array($this->categoryId, BL::getWorkingLanguage()));

		}

		else
		{
			// create datagrid
			$this->dgPosts = new BackendDataGridDB(BackendLunchModel::QRY_DATAGRID_BROWSE, array(BL::getWorkingLanguage()));
		}

		$this->dgPosts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_menu_item') . '&amp;id=[id]', BL::lbl('Edit'));
	}

	/**
	 * Parse all datagrids
	 */
	protected function parse()
	{
		parent::parse();

		// parse the datagrid for all blogposts
		$this->tpl->assign('dgPosts', ($this->dgPosts->getNumResults() != 0) ? $this->dgPosts->getContent() : false);

		// get categories
		$categories = BackendLunchModel::getCategories(true);
		//Spoon::dump($categories);

		// multiple categories?
		if(count($categories) > 1)
		{
			// create form
			$frm = new BackendForm('filter', null, 'get', false);

			// create element
			$frm->addDropdown('category', $categories, $this->categoryId);
			$frm->getField('category')->setDefaultElement('');

			//echo $frm->getField('category')->getValue();

			// parse the form
			$frm->parse($this->tpl);
		}

		// parse category
		if(!empty($this->category)) $this->tpl->assign('filterCategory', $this->category);
	}
}