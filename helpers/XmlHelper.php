<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 20.5.16
 * Time: 0.34
 */

namespace app\helpers;


class XmlHelper
{
    static public function xmlFIleToArray($file)
    {
        $xml = simplexml_load_file($file, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        return $array;
    }
}