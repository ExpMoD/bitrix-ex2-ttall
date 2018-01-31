<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.12.17
 * Time: 10:56
 */

define('IBLOCK_ID_PRODUCT', 2);
define('IBLOCK_ID_METATAGS', 6);



if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/EventHandlers.php"))
    require_once($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/EventHandlers.php");

if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/Seo.php"))
    require_once($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/Seo.php");


define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");


function CheckUserCount()
{

    $optionName = "LAST_DATE_CCRU";

    global $APPLICATION;

    $lastDate = COption::GetOptionString('main', $optionName);
    $nowDate = time();
    $rsFilter = array();

    if ($lastDate)
        $rsFilter["DATE_REGISTER_1"] = \Bitrix\Main\Type\Date::createFromTimestamp($lastDate);

    $rsUsers = CUser::GetList($by = 'DATE_REGISTER', $order = "ASC", $rsFilter, array('FIELDS' => array('ID', 'DATE_REGISTER')));

    $count = $rsUsers->SelectedRowsCount();

    if ($count > 0) {
        if (! $lastDate)
            $lastDate = strtotime($rsUsers->Fetch()['DATE_REGISTER']);

        $countDays = ceil(abs($lastDate - $nowDate) / (3600 * 24));

        $rsAdmins = CUser::GetList($by = 'ID', $order = 'ASC', array('GROUPS_ID' => 1), array('FIELDS' => array('EMAIL')));

        $adminEmails = array();

        while ($arAdmin = $rsAdmins->Fetch())
            $adminEmails[] = $arAdmin['EMAIL'];

        CEvent::Send('COUNT_REGISTERED_USERS', 's1',
            array(
                'EMAIL_TO' => implode(',', $adminEmails),
                'COUNT_USERS' => $count,
                'COUNT_DAYS' => $countDays,
            ), 'Y', 29
        );

        COption::SetOptionString("main", $optionName, $nowDate);
    }

    return "CheckUserCount();";
}