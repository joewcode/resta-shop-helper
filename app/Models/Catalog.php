<?php

class Catalog
{

	public static function getCatalog()
	{
		$obj = array();
		foreach (db()->query('SELECT `id`, `name`, `unit`, `step`, `price`, `img`, `techno`, `descr` FROM `'.PREF.'catalog` WHERE `active` = 1 ORDER BY `name` ASC;', PDO::FETCH_ASSOC) as $v) {
			$obj[$v['id']] = $v;
		}
		return $obj;
	}

}
