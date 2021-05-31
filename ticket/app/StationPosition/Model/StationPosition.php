<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 27.05.16
 * Time: 15:44
 */

namespace StationPosition\Model;


class StationPosition extends \Db\Model
{
    public function getDbTable()
    {
        return \Db\TableFactory::get(\MyConfig::getValue('dbPrefix') . 'station_position');
    }

    public function setPrimaryColumn()
    {
        return 'station_position_id';
    }

}