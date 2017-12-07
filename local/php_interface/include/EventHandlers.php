<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.12.17
 * Time: 11:04
 */


AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EventHandlers", "OnBeforeDeactivate"));
AddEventHandler("main", "OnBeforeEventAdd", array("EventHandlers", "OnBeforeFeedbackAdd"));
class EventHandlers
{
    function OnBeforeDeactivate($arParams) {
        global $APPLICATION;
        if ($arParams['ACTIVE'] == 'N') {
            if (CModule::IncludeModule('iblock')) {
                $element = CIblockElement::GetList(
                    array(),
                    array(
                        'IBLOCK_ID' => 2,
                        'ID' => $arParams['ID'],
                        '>SHOW_COUNTER' => 2
                    )
                )->Fetch();

                if ($element) {
                    $APPLICATION->ThrowException("Товар невозможно деактивировать, у него {$element['SHOW_COUNTER']} просмотров");
                    return false;
                }
            }
        }
    }

    function OnBeforeFeedbackAdd(&$event, &$lid, &$arFields) {
        if ($event == 'FEEDBACK_FORM') {
            global $USER;

            if ($USER->IsAuthorized()) {
                $arFields['AUTHOR'] = "Пользователь авторизован: {$USER->GetId()} ({$USER->GetLogin()}) {$USER->GetFullName()}, данные из формы: {$arFields['AUTHOR']}";
            } else {
                $arFields['AUTHOR'] = "Пользователь не авторизован, данные из формы: {$arFields['AUTHOR']}";
            }

            CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => "Замена данных в отсылаемом письме",
                "MODULE_ID" => "main",
                "DESCRIPTION" => "Замена данных в отсылаемом письме – {$arFields['AUTHOR']}",
            ));
        }
    }
}
