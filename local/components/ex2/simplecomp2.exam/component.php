<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


/*************************************************************************
	Processing of received parameters
*************************************************************************/
if (! isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 180;



if (! CModule::IncludeModule("iblock")) {
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if ( !$arParams['IBLOCK_CATALOG'] && ! $arParams['IBLOCK_NEWS'] && ! $arParams['PROPERTY_CODE_NEWS'])
    return;


$catalogByNews = array();

if ($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N" ? false : $USER->GetGroups()))) {
    $rsSelect = array(
        "ID",
        "NAME",
    );
    $rsFilter = array(
        "IBLOCK_ID" => $arParams['IBLOCK_CLASSIFIER'],
        "ACTIVE" => 'Y'
    );
    $arClassifier = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect);

    $classifierList = array();
    $classifierIds = array();

    while ($classifier = $arClassifier->Fetch()) {
        $classifierList[$classifier['ID']] = ['NAME' => $classifier['NAME']];
        $classifierIds[] = intval($classifier['ID']);
    }

    //


    $rsSelect = array(
        "ID",
        "NAME",
        "PROPERTY_MATERIAL",
        "PROPERTY_PRICE",
        "PROPERTY_ARTNUMBER",
        "IBLOCK_SECTION_ID",
        $arParams['PROPERTY_CODE_CLASSIFIER']
    );
    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_CATALOG'],
        'ACTIVE' => 'Y',
        $arParams['PROPERTY_CODE_CLASSIFIER'] => $classifierIds
    );
    $arProducts = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect);

    while ($product = $arProducts->Fetch()) {
        $firmId = $product[$arParams['PROPERTY_CODE_CLASSIFIER'] . '_VALUE'];
        $detailPage = str_replace("#SECTION_ID#", $product['IBLOCK_SECTION_ID'], $arParams['TEMPLATE_DETAIL_URL']);
        $detailPage = str_replace("#ELEMENT_ID#", $product['ID'], $detailPage);

        $classifierList[$firmId]['ITEMS'][$product['ID']] = array(
            'NAME' => $product['NAME'],
            'MATERIAL' => $product['PROPERTY_MATERIAL_VALUE'],
            'PRICE' => $product['PROPERTY_PRICE_VALUE'],
            'ARTNUMBER' => $product['PROPERTY_ARTNUMBER_VALUE'],
            'DETAIL_PAGE_URL' => $detailPage
        );
    }

    $APPLICATION->SetTitle('Разделов: ' . count($classifierList));

    $arResult = $classifierList;

    $this->IncludeComponentTemplate();
} else {
    $this->abortResultCache();
}