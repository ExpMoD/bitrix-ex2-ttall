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
    <? foreach ($arResult['ELEMENTS'] as $id => $user): ?>
    <li>[<?=$id?>] - <?=$user['LOGIN']?>
        <ul>
            <? foreach ($user['NEWS'] as $ITEM): ?>
                <li>
                    - <?=$ITEM['NAME']?>
                </li>
            <? endforeach; ?>
        </ul></li>
    <? endforeach; ?>

</ul>
<?
$frame->end();
?>