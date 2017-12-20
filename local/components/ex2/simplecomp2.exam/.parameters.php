<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "IBLOCK_CATALOG" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_CATALOG"),
            "TYPE" => "STRING",
        ),
        "IBLOCK_CLASSIFIER" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_CLASSIFIER"),
            "TYPE" => "STRING",
        ),
        "TEMPLATE_DETAIL_URL" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("TEMPLATE_DETAIL_URL"),
            "TYPE" => "STRING",
        ),
        "ELEMENTS_PER_PAGE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ELEMENTS_PER_PAGE"),
            "TYPE" => "STRING",
            "DEFAULT" => "2"
        ),
        "PROPERTY_CODE_CLASSIFIER" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("PROPERTY_CODE_CLASSIFIER"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>180),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BPR_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
    ),
)
?>
