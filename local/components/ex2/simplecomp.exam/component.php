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

if ($this->StartResultCache()) {
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

    $arProducts = CIBlockElement::GetList(array(), $rsFilter);

    while ($product = $arProducts->GetNextElement()) {
        $fields = $product->GetFields();
        $properties = $product->GetProperties();

        foreach ($catalogByNews as $cKey => $cVal) {
            foreach ($cVal['SECTIONS'] as $sKey => $sVal) {
                if ($sKey == $fields['IBLOCK_SECTION_ID']) {
                    $catalogByNews[$cKey]['ITEMS'][$fields['ID']] = array(
                        'NAME' => $fields['NAME'],
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

    foreach ($catalogByNews as $news) {
        foreach ($news['ITEMS'] as $key => $ITEM) {
            $items[$key] = $ITEM;
        }
    }

    $APPLICATION->SetTitle('В каталоге товаров представлено товаров: ' . count($items));

    $arResult['NEWS'] = $catalogByNews;


    $this->IncludeComponentTemplate();
} else {
    $this->abortResultCache();
}





/*
if(!is_array($arParams["IBLOCKS"]))
	$arParams["IBLOCKS"] = array($arParams["IBLOCKS"]);

$arIBlockFilter = array();
foreach($arParams["IBLOCKS"] as $IBLOCK_ID)
{
	$IBLOCK_ID=intval($IBLOCK_ID);
	if($IBLOCK_ID>0)
		$arIBlockFilter[]=$IBLOCK_ID;
}

if(empty($arIBlockFilter))
{
	if(!CModule::IncludeModule("iblock"))
	{
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$rsIBlocks = CIBlock::GetList(array("sort" => "asc"), array(
		"type" => $arParams["IBLOCK_TYPE"],
		"LID" => SITE_ID,
		"ACTIVE" => "Y",
	));
	if($arIBlock = $rsIBlocks->Fetch())
		$arIBlockFilter[] = $arIBlock["ID"];
}

unset($arParams["IBLOCK_TYPE"]);
$arParams["PARENT_SECTION"] = intval($arParams["PARENT_SECTION"]);
$arParams["IBLOCKS"] = $arIBlockFilter;

if(!empty($arIBlockFilter) && $this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	//SELECT
	$arSelect = array(
		"ID",
		"IBLOCK_ID",
		"CODE",
		"IBLOCK_SECTION_ID",
		"NAME",
		"PREVIEW_PICTURE",
		"DETAIL_PICTURE",
		"DETAIL_PAGE_URL",
	);
	//WHERE
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCKS"],
		"ACTIVE_DATE" => "Y",
		"ACTIVE"=>"Y",
		"CHECK_PERMISSIONS"=>"Y",
	);
	if($arParams["PARENT_SECTION"]>0)
	{
		$arFilter["SECTION_ID"] = $arParams["PARENT_SECTION"];
		$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
	}
	//ORDER BY
	$arSort = array(
		"RAND"=>"ASC",
	);
	//EXECUTE
	$rsIBlockElement = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
	$rsIBlockElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	if($arResult = $rsIBlockElement->GetNext())
	{
		$arResult["PICTURE"] = CFile::GetFileArray($arResult["PREVIEW_PICTURE"]);
		if(!is_array($arResult["PICTURE"]))
			$arResult["PICTURE"] = CFile::GetFileArray($arResult["DETAIL_PICTURE"]);

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arResult["IBLOCK_ID"], $arResult["ID"]);
		$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

		if ($arResult["PICTURE"])
		{
			$arResult["PICTURE"]["ALT"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"];
			if ($arResult["PICTURE"]["ALT"] == "")
				$arResult["PICTURE"]["ALT"] = $arResult["NAME"];
			$arResult["PICTURE"]["TITLE"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"];
			if ($arResult["PICTURE"]["TITLE"] == "")
				$arResult["PICTURE"]["TITLE"] = $arResult["NAME"];
		}

		$this->SetResultCacheKeys(array(
		));
		$this->IncludeComponentTemplate();
	}
	else
	{
		$this->AbortResultCache();
	}
}*/
?>
