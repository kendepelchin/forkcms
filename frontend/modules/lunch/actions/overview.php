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

class FrontendLunchOverview extends FrontendBaseBlock
{
	public function execute()
	{
		parent::execute();

		$this->loadTemplate();
		$this->parse();
	}

	private function parse()
	{
		if (SpoonDate::getDate('N') == 5)
		{
			// this day is a friday, so show the orders till Saturday
			$this->tpl->assign('next', SpoonDate::getDate('F jS, Y'));

			$this->source = FrontendLunchModel::getOrdersForDate(SpoonDate::getDate('Y-m-d H:00:00'));
			$this->showContent();
		}
		else {
			// we need to get all orders before this date:
			$this->date = strtotime('next Friday 11:00:00');
			$this->tpl->assign('next', SpoonDate::getDate('F jS, Y', $this->date));

			$this->source = FrontendLunchModel::getOrdersForDate(SpoonDate::getDate('Y-m-d H:00:00', $this->date));
			$this->showContent();
		}
	}

	private function showContent() {
		$total = 0;
		//Spoon::dump($this->source);
		foreach ($this->source as &$item)
		{
			$item['subtotal'] = round($item['subtotal'],2);

			// calculate total
			$total += $item['subtotal'];
		}

		if (sizeof($this->source) != 0)
		{
			$this->tpl->assign('items', $this->source);
			$this->tpl->assign('total', $total);
		}
	}
}