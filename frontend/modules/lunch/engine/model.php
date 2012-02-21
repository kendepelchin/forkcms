<?php
class FrontendLunchModel
{
	public static function getAllCategories()
	{
		$tmp =  (array) FrontendModel::getDB()->getRecords(
			'SELECT i.id, i.name, count(p.id) AS total
			 FROM lunch_category AS i
			 LEFT OUTER JOIN lunch_menu_item AS p on i.id = p.lunch_category_id AND p.language = i.language
			 WHERE i.language = p.language
			 GROUP BY i.name ORDER BY i.name'
		);

		$results = array();

		foreach($tmp as $key => $category)
		{
			$results[] = array(
				'name' => $category['name'],
				'items' => self::getAllMenuItemsForCategory($category['name']),
				'total' => $category['total']
			);
		}

		return $results;
	}

	public static function getAllMenuItemsForCategory($menuItem)
	{
		return (array) FrontendModel::getDB()->getRecords(
			'SELECT i.id, i.name, i.price
			 FROM lunch_menu_item AS i
			 LEFT OUTER JOIN lunch_category AS p on i.lunch_category_id = p.id
			 WHERE p.name = ? AND p.language = ?
			 ORDER BY i.name'
			, array((string) $menuItem, FRONTEND_LANGUAGE));
	}

	public static function getMenuItem($id)
	{
		return (array) FrontendModel::getDB()->getRecord(
			'SELECT i.price, i.name, i.id
			 FROM lunch_menu_item AS i
			 WHERE i.id = ? AND i.language = ?
			',array((int) $id, FRONTEND_LANGUAGE));
	}
}