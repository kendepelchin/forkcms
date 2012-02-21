<?php
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
		// assign categories
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