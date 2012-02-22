<?php
/*
 * This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * This is the edit-action (default), it will edit a item in the session
 * @author ken.depelchin@netlash.com
 */

class FrontendLunchEdit extends FrontendBaseBlock
{
	public function execute()
	{
		parent::execute();
		$this->loadTemplate();
		$this->getData();
		$this->parse();
	}

	private function getData()
	{
		$info = array();

		if($this->URL->getParameter(1) === null) $this->redirect(FrontendNavigation::getURL(404));
		else
		{
			$this->id = $this->URL->getParameter(1);
			if (SpoonSession::exists('frieten'))
			{
				$this->order = SpoonSession::get('frieten');

				if (sizeof($this->order) != 0)
				{
					$this->loadForm();
					$this->validateForm();
				}

				foreach($this->order as $item) if ($item['id'] == $this->id) $info = $item;
			}
		}

		$this->tpl->assign('info',$info);
		//Spoon::dump($info);
	}

	private function loadForm()
	{
		$this->frm = new FrontendForm('editOrderForm');
		//Spoon::dump($this->order);

		$this->productName = FrontendLunchModel::getMenuItemName($this->id);

		$this->frm->addText('count', $this->order[$this->productName]['count']);
		if ($this->order[$this->productName]['note'] === '') $this->frm->addTextarea('note');
		else $this->frm->addTextarea('note',$this->order[$this->productName]['note']);
	}

	private function validateForm()
	{
		if ($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			$count = $this->frm->getField('count')->getValue();
			$note = $this->frm->getField('note')->getValue();

			$this->frm->getField('count')->isFilled(FL::err('AuthorIsRequired'));

			if ($this->frm->isCorrect())
			{
				// add the new info to the session
				//Spoon::dump($this->productName);

				if ($count == 0)
				{
					unset($this->order[$this->productName]);
				}

				else
				{
					$this->order[$this->productName]['count'] = $count;
					$this->order[$this->productName]['note'] = $note;
					$this->order[$this->productName]['total'] = $count * $this->order[$this->productName]['price'];
				}

				// niet nodig
				if (SpoonSession::exists('frieten')) {
					SpoonSession::delete('frieten');
					SpoonSession::set('frieten',$this->order);
				}

				// redirect to your order
				$this->redirect(FrontendNavigation::getURLForBlock('lunch'));
			}
		}
	}

	private function parse()
	{
		if (sizeof($this->order) == 0) $this->redirect(FrontendNavigation::getURLForBlock('lunch'));
		else $this->frm->parse($this->tpl);
	}
}