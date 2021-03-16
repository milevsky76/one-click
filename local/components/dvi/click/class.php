<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
 
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use Bitrix\Sale\Delivery\Services;
use Bitrix\Sale\PaySystem;
	
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
 
	private function productAction($prodId, $count)
	{
		//Формирование продукта
		$product = array(
			"PRODUCT_ID" => $prodId,
			"QUANTITY" => $count,
			"PRODUCT_PROVIDER_CLASS" => "\Bitrix\Catalog\Product\CatalogProvider",
		);

		// Создание корзины
		$basket = Basket::create(SITE_ID);

		// Заполнение корзины товарам
		$basketItem = $basket->createItem("catalog", $product["PRODUCT_ID"]);
		$basketItem->setFields($product);

		return $basket;
	}
	
	private function basketAction($userId)
	{
		// Загружаем корзину пользователя
		$basket = Basket::loadItemsForFUser($userId, SITE_ID);

		return $basket;
	}


	public function mainAction($prodId = "", $count = "", $phone = "", $type = "N")
	{
		// Получение ID покупателя
		$userId = Fuser::getId();

		// Проверка расположения компонента
		if ($type !== "Y") {
			$basket = $this->productAction($prodId, $count);
		} else {
			$basket = $this->basketAction($userId);
		}
				
		// Создание нового заказа
		$order = Order::create(SITE_ID, $userId);
		// Устанавливаем ID типа плательщика для заказа
		$order->setPersonTypeId(1);
		// Прикрепляем корзину к новому заказу и актуализирует ее
		$order->setBasket($basket);

		// Возвращаем новую коллекцию отгрузок
		$shipmentCollection = $order->getShipmentCollection();
		// Создаём отгрузку, передавая службу доставки
		$shipment = $shipmentCollection->createItem(
			Services\Manager::getObjectById(1)
		);

		// Возвращаем состав отгрузки
		$shipmentItemCollection = $shipment->getShipmentItemCollection();
		foreach ($basket as $basketItem)
		{
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		}

		// Возвращаем коллекцию оплат
		$paymentCollection = $order->getPaymentCollection();
		// Создаём оплату
		$payment = $paymentCollection->createItem(
			PaySystem\Manager::getObjectById(1)
		);

		// Настраиваем свойства оплаты
		$payment->setField("SUM", $order->getPrice());
		$payment->setField("CURRENCY", $order->getCurrency());

		// Получаем коллекцию свойст
		$propertyCollection = $order->getPropertyCollection();

		// Задаём знечение телефона
		$phoneProp = $propertyCollection->getPhone();
		$phoneProp->setValue($phone);

		// Сохраняем заказ
		$result = $order->save();
		if (!$result->isSuccess())
		{
			$result->getErrors();
		}

		return $result;
	}
}