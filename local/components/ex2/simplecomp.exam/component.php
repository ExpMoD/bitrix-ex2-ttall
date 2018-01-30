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
$sectionsIds = array();
$newsIds = array();

$timeCache = false;

if (isset($_GET['F'])) {
    $timeCache = 0;
}
global $CACHE_MANAGER;
if ($this->StartResultCache($timeCache, false, '/servicesIblock')) {
    $CACHE_MANAGER->RegisterTag('iblock_id_3');

    $rsSelect = array('ID', 'NAME', $arParams['PROPERTY_CODE_NEWS']);
    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_CATALOG'],
        "ACTIVE" => "Y",
        '!' . $arParams['PROPERTY_CODE_NEWS'] => false
    );
    $sections = CIBlockSection::GetList(array(), $rsFilter, false, $rsSelect);

    while ($section = $sections->Fetch()) {
        $sectionIds[$section['ID']] = $section['ID'];

        foreach ($section[$arParams['PROPERTY_CODE_NEWS']] as $item => $value) {
            $catalogByNews[$value]['SECTIONS'][$section['ID']] = $section['NAME'];
            $newsIds[$value] = $value;
        }
    }


    $rsSelect = array(
        "ID",
        "NAME",
        "IBLOCK_SECTION_ID",
        "PROPERTY_MATERIAL",
        "PROPERTY_PRICE",
        "PROPERTY_ARTNUMBER",
    );
    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_CATALOG'],
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => $sectionIds
    );

    if (isset($_GET['F'])) {
        $rsFilter[] = array(
            "LOGIC" => 'OR',
            array(
                'LOGIC' => 'AND',
                array('<=PROPERTY_PRICE' => 1700),
                array('PROPERTY_MATERIAL' => 'Дерево, ткань')
            ),
            array(
                'LOGIC' => 'AND',
                array('<PROPERTY_PRICE' => 1500),
                array('PROPERTY_MATERIAL' => 'Металл, пластик')
            )
        );
    }

    $arProducts = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect);

    $itemsPrice = array();


    while ($product = $arProducts->Fetch()) {
        foreach ($catalogByNews as $cKey => $cVal) {
            foreach ($cVal['SECTIONS'] as $sKey => $sVal) {
                if ($sKey == $product['IBLOCK_SECTION_ID']) {
                    $catalogByNews[$cKey]['ITEMS'][$product['ID']] = array(
                        "NAME" => $product['NAME'],
                        "MATERIAL" => $product['PROPERTY_MATERIAL_VALUE'],
                        "PRICE" => $product['PROPERTY_PRICE_VALUE'],
                        "ARTNUMBER" => $product['PROPERTY_ARTNUMBER_VALUE'],
                    );
                    break;
                }
            }
        }

        $itemsPrice[] = $product['PROPERTY_PRICE_VALUE'];
    }

    $rsSelect = array(
        "ID",
        "NAME",
        "ACTIVE_FROM",
        "IBLOCK_ID"
    );
    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_NEWS'],
        "ID" => $newsIds
    );

    $arNews = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect);

    while ($news = $arNews->Fetch()) {
        $arButtons = CIBlock::GetPanelButtons(
            $arParams['IBLOCK_NEWS'],
            $news["ID"]
        );

        $catalogByNews[$news['ID']]['NAME'] = $news['NAME'];
        $catalogByNews[$news['ID']]['ACTIVE_FROM'] = $news['ACTIVE_FROM'];
        $catalogByNews[$news['ID']]['IBLOCK_ID'] = $news['IBLOCK_ID'];
        $catalogByNews[$news['ID']]['EDIT_LINK'] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $catalogByNews[$news['ID']]['DELETE_LINK'] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
    }


    $arResult['NEWS'] = $catalogByNews;
    $arResult['COUNT_ELEMENTS'] = count($itemsPrice);
    $arResult['MIN_PRICE'] = min($itemsPrice);
    $arResult['MAX_PRICE'] = max($itemsPrice);

    if ($APPLICATION->GetShowIncludeAreas()) {
        $arButtons = CIBlock::GetPanelButtons(
            $arParams['IBLOCK_NEWS'],
            0
        );

        $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));


        $iblock = GetIBlock($arParams['IBLOCK_CATALOG']);

        $url = "/bitrix/admin/iblock_section_admin.php?IBLOCK_ID={$iblock['ID']}&type={$iblock['IBLOCK_TYPE_ID']}";

        $this->AddIncludeAreaIcon(
            array(
                'URL' => $url,
                'TITLE' => "ИБ в админке",
                "IN_PARAMS_MENU" => true
            )
        );
    }

    $this->setResultCacheKeys(array('COUNT_ELEMENTS', 'MIN_PRICE', 'MAX_PRICE'));
    $this->IncludeComponentTemplate();
} else {
    $this->abortResultCache();
}

$APPLICATION->SetTitle('В каталоге товаров представлено товаров: ' . $arResult['COUNT_ELEMENTS']);