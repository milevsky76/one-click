<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();?>

<div id="click-one">
	<input id="click-btn" type="button" value="Купить в 1 клик">

	<form id="click-form" class="view-n" action="">
		<input id="click-phone" type="tel" name="phone" placeholder="Введите ваш номер телефона" required>
		
		<input id="click-data" type="hidden" name="data" data-id="<?=$arParams["ELEMENT_ID"]?>" data-basket="<?=$arParams["LOCATED_BASKET"]?>">
		
		<p><input id="click-buy" type="submit" value="Оформить заказ"></p>
	</form>
</div>

<div id="answer"></div>