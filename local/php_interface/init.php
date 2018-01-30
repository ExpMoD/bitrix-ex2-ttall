<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.12.17
 * Time: 10:56
 */

define('IBLOCK_ID_PRODUCT', 2);



if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/EventHandlers.php"))
    require_once($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/EventHandlers.php");

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/Seo.php"))
    require_once($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/Seo.php");


define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");


function CheckUserCount()
{
    $date = new DateTime();
    $date = \Bitrix\Main\Type\DateTime::createFromTimestamp($date->getTimestamp());

    $lastDate = COption::GetOptionString("main", 'last_date_agent_checkUserCount');


    if ($lastDate) {
        $arFilter = array('>=DATE_REGISTER' => $lastDate);
    } else {
        $arFilter = array();
    }

    $rsUsers = CUser::GetList($by = "DATE_REGISTER", $order = "ASC", $arFilter);

    $arUsers = array();

    while ($user = $rsUsers->Fetch()) {
        $arUsers[] = $user;
    }

    if (! $lastDate) {
        $lastDate = $arUsers[0]['DATE_REGISTER'];
    }

    $difference = intval(abs(
        strtotime($lastDate) - strtotime($date->toString())
    ));

    $days = round($difference / (3600 * 24));
    $countUsers = count($arUsers);

    $rsAdmins = CUser::GetList($by = "ID", $order = "ASC", array('GROUPS_ID' => 1));


    while ($admin = $rsAdmins->Fetch()) {
        CEvent::Send('COUNT_REGISTERED_USERS', 's1', array(
            'EMAIL_TO' => $admin['EMAIL'],
            'COUNT_USERS' => $countUsers,
            'COUNT_DAYS' => $days,
        ), "Y", "29");

    }

    COption::SetOptionString("main", 'last_date_agent_checkUserCount', $date->toString());

    return "CheckUserCount();";
}