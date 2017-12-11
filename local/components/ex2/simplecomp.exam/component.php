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

$timeCache = false;

if (isset($_GET['F'])) {
    $timeCache = 0;
}

if ($this->StartResultCache($timeCache)) {
    $rsSelect = array('ID', 'NAME', $arParams['PROPERTY_CODE_NEWS']);
    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_CATALOG'],
        "ACTIVE" => "Y",
        '!' . $arParams['PROPERTY_CODE_NEWS'] => false
    );
    $sections = CIBlockSection::GetList(array(), $rsFilter, false, $rsSelect);

    while ($section = $sections->Fetch()) {
        foreach ($section[$arParams['PROPERTY_CODE_NEWS']] as $item => $value) {
            $catalogByNews[$value]['SECTIONS'][$section['ID']] = $section['NAME'];
        }
    }

    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_CATALOG'],
        "ACTIVE" => "Y",
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

    $arProducts = CIBlockElement::GetList(array(), $rsFilter);

    while ($product = $arProducts->GetNextElement()) {
        $fields = $product->GetFields();
        $properties = $product->GetProperties();

        foreach ($catalogByNews as $cKey => $cVal) {
            foreach ($cVal['SECTIONS'] as $sKey => $sVal) {
                if ($sKey == $fields['IBLOCK_SECTION_ID']) {
                    $catalogByNews[$cKey]['ITEMS'][$fields['ID']] = array(
                        'NAME' => $fields['NAME'],
                        'IBLOCK_ID' => $fields['IBLOCK_ID'],
                        'MATERIAL' => $properties['MATERIAL']['VALUE'],
                        'PRICE' => $properties['PRICE']['VALUE'],
                        'ARTNUMBER' => $properties['ARTNUMBER']['VALUE'],
                    );
                }
            }
        }
    }

    //

    $rsFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_NEWS']
    );

    $arNews = CIBlockElement::GetList(array(), $rsFilter);

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


    $this->IncludeComponentTemplate();
} else {
    $this->abortResultCache();
}
