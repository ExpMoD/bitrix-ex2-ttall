<?php

if ($arParams['IBLOCK_CANONICAL_ID'] && CModule::IncludeModule('iblock')) {
    $rsFilter = array(
        "IBLOCK_ID" => $arParams['IBLOCK_CANONICAL_ID'],
        "PROPERTY_NEWS" => $arResult['ID']
    );

    $canonical = CIBlockElement::GetList(array(), $rsFilter)->Fetch()['NAME'];

    if ($canonical) {
        $arResult['CANONICAL_LINK'] = $canonical;
        $this->__component->SetResultCacheKeys(array('CANONICAL_LINK'));
    }
}