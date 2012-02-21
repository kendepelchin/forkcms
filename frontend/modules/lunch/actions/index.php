<?php
class FrontendLunchIndex extends FrontendBaseBlock
{
	private $order = array();

	public function execute()
	{
		SpoonSession::start();
		//SpoonSession::destroy();

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

		parent::execute();
		$this->loadTemplate();
		$this->loadDataGrid();

		$this->parse();
	}

	private function loadDataGrid()
	{
		//Spoon::dump($this->order);
		if (sizeof($this->order) != 0) {
			$this->tpl->assign('items', $this->order);

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
		if ($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			//$cat_name = $this->frm->getField('name')->getValue();
			// validate fields
			/*if (BackendLunchModel::categoryAlreadyExists($cat_name)) {
				$this->frm->getField('name')->addError('This category already exists');
			} else {
				$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));
			}
			if($this->frm->isCorrect())
			{
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['language'] = BL::getWorkingLanguage();

				$item['id'] = BackendLunchModel::insertCategory($item);

				$this->redirect(BackendModel::createURLForAction('categories') . '&report=added-category&var=' . urlencode($item['name']) . '&highlight=row-' . $item['id']);
			}*/
		}
	}

	private function parse()
	{

	}
}