<?php

/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the index-action (orders), it will display the overview of orders
 * @author ken.depelchin@netlash.com
 */

class BackendLunchOrders extends BackendBaseActionIndex
{
	private $fridays;

	public function execute()
	{
		parent::execute();

		// GET ALL THE FRIDAYS!
		$this->getFridays(SpoonDate::getDate('Y'));

		$this->loadDataGrid();
		$this->parse();
		$this->display();
	}

	protected function parse()
	{
		parent::parse();
		$this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);
	}

	private function loadDataGrid()
	{
		$this->dataGrid = new BackendDataGridArray($this->fridays);

		// add column
		$this->dataGrid->addColumn('View', null, BL::lbl('View'), BackendModel::createURLForAction('order_detail') . '&amp;id=[fridayTimeStamp]', BL::lbl('View'));

		// set headers
		$this->dataGrid->setHeaderLabels(array('friday' => SpoonFilter::ucfirst(BL::lbl('LunchFridays'))));
		$this->dataGrid->setHeaderLabels(array('View' => SpoonFilter::ucfirst(BL::lbl('LunchViewDetails'))));

		// hide timestamp column
		$this->dataGrid->setColumnHidden('fridayTimeStamp');
	}

	private function getFridays($year)
	{
		// define an array for our fridays
		$this->fridays = array();

		// get start date
	    $startDate = new DateTime($year . '-01-01 Friday 11:00');

	  	// get current date
	  	$currentDate = new DateTime(SpoonDate::getDate('d-m-Y'));

	    $year++;

	    // get end date
	    $endDate = new DateTime($year . '-01-01');

	    $int = new DateInterval('P7D');
	    foreach(new DatePeriod($startDate, $int, $endDate) as $d) {
			$dateTimeToCheck= new DateTime($d->format('F j, Y'));

			if ($currentDate > $dateTimeToCheck)
			{
				continue;
			}

	        $this->fridays[] = array(
        		'fridayTimeStamp' => strtotime((string)$d->format('F j, Y h:00:00')),
        		'friday' => $d->format('jS F Y')
        	);
	    }
	}
}