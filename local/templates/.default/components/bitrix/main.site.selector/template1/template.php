<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    <select onchange="document.location.href = this.value;">
<?foreach ($arResult["SITES"] as $key => $arSite):?>

	<?if ($arSite["CURRENT"] == "Y"):?>
        <option value="<?=$arSite['DIR']?>" selected><?=$arSite['LANG']?></option>
	<?else:?>
        <option value="<?=$arSite['DIR']?>"><?=$arSite['LANG']?></option>
	<?endif?>

<?endforeach;?>
    </select>
