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
    }

    $rsSelect = array(
        "ID",
        "NAME",
        "ACTIVE_FROM"
    );
    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_NEWS'],
        "ID" => $newsIds
    );

    $arNews = CIBlockElement::GetList(array(), $rsFilter, false, false, $rsSelect);

    while ($news = $arNews->Fetch()) {
        foreach ($catalogByNews as $key => $value) {
            if ($news['ID'] == $key) {
                $catalogByNews[$key]['NAME'] = $news['NAME'];
                $catalogByNews[$key]['ACTIVE_FROM'] = $news['ACTIVE_FROM'];
            }
        }
    }

    $items = array();

    foreach ($catalogByNews as $nKey => $news) {
        foreach ($news['ITEMS'] as $key => $ITEM) {
            if (! isset($items[$key])) {
                $items[$key] = $ITEM;

                $arButtons = CIBlock::GetPanelButtons(
                    $fields["IBLOCK_ID"],
                    $fields["ID"],
                    0,
                    array("SECTION_BUTTONS"=>false, "SESSID"=>false)
                );
                $editLink = $arButtons["edit"]["edit_element"]["ACTION_URL"];
                $deleteLink = $arButtons["edit"]["delete_element"]["ACTION_URL"];

                $catalogByNews[$nKey]['ITEMS'][$key]['EDIT_LINK'] = $editLink;
                $catalogByNews[$nKey]['ITEMS'][$key]['DELETE_LINK'] = $deleteLink;
            }
        }
    }

    $APPLICATION->SetTitle('В каталоге товаров представлено товаров: ' . count($items));

    $arResult['NEWS'] = $catalogByNews;
    $arResult['ITEMS'] = $items;

    if ($APPLICATION->GetShowIncludeAreas()) {

        $iblock = GetIBlock($arParams['IBLOCK_CATALOG']);

        $url = "/bitrix/admin/iblock_section_admin.php?IBLOCK_ID={$iblock['ID']}&type={$iblock['IBLOCK_TYPE_ID']}";

        $this->AddIncludeAreaIcon(
            array(
                'URL'   => $url,
                'TITLE' => "ИБ в админке",
                "IN_PARAMS_MENU" => true
            )
        );
    }
    echo time() . "<br>";
    $this->IncludeComponentTemplate();
    $this->endResultCache();
} else {
    $this->abortResultCache();
}
