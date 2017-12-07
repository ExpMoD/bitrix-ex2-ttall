<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 07.12.17
 * Time: 11:04
 */


AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EventHandlers", "OnBeforeDeactivate"));
class EventHandlers
{
    public function OnBeforeDeactivate($arParams) {
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
}
