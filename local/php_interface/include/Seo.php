<?php


if (CModule::IncludeModule('iblock')) {
    global $APPLICATION;

    $result = CIBlockElement::GetList(
        array(),
        array(
            'IBLOCK_ID' => IBLOCK_ID_METATAGS,
            'NAME' => $APPLICATION->GetCurPage()
        ),
        false,
        false,
        array("PROPERTY_TITLE", "PROPERTY_DESCRIPTION")
    )->Fetch();

    if ($result) {
        if ($result['PROPERTY_TITLE_VALUE'])
            $APPLICATION->SetPageProperty('title', $result['PROPERTY_TITLE_VALUE']);
        if ($result['PROPERTY_DESCRIPTION_VALUE'])
            $APPLICATION->SetPageProperty('description', $result['PROPERTY_DESCRIPTION_VALUE']);
    }

}