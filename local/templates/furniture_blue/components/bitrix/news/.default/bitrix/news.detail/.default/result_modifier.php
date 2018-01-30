<?php

if (isset($arParams['IBLOCK_ID_CANONICAL']) && CModule::IncludeModule('iblock')) {
    $rsSelect = array(
        "NAME"
    );
    $rsFilter = array(
        "IBLOCK_ID" => $arParams['IBLOCK_ID_CANONICAL'],
        "PROPERTY_NEWS" => $arResult['ID']
    );
    $rsCanonical = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect)->Fetch();

    if (isset($rsCanonical)) {
        $arResult['CANONICAL'] = $rsCanonical['NAME'];
        $this->__component->SetResultCacheKeys(array('CANONICAL'));
    }
}


