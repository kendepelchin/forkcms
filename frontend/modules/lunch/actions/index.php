<?php
/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the index-action (default), it will display the overview of all items
 * @author ken.depelchin@netlash.com
 */

class FrontendLunchIndex extends FrontendBaseBlock
{
	private $order = array();

	public function execute()
	{
		if (SpoonDate::getDate('N') != 5) {
			$this->getSession();
		}

		elseif(SpoonDate::getDate('N') == 5 && SpoonDate::getDate('H') <= 11)
		{
			$this->getSession();
		}

		else
		{
			SpoonSession::destroy();

		}

		parent::execute();
		$this->loadTemplate();
		$this->loadData();
		$this->parse();
	}

	private function getSession()
	{
		// let users order
		SpoonSession::start();

		$this->tpl->assign('possible',true);

		// get the session or start a new one
		if (!SpoonSession::exists('frieten'))
		{
			SpoonSession::set('frieten',$this->order);
		}
		else $this->order = SpoonSession::get('frieten');

		// get the get value
		$this->orderId = SpoonFilter::getGetValue('id', null, null, 'int');
		$count = 1;

		// the product we want to add:
		if ($this->orderId != 0)
		{
			$item = FrontendLunchModel::getMenuItem($this->orderId);
			$item['count'] = $count;
			$item['note'] = '';
			$item['total'] = 0;

			// add the count of an existing menu item
			if (array_key_exists($item['name'],$this->order)) {
				$count = $this->order[$item['name']]['count'] + 1;

				$this->order[$item['name']]['count'] = $count;
				$this->order[$item['name']]['total'] = $this->order[$item['name']]['count'] * $this->order[$item['name']]['price'];
			} else {
				// no product found, add it
				$this->order[$item['name']] = $item;
				$this->order[$item['name']]['total'] = $this->order[$item['name']]['count'] * $this->order[$item['name']]['price'];
			}

			// add the array to the session
			SpoonSession::set('frieten', $this->order);
		}
	}

	private function loadData()
	{
		//Spoon::dump($this->order);
		if (sizeof($this->order) != 0) {
			$this->tpl->assign('items', $this->order);

			$url = FrontendNavigation::getURLForBlock('lunch', 'edit') . '/';
			$this->tpl->assign('url',$url);

			$total = 0;
			foreach($this->order as $k) {
				$total += $k['count'] * $k['price'];
			}

			$this->tpl->assign('total',$total);

			$this->loadForm();
			$this->validateForm();
		}
	}

	private function loadForm()
	{
		// create form
		$this->frm = new FrontendForm('orderForm');
		$this->frm->addText('name',null,100,'inputText title','inputTextError title',false);
	}

	private function validateForm() {
		// @TODO: put name in session and remember it
		if ($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			$name = $this->frm->getField('name')->getValue();

			$this->frm->getField('name')->isFilled(FL::err('AuthorIsRequired'));

			if ($this->frm->isCorrect())
			{
				FrontendLunchModel::addOrder($name, $this->order);

				//order is added, delete the sessionkey
				SpoonSession::delete('frieten');

				$this->redirect(FrontendNavigation::getURLForBlock('lunch','overview'));
			}
		}
	}

	private function parse()
	{
		$this->date = SpoonDate::getDate('F jS, Y H:i:s',strtotime('next Friday 11:00:00'));
		//echo SpoonDate::getDate('F jS, Y H:i:s');
		//echo $this->date;
		if (sizeof($this->order) != 0)
		{
			if (SpoonDate::getDate('F jS, Y H:i:s') < $this->date)
			{
				//parse form
				$this->frm->parse($this->tpl);
			}
			else {
				// show day is passed
				// remove the add buttons
			}
		}
	}
}