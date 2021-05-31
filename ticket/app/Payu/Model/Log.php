<?php

namespace PayU\Model;


class Log extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'payu_log');
    }

    public function setPrimaryColumn()
    {
        return 'log_id';
    }

}