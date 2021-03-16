<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
	"dvi:click",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENT_ID" => $arParams["ELEMENT_ID"],
		"LOCATED_BASKET" => "Y"
	)
);