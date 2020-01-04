<?php

class Statistic
{
	public function init()
	{
		db()->query('DROP TABLE `'.PREF.'stats`');
		db()->query('CREATE TABLE `'.PREF.'stats` (
							`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
							`name` VARCHAR(100) NOT NULL ,
							`phone` VARCHAR(100) NOT NULL ,
							`addr` VARCHAR(200) NOT NULL ,
							`date` DATE NOT NULL ,
							`date_d` DATE NOT NULL ,
							`name_prod` VARCHAR(200) NOT NULL ,
							`price_prod` DOUBLE(18,2) NOT NULL ,
							`count_prod` DOUBLE(18,2) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;');
		$this->generateDatabase();
		return true;
	}

	public function mostCustomers()
	{
		$obj = array();
		foreach (db()->query('SELECT COUNT(id) as acnt, date_d FROM `'.PREF.'cart` WHERE 1 GROUP BY `date_d`;', PDO::FETCH_ASSOC) as $value) {
			$tm = strtotime($value['date_d']);
			$value['date_d'] = date('d F Y', $tm).', '.date('l', $tm);
			$obj[] = array($value['acnt'], $value['date_d']);
		}
		usort($obj, function($a, $b){
			if ($a == $b) { return 0; }
			return ($a < $b) ? -1 : 1;
		});
		$obj = array_reverse($obj);
		return $obj;
	}

	public function popularCustomers()
	{
		$obj = array();
		foreach (db()->query('SELECT COUNT(id) as acnt, name FROM `'.PREF.'cart` WHERE 1 GROUP BY `name`;', PDO::FETCH_ASSOC) as $value) {
			$obj[] = array($value['acnt'], $value['name']);
		}
		usort($obj, function($a, $b){
			if ($a == $b) { return 0; }
			return ($a < $b) ? -1 : 1;
		});
		$obj = array_reverse($obj);
		return $obj;
	}

	public function popularRestaurant()
	{
		$obj = array();
		foreach (db()->query('SELECT COUNT(id) as acnt, addr FROM `'.PREF.'cart` WHERE 1 GROUP BY `addr`;', PDO::FETCH_ASSOC) as $value) {
			$obj[] = array($value['acnt'], $value['addr']);
		}
		usort($obj, function($a, $b){
			if ($a == $b) { return 0; }
			return ($a < $b) ? -1 : 1;
		});
		$obj = array_reverse($obj);
		return $obj;
	}

	public function popularGoods()
	{
		$obj = array();
		foreach (db()->query('SELECT COUNT(id) as acnt, name_prod FROM `'.PREF.'stats` WHERE 1 GROUP BY `name_prod`;', PDO::FETCH_ASSOC) as $value) {
			$obj[] = array($value['acnt'], $value['name_prod']);
		}
		usort($obj, function($a, $b){
			if ($a == $b) { return 0; }
			return ($a < $b) ? -1 : 1;
		});
		$obj = array_reverse($obj);
		return $obj;
	}

	private function generateDatabase()
	{
		$base = array();
		foreach (db()->query('SELECT name, phone, addr, date, date_d, prod_ids FROM `'.PREF.'cart` WHERE 1 ORDER BY `id` ASC;', PDO::FETCH_ASSOC) as $value) {
			$value['prod_ids'] = optimizeDecryptStats( baseDeCrypt($value['prod_ids']) );
			foreach ($value['prod_ids'] as $prod) {
				$base[] = array(
							'name' => sqlProtect($value['name']),
							'phone' => sqlProtect($value['phone']),
							'addr' => sqlProtect($value['addr']),
							'date' => $value['date'],
							'date_d' => $value['date_d'],
							'name_prod' => sqlProtect($prod['name']),
							'price_prod' => $prod['price'],
							'count_prod' => $prod['count']
				);
			}
		}
		// to sql query
		$sql = 'INSERT INTO `rnbw_stats` (`name`, `phone`, `addr`, `date`, `date_d`, `name_prod`, `price_prod`, `count_prod`) VALUES ';
		foreach ($base as $val){
			$sql.= '("'.$val['name'].'", "'.$val['phone'].'", "'.$val['addr'].'", "'.$val['date'].'", "'.$val['date_d'].'", "'.$val['name_prod'].'", '.$val['price_prod'].', '.$val['count_prod'].'),';
		}
		$sql = substr($sql,0,-1);
		db()->query($sql);
		return true;
	}
	
}
