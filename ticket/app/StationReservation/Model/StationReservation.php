<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 27.05.16
 * Time: 15:44
 */

namespace StationReservation\Model;


class StationReservation extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'station_reservation');
    }

    public function setPrimaryColumn()
    {
        return 'station_reservation_id';
    }

    protected function getSelect($filtr) {

        $select = parent::getSelect($filtr);
        $select->joinLeft(array('s' => \MyConfig::getValue('dbPrefix') . 'station'), 'x.station_id = s.station_id', array(
            'station_name'
        ));
        $select->joinLeft(array('c' => \MyConfig::getValue('dbPrefix') . 'company'), 'x.company_id = c.company_id', array(
            'company_name',
            'company_tel',
            'company_email'
        ));

        return $select;
    }

}