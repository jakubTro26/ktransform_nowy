<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 27.05.16
 * Time: 15:44
 */

namespace Ticket\Model;


class Type extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'ticket_type');
    }

    public function setPrimaryColumn()
    {
        return 'ticket_type_id';
    }

}