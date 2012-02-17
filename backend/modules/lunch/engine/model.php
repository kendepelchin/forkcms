<?php

/*
* This file is part of Fork CMS.
*
* For the full copyright and license information, please view the license
* file that was distributed with this source code.
*/

/**
 * In this file we will store all connections to the database for our lunch module
 *
 */

class BackendLunchModel
{
	const QRY_DATAGRID_BROWSE_FOR_CATEGORY =
	'SELECT i.id, i.name, COUNT(p.id) AS num_items
	 FROM lunch_category AS i
	 LEFT OUTER JOIN lunch_menu_item AS p ON i.id = p.lunch_category_id AND p.language = i.language
	 WHERE i.language = ?
	 GROUP BY i.id';

	const QRY_DATAGRID_BROWSE_FOR_LUNCH_MENU_ITEMS =
	'SELECT i.name, i.id, CONCAT("€ ", i.price) as price FROM lunch_menu_item AS i
	 WHERE i.lunch_category_id = ? AND i.language = ? ORDER BY i.id DESC';

	const QRY_DATAGRID_BROWSE =
	'SELECT i.name,i.id, CONCAT("€ ", i.price) as price FROM lunch_menu_item AS i
	 WHERE i.language = ? ORDER BY i.id DESC';

	const QRY_DATAGRID_BROWSE_ORDERS_FOR_DATE =
	'SELECT i.name, SUM(i.count * p.price) AS subtotal FROM lunch_order AS i
	 LEFT OUTER JOIN lunch_menu_item AS p ON i.lunch_menu_item_id = p.id AND p.language = i.language
	 WHERE i.time BETWEEN DATE_SUB(?,INTERVAL 7 DAY) AND ? AND i.language = ? GROUP BY i.name ORDER BY i.time DESC';

	/**
	 * Checks if a category exists
	 *
	 * @param int $id The id of the category to check for existence.
	 * @return int
	 */
	public static function existsCategory($id)
	{
		return (bool) BackendModel::getDB()->getVar(
			'SELECT COUNT(i.id)
			 FROM lunch_category AS i
			 WHERE i.id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	/**
	 * Checks if a menu item exists
	 * @param int $id the id of the menu item to check for existence
	 * @return boolean
	 */
	public static function existsMenuItem($id)
	{
		return (bool) BackendModel::getDB()->getVar(
			'SELECT COUNT(i.id)
			 FROM lunch_menu_item AS i
			 WHERE i.id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	/**
	 * Get all data for a given id
	 *
	 * @param int $id The id of the category to fetch.
	 * @return array
	 */
	public static function getCategory($id)
	{
		return (array) BackendModel::getDB()->getRecord(
			'SELECT i.*
			 FROM lunch_category AS i
			 WHERE i.id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	/**
	 * Checks if it is allowed to delete the category
	 *
	 * @param int $id The id of the category.
	 * @return bool
	 */
	public static function deleteCategoryAllowed($id)
	{
		return !(bool) BackendModel::getDB()->getVar(
			'SELECT COUNT(i.id)
			 FROM lunch_menu_item AS i
			 WHERE i.lunch_category_id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage())
		);
	}

	public static function updateCategory(array $item)
	{
		$db = BackendModel::getDB(true);
		$updated = $db->update('lunch_category', $item,'id = ?',$item['id']);

		return $updated;
	}

	public static function updateMenuItem(array $item)
	{
		$db = BackendModel::getDB(true);
		$updated = $db->update('lunch_menu_item', $item, 'id = ?', $item['id']);

		return $updated;
	}

	public static function insertCategory(array $item)
	{
		$db = BackendModel::getDB(true);
		$id = $db->insert('lunch_category', $item);

		return $id;
	}

	public static function categoryAlreadyExists($cat)
	{
		return (bool) BackendModel::getDB()->getVar(
			'SELECT count(i.id)
			 FROM lunch_category AS i
			 WHERE i.name = ? AND i.language = ?',
			array((string) $cat, BL::getWorkingLanguage())
		);
	}

	public static function itemAlreadyExists($item,$cat)
	{
		return (bool) BackendModel::getDB()->getVar(
			'SELECT count(i.id)
			 FROM lunch_menu_item AS i
			 WHERE i.name = ? AND i.lunch_category_id = ? AND i.language = ?',
			array((string) $item,(int)$cat, BL::getWorkingLanguage())
		);
	}

	public static function getItem($id)
	{
		return (array)BackendModel::getDB()->getRecord(
			'SELECT i.name, i.id, i.price, i.lunch_category_id AS category
			 FROM lunch_menu_item AS i
			 WHERE i.id = ? AND i.language = ?',
			array((int) $id, BL::getWorkingLanguage()));
	}

	/**
	 * Deletes a category
	 *
	 * @param int $id The id of the category to delete.
	 */
	public static function deleteCategory($id)
	{
		$id = (int) $id;
		$db = BackendModel::getDB(true);

		// get item
		$item = self::getCategory($id);

		if(!empty($item))
		{
			// delete category
			$db->delete('lunch_category', 'id = ?', array($id));
		}
	}

	public static function deleteMenuItem($id)
	{
		$id = (int) $id;
		$db = BackendModel::getDB(true);

		// get item
		$item = self::getItem($id);

		if(!empty($item))
		{
			// delete category
			$db->delete('lunch_menu_item', 'id = ?', array($id));
		}
	}

	public static function getCategories()
	{
		return (array)BackendModel::getDB()->getPairs(
			'SELECT i.id,CONCAT(i.name, " (",COUNT(p.lunch_category_id),")") AS name
			 FROM lunch_category AS i
			 LEFT OUTER JOIN lunch_menu_item AS p ON i.id = p.lunch_category_id AND p.language = i.language
			 WHERE i.language = ? GROUP BY i.id', array(BL::getWorkingLanguage())
			);
	}

	public static function addMenuItem($item)
	{
		$db = BackendModel::getDB(true);
		return (int)$db->insert('lunch_menu_item', $item);
	}

	public static function calculateTotal($timestamp)
	{
		return (float)BackendModel::getDB()->getVar(
			'SELECT SUM(i.count * p.price) AS subtotal FROM lunch_order AS i
			 LEFT OUTER JOIN lunch_menu_item AS p ON i.lunch_menu_item_id = p.id AND p.language = i.language
			 WHERE i.time BETWEEN DATE_SUB(?,INTERVAL 7 DAY) AND ? AND i.language = ? ORDER BY i.time DESC'
			,array($timestamp,$timestamp,BL::getWorkingLanguage()));
	}
}