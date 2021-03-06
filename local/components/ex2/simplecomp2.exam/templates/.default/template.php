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
?>
    <b>Каталог:</b>
<ul>
    <? foreach ($arResult['CLASSES'] as $key => $CLASS): ?>
    <li><b><?=$CLASS['NAME']?></b>
        <ul>
            <? foreach ($CLASS['ITEMS'] as $ITEM): ?>
                <li>
                    <?=$ITEM['NAME']?>
                    <?=($ITEM['PRICE']) ? "- {$ITEM['PRICE']}" : ""?>
                    <?=($ITEM['MATERIAL']) ? "- {$ITEM['MATERIAL']}" : ""?>
                    <?=($ITEM['ARTNUMBER']) ? "- {$ITEM['ARTNUMBER']}" : ""?>
                    (<?=$ITEM['DETAIL_PAGE_URL']?>)
                </li>
            <? endforeach; ?>
        </ul></li>
    <? endforeach; ?>
</ul>

<?=$arResult['NAV_STRING']?>

<?
$frame->end();
?>