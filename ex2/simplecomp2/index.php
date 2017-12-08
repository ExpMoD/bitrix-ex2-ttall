<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp2.exam", 
	".default", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "180",
		"CACHE_TYPE" => "A",
		"IBLOCK_CATALOG" => "2",
		"IBLOCK_CLASSIFIER" => "7",
		"PROPERTY_CODE_CLASSIFIER" => "PROPERTY_FIRM",
		"TEMPLATE_DETAIL_URL" => "/products/#SECTION_ID#/#ELEMENT_ID#/",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>