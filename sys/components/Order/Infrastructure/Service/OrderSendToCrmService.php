<?php

declare(strict_types=1);

namespace app\components\Order\Infrastructure\Service;

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use app\components\Order\App\DTO\OrderItemResponce;
use app\components\Order\Domain\ValueObject\OrderId;
use app\components\Order\Infrastructure\OrderQueryRepository;
use app\components\Order\Infrastructure\OrderQueryRepositoryInterface;

class OrderSendToCrmService
{
    private OrderQueryRepositoryInterface $orderReadRepository;

    public function __construct()
    {
        $this->orderReadRepository = new OrderQueryRepository;
    }

    public function sendToCrm(OrderId $orderId): void
    {
        $order = $this->orderReadRepository->getOrder($orderId);

        $info = '';
        $info .= 'Итого: ' . round($order->getPriceTotalFractionalCount() / 100, 2) . '<br>' . PHP_EOL;

        $infoDelivery = '';
        $infoDelivery .= 'Город: ' . $order->getDeliveryCityName() . '<br>' . PHP_EOL;
        $infoDelivery .= '' . $order->getDeliveryTypeId() . '<br>' . PHP_EOL;

        $info .= 'Заказ: <a href="' . Url::to(['order/view', 'id' => $order->getOrderId(),], true) . '">'
            . $order->getUserFriendlyOrderId()
            . '</a> <br>' . PHP_EOL;
        $info .= 'Клиент:  <a href="' . Url::to(['user/profile', 'id' => $order->getUserId(),], true) . '">';
        $info .=  $order->getCustomerName();
        $info .= '</a> ';
        $info .=  '<br>' . PHP_EOL;
        $info .= 'Комм: ' .  $order->getOrderComment() . '<br>' . PHP_EOL;

        $items = $this->orderReadRepository->getOrderItems($orderId);
        foreach ($items as $item) :
            /** @var OrderItemResponce $item */
            $href = Url::to(['product/view', 'id' => $item->getProductId(), 'ufu' => $item->getProductUfu()], true);
            $info .=  '<a href="' . $href . '">';
            $info .=  $item->getProductName();
            $info .=  '</a>';
            $info .=  '_' . $item->getQuantity() . 'шт.';
            $info .=  '_' . round($item->getPriceFinalFractionalCount() / 100, 2) . 'р.';
            $info .=  '<br>' . PHP_EOL;
        endforeach;

        $token = '4c79d5d69d35c9512db5e16a7df424d9';
        $requestArray = [];
        $requestArray['order_user_name'] =  $order->getCustomerName();
        $requestArray['order_user_phone'] =  $order->getCustomerPhone();
        $requestArray['order_user_email'] =  $order->getCustomerEmail();
        $requestArray['order_product_name'] = 'Корзина';
        $requestArray['order_user_comment'] = $info;
        $requestArray['order_user_address'] = $infoDelivery;
        $requestArray['order_product_count'] = 1;
        $requestArray['order_product_url'] = Url::to(['',], true);
        $requestArray['order_site_name'] = HelperY::params('domain');
        $requestArray['order_order_type'] = 'Корзина';
        $requestArray['sys_created_at'] = date('Y-m-d H:i:s');
        $requestArray['sys_visitor_id'] = 'nodata';

        ksort($requestArray);
        $q = '';
        foreach ($requestArray as $k => $v) {
            $q .= $k . '=' . $v;
        }
        $requestArray['sig'] = md5($q . $token);

        $ch = curl_init('http://sd.teamtimer.ru/api/v1/moduleorder/placeOrder');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestArray);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_exec($ch);
        curl_close($ch);
    }
}
