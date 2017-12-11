<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$frame = $this->createFrame()->begin('');

$url = $APPLICATION->GetCurPage() . "?F=Y";
?>
Фильтр: <a href="<?=$url?>"><?=$url?></a>
    <br>
    ---
    <br>
    <br>
    <b>Каталог:</b>
<ul>
    <? foreach ($arResult['NEWS'] as $NEWS): ?>
    <li><b><?=$NEWS['NAME']?></b> - <?=$NEWS['ACTIVE_FROM']?> (<?=implode(', ', $NEWS['SECTIONS'])?>)
        <ul>
            <? foreach ($NEWS['ITEMS'] as $kItem => $ITEM): ?>
                <?
                $this->AddEditAction($kItem, $ITEM['EDIT_LINK'], CIBlock::GetArrayByID($ITEM["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($kItem, $ITEM['DELETE_LINK'], CIBlock::GetArrayByID($ITEM["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <li id="<?=$this->GetEditAreaId($kItem);?>">
                    <?=$ITEM['NAME']?>
                    <?=($ITEM['PRICE']) ? "- {$ITEM['PRICE']}" : ""?>
                    <?=($ITEM['MATERIAL']) ? "- {$ITEM['MATERIAL']}" : ""?>
                    <?=($ITEM['ARTNUMBER']) ? "- {$ITEM['ARTNUMBER']}" : ""?>
                </li>
            <? endforeach; ?>
        </ul></li>
    <? endforeach; ?>

</ul>
<?
$frame->end();
?>