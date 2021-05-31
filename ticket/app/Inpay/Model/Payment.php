<?php

namespace Inpay\Model;


class Payment extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'payu_payment');
    }

    public function setPrimaryColumn()
    {
        return 'payu_payment_id';
    }

}