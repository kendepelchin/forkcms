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
			 ORDER BY i.price'
			, array((string) $menuItem, FRONTEND_LANGUAGE)
		);
	}

	public static function getMenuItem($id)
	{
		return (array) FrontendModel::getDB()->getRecord(
			'SELECT i.price, i.name, i.id
			 FROM lunch_menu_item AS i
			 WHERE i.id = ? AND i.language = ?
			',array((int) $id, FRONTEND_LANGUAGE)
		);
	}

	public static function addOrder($name, $sessionArray)
	{
		$db = FrontendModel::getDB(true);

		foreach ($sessionArray as $item)
		{
			// add each item to the database
			$array = array(
				'count' => $item['count'],
				'note' => $item['note'],
				'time' => SpoonDate::getDate('Y-m-d H:i:s'),
				'name' => $name,
				'language' => FRONTEND_LANGUAGE,
				'lunch_menu_item_id' => $item['id']
			);

			$id = $db->insert('lunch_order', $array);
		}
	}

	public static function getMenuItemName($id)
	{
		return (string) FrontendModel::getDB()->getVar(
			'SELECT i.name
			 FROM lunch_menu_item AS i
			 WHERE i.id = ?',array((int) $id)
		);
	}

	public static function getOrdersForDate($timestamp) {
		return (array) FrontendModel::getDB()->getRecords(
			'SELECT i.name AS name, p.price, i.id, i.note, p.name AS item, i.count, SUM(i.count * p.price) AS subtotal
			 FROM lunch_order AS i
			 LEFT OUTER JOIN lunch_menu_item AS p ON i.lunch_menu_item_id = p.id AND p.language = i.language
			 WHERE i.time BETWEEN DATE_SUB(?,INTERVAL 7 DAY) AND ? AND i.language = ?
			 GROUP BY i.id
			 ORDER BY i.time DESC'
			 ,array($timestamp, $timestamp, FRONTEND_LANGUAGE)
		);
	}
}