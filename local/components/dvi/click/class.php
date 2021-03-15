<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
 
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Sale;
use Bitrix\Main\Engine\Controller;

use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;
	
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
		global $USER;
		// ID пользователя
		$userId = $USER->isAuthorized() ? $USER->GetID() : 1;

		//Формирование продукта
		$product = array(
			"PRODUCT_ID" => $id,
			"QUANTITY" => $count,
			"PRODUCT_PROVIDER_CLASS" => "\Bitrix\Catalog\Product\CatalogProvider",
		);

		// Создание корзины
		$basket = Bitrix\Sale\Basket::create(SITE_ID);

		// Заполнение корзины товарам
		$basketItem = $basket->createItem("catalog", $product["PRODUCT_ID"]);
		$basketItem->setFields($product);

		
		// Создание нового заказа
		$order = Bitrix\Sale\Order::create(SITE_ID, $userId);
		// Устанавливаем ID типа плательщика для заказа
		$order->setPersonTypeId(1);
		// Прикрепляем корзину к новому заказу и актуализирует ее
		$order->setBasket($basket);

		// Возвращаем коллекцию отгрузок, привязанных к заказу
		$shipmentCollection = $order->getShipmentCollection();
		// Добавляем отгрузку
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

		$propertyCollection = $order->getPropertyCollection();
		$phoneProp = $propertyCollection->getPhone();
		$phoneProp->setValue($phone);

		$result = $order->save();
    	if (!$result->isSuccess())
        {
            $result->getErrors();
        }

		return $result;
	}
	
	public function basketAction($phone = "")
	{
		global $USER;
		// ID пользователя
		$userId = $USER->isAuthorized() ? $USER->GetID() : 1;

		// Загружаем корзину пользователя
		$basket = Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), SITE_ID);

		// Создание нового заказа
		$order = Bitrix\Sale\Order::create(SITE_ID, $userId);
		// Устанавливаем ID типа плательщика для заказа
		$order->setPersonTypeId(1);
		// Прикрепляем корзину к новому заказу и актуализирует ее
		$order->setBasket($basket);

		// Возвращаем коллекцию отгрузок, привязанных к заказу
		$shipmentCollection = $order->getShipmentCollection();
		// Добавляем отгрузку
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
		
		$propertyCollection = $order->getPropertyCollection();
		$phoneProp = $propertyCollection->getPhone();
		$phoneProp->setValue($phone);
		
		$result = $order->save();
		if (!$result->isSuccess())
		{
			$result->getErrors();
		}

		return $arResult;
	}
}