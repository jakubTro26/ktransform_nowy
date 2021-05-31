<?php

namespace Payu\Model;


class LogPay extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'payu_log');
    }

    public function setPrimaryColumn()
    {
        return 'payu_log_id';
    }

}