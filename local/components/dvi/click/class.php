<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
 
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
use Bitrix\Sale;
use Bitrix\Main\Engine\Controller;
	
Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");

class CCustomAjax extends CBitrixComponent implements Controllerable
{
	public function configureActions()
	{
		return [];
	}
 
	function executeComponent()
	{
		$this->includeComponentTemplate();
	}
 

	public function productAction($id = "", $count = "", $phone = "")
	{
		/*$products = array(
			array('PRODUCT_ID' => 1811, 'NAME' => 'Товар 1', 'PRICE' => 500, 'CURRENCY' => 'RUB', 'QUANTITY' => 5, 'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',)
		);

		$basket = Bitrix\Sale\Basket::create(SITE_ID);

		foreach ($products as $product)
		{
			$item = $basket->createItem("catalog", $product["PRODUCT_ID"]);
			unset($product["PRODUCT_ID"]);
			$item->setFields($product);
		}

		$order = Bitrix\Sale\Order::create(SITE_ID, 1);
		$order->setPersonTypeId(1);
		$order->setBasket($basket);

		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem(
			Bitrix\Sale\Delivery\Services\Manager::getObjectById(1)
		);
		
		$shipmentItemCollection = $shipment->getShipmentItemCollection();

		foreach ($basket as $basketItem)
		{
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		}

		$paymentCollection = $order->getPaymentCollection();
		$payment = $paymentCollection->createItem(
			Bitrix\Sale\PaySystem\Manager::getObjectById(1)
		);

		$payment->setField("SUM", $order->getPrice());
		$payment->setField("CURRENCY", $order->getCurrency());

		$result = $order->save();
    	if (!$result->isSuccess())
        {
            //$result->getErrors();
        }*/

		return [
			"id" => $id,
			"count" => $count,
			"phone" => $phone
		];
	}
	
	public function basketAction($phone = "")
	{
		return [
			"phone" => $phone
		];
	}
}