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
        "IBLOCK_NEWS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_NEWS"),
            "TYPE" => "STRING",
        ),
        "PROPERTY_CODE_NEWS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("PROPERTY_CODE_NEWS"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>180),
    ),
)
?>
