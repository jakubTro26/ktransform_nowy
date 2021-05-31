<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 27.05.16
 * Time: 15:44
 */

namespace Invoice\Model;


class Invoice extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'invoice');
    }

    public function setPrimaryColumn()
    {
        return 'invoice_id';
    }

}