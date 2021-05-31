<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 27.05.16
 * Time: 15:44
 */

namespace Visitor\Model;


class Visitor extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'visitor');
    }

    public function setPrimaryColumn()
    {
        return 'visitor_id';
    }
	
	public function processGetOneRecord($row) {
	
		$modelPayU = new \Payu\Model\Payment();
		$row['payment'] = $modelPayU->getOne(array(
			'visitor_id' => $row['visitor_id']
		)); 
		
		return $row;
	}

}