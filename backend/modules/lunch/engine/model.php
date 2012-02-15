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
	'SELECT i.id, i.price, i.name FROM lunch_menu_item AS i
		WHERE i.lunch_category_id = ? AND i.language = ?
	';

	/**
	 * Checks if a category exists
	 *
	 * @param int $id The id of the category to check for existence.
	 * @return int
	 */
	public static function existsCategory($id)
	{
		return (bool) BackendModel::getDB()->getVar(
				'SELECT COUNT(id)
				FROM lunch_category AS i
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

		// TODO: meta aanpassen

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
		/*$db = BackendModel::getDB();
		$result = (bool)$db->getVar(
			'SELECT count(i.id)
			FROM lunch_category AS i
			WHERE i.name = ? AND i.language = ?',
			array((string) $cat, BL::getWorkingLanguage()));

		echo $result;*/
		return (bool) BackendModel::getDB()->getVar(
			'SELECT count(i.id)
			FROM lunch_category AS i
			WHERE i.name = ? AND i.language = ?',
			array((string) $cat, BL::getWorkingLanguage())
		);
	}
}