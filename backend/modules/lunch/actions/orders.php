<?php
class BackendLunchOrders extends BackendBaseActionIndex
{
	private $fridays = array();

	public function execute()
	{
		// overzicht van vrijdagen
		parent::execute();

		// GET ALL THE FRIDAYS!
		$this->getFridays(SpoonDate::getDate('Y'));


		$this->loadDataGrid();
		$this->display();
	}

	private function loadDataGrid()
	{
		$this->dataGrid = new BackendDataGridArray($this->fridays);

	}

	private function getFridays($year)
	{
		// define an array for our fridays
		$this->fridays = array();

		// Where should we start?
	    $startDate = new DateTime($year . '-01-01 Friday');

	  	// What is the current date?
	  	$currentDate = new DateTime(SpoonDate::getDate('d-m-Y'));

		// What is the end date?
	    $year++;

	    // Where should we stop?
	    $endDate = new DateTime($year . '-01-01');

	    $int = new DateInterval('P7D');
	    foreach(new DatePeriod($startDate, $int, $endDate) as $d) {
			$dateTimeToCheck= new DateTime($d->format('F j, Y'));

			if ($currentDate > $dateTimeToCheck)
			{
				continue;
			}
	        $this->fridays[] = $d->format('F j, Y');
	    }

    	Spoon::dump($this->fridays);
	}
}