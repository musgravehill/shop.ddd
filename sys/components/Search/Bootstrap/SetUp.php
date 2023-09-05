<?php

namespace app\components\Search\Bootstrap;

use app\components\Search\Domain\Contract\SearchProductInterface;
use app\components\Search\Infrastructure\SearchProductSphinx;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = Yii::$container;
        $container->set(SearchProductInterface::class, function ($container, $params, $config) {
            return new SearchProductSphinx;
        });
    }
}
 
//  $searchProduct = Yii::$container->get(\app\components\Search\Domain\Contract\SearchProductInterface::class);
