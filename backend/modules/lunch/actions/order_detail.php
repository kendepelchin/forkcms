<?php

/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the detail-action (orders), it will display the detail of orders
 * @author ken.depelchin@netlash.com
 */

class BackendLunchOrderDetail extends BackendBaseActionIndex
{
	private $timestamp;

	public function execute()
	{
		$this->timestamp = $this->getParameter('id', 'int', null);
		if ($this->timestamp !== null && date('N', $this->timestamp) == 5 ? true : false)
		{
			parent::execute();
			$this->loadDataGrid();
			$this->parse();
			$this->display();
		} else {
			$this->redirect(BackendModel::createURLForAction('orders') . '&error=not-friday');
		}
	}

	protected function parse()
	{
		parent::parse();
		$this->tpl->assign('detailDate', date('jS F Y',$this->timestamp));
		$this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);

		// Calculate the total
		$total = round(BackendLunchModel::calculateTotal(date('Y-m-d H:00:00',$this->timestamp)),2);

		$this->tpl->assign('total',$total);
	}

	private function loadDataGrid()
	{
		// TODO: change query to use date
		$this->dataGrid = new BackendDataGridDB(BackendLunchModel::QRY_DATAGRID_BROWSE_ORDERS_FOR_DATE, array(SpoonDate::getDate('Y-m-d H:00:00', $this->timestamp),SpoonDate::getDate('Y-m-d H:00:00', $this->timestamp),BL::getWorkingLanguage()));

		$this->dataGrid->setColumnFunction(array(__CLASS__, 'roundSubtotal'), array('[subtotal]'), 'subtotal', true);
	}

	public static function roundSubtotal($subtotal)
	{
		$subtotal = (float)$subtotal;
		return '€ ' . round($subtotal,2);
	}
}