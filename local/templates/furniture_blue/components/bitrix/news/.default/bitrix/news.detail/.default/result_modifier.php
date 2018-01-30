<?php

if ($arParams['IBLOCK_CANONICAL_ID'] && CModule::IncludeModule('iblock')) {
    $rsSelect = array(
        'NAME'
    );

    $rsFilter = array(
        "IBLOCK_ID" => $arParams['IBLOCK_CANONICAL_ID'],
        "PROPERTY_NEWS" => $arResult['ID']
    );

    $canonical = CIBlockElement::GetList(array(), $rsFilter, false ,false, $rsSelect)->Fetch();

    if ($canonical) {
        $arResult['CANONICAL_LINK'] = $canonical['NAME'];
        $this->__component->SetResultCacheKeys(array('CANONICAL_LINK'));
    }
}