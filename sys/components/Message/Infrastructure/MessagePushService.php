<?php

declare(strict_types=1);

namespace app\components\Message\Infrastructure;

use app\components\HelperY;
use app\components\Message\Domain\Entity\Message;
use app\components\Message\Domain\ValueObject\MessageId;
use InvalidArgumentException;
use Yii;
use yii\helpers\Url;

class MessagePushService
{
    public function pushToCrm(Message $message): Message
    {
        $token = '4c79d5d69d35c9512db5e16a7df424d9';
        $requestArray = [];
        $requestArray['order_user_name'] =  $message->getUsername()->getUsername();
        $requestArray['order_user_phone'] =  $message->getPhone()->getPhone();
        $requestArray['order_user_email'] =  $message->getEmail()->getEmail();
        $requestArray['order_product_name'] = 'Сообщение.';
        $requestArray['order_user_comment'] = $message->getTxt()->getTxt();
        $requestArray['order_user_address'] = '';
        $requestArray['order_product_count'] = 1;
        $requestArray['order_product_url'] = $message->getUrl()->getUrl(); 
        $requestArray['order_site_name'] = HelperY::params('domain');
        $requestArray['order_order_type'] = 'Сообщение';
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
        $raw = curl_exec($ch);
        curl_close($ch);

        try {
            $res = json_decode($raw, true);
            $idRaw = strval(intval($res['order_id']));
            $id = MessageId::fromString($idRaw);
            $message = $message->appendId($id);
        } catch (\Throwable $th) {
        }

        return $message;
    }
}
