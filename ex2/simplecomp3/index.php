<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент 3");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp3.exam", 
	".default", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "180",
		"CACHE_TYPE" => "A",
		"IBLOCK_CATALOG" => "",
		"IBLOCK_CLASSIFIER" => "",
		"PROPERTY_CODE_CLASSIFIER" => "",
		"TEMPLATE_DETAIL_URL" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_NEWS" => "1",
		"PROPERTY_CODE_NEWS_AUTHOR" => "PROPERTY_AUTHOR",
		"USER_PROPERTY_CODE_AUTHOR" => "UF_AUTHOR_TYPE"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>