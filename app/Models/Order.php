<?php

class Order
{

	public static function groupDayList()
	{
		$obj = array();
		foreach (db()->query('SELECT COUNT(id) as acnt, date_d FROM `'.PREF.'cart` WHERE 1 GROUP BY `date_d`;') as $value) {
			$tm = strtotime($value['date_d']);
			$obj[$tm] = array($tm, $value['date_d'], $value['acnt']);
		}
		arsort($obj);
		return $obj;
	}

	public static function detailDayList( $date )
	{
		if ( strtotime($date) > 0 ) {
			$obj = array();
			foreach (db()->query('SELECT u_id,name,phone,addr,comm,delivery_time,tsum,date,date_d,prod_ids FROM `'.PREF.'cart` WHERE `date_d` = "'.$date.'" ORDER BY `name` ASC;', PDO::FETCH_ASSOC) as $value) {
				$value['addr'] = optimizeAddr($value['addr']);
				$value['prod_ids'] = optimizeDecrypt( baseDeCrypt($value['prod_ids']) );
				$obj[] = $value;
			}
			return $obj;
		}
		else return 'date error';
	}

	public static function toExcel($dated, $products) {
		$titles = array('Объект доставки', 'Цех изготов.', 'Наименование товара', 'Ед.', 'Кол-во', 'Отправка', 'Ф.И.О. получателя', 'Комментарий телефон');
		$filename = 'orders-'. $dated .'.csv';
		$now = gmdate('D, d M Y H:i:s');
		$charset = isset($_GET['win']) ? 'windows-1251' : 'utf-8';
		header('Content-type: text/html; charset='.$charset);
		header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
		header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
		header('Last-Modified: '. $now .' GMT');
		// force download
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream');
		header('Content-Type: application/download');
		// disposition / encoding on response body
		header('Content-Disposition: attachment;filename='. $filename);
		header('Content-Transfer-Encoding: binary');
		echo arrayToCsv($products, $titles);
		die();
	}

}
