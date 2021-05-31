<?php

namespace Db;

class TableFactory {

	/**
	 * Return zend table object
	 * 
	 * @param string type $primary defailt id
	 * @return Zend_Db_Table 
	 */
	static public function get($name, $primary = 'id') {

        if(\MyConfig::getValue('dbPrefix') != '') {
            $primary = str_replace(\MyConfig::getValue('dbPrefix'), '', $name).'_'.$primary;
        } else {
            $primary = $name.'_'.$primary;
        }

		$config = array(
			\Zend_Db_Table::PRIMARY => $primary,
			\Zend_Db_Table::NAME => $name
		);

		return new \Zend_Db_Table($config);
	}

}
