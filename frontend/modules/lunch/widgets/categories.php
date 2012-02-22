<?php
/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the categories widget
 * @author ken.depelchin@netlash.com
 */

class FrontendLunchWidgetCategories extends FrontendBaseWidget
{
	public function execute()
	{
		parent::execute();
		$this->loadTemplate();
		$this->parse();
	}

	private function parse()
	{
		$categories = FrontendLunchModel::getAllCategories();

		if (!empty($categories))
		{
			$link = FrontendNavigation::getURLForBlock('lunch');

			foreach($categories as &$row)
			{
				foreach($row['items'] as &$item)
				{
					$item['url'] = $link . '?id=' . $item['id'];
				}
			}
		}

		$this->tpl->assign('widgetLunchCategories', $categories);
	}
}