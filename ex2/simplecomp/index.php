<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp.exam",
	"",
	Array(
		"CACHE_TIME" => "180",
		"CACHE_TYPE" => "A",
		"IBLOCK_CATALOG" => "2",
		"IBLOCK_NEWS" => "1",
		"PROPERTY_CODE_NEWS" => "UF_NEWS_LINK"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>