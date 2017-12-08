<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "IBLOCK_NEWS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_NEWS"),
            "TYPE" => "STRING",
        ),
        "PROPERTY_CODE_NEWS_AUTHOR" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("PROPERTY_CODE_NEWS_AUTHOR"),
            "TYPE" => "STRING",
        ),
        "USER_PROPERTY_CODE_AUTHOR" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("USER_PROPERTY_CODE_AUTHOR"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>180),
    ),
)
?>
