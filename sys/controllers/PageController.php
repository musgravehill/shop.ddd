<?php

namespace app\controllers;

use app\components\HelperCache;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\IAA\Authorization\AccessRule;
use app\components\IAA\Authentication\Model\ValueObject\RoleTypeId;
use app\components\IAA\Authentication\Service\IdentityService;
//
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\models\PageHelper;

//

class PageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'actions' => ['view', 'showcase', 'list', 'find',],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['cru', 'list_adm'],
                        'allow' => true,
                        'roles' => [RoleTypeId::Admin],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [ //for config ErrorHandler 'site/error'
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 3,
                'maxLength' => 4,
                'offset' => 4,
            ],
        ];
    }

    public function actionShowcase()
    {
        $cacheId = HelperCache::getCacheKey(HelperCache::PAGE_SHOWCASE, []);
        $data = Yii::$app->cache->get($cacheId);

        if ($data === false) {
            $page = new PageNumber(1);
            $cop = new CountOnPage(12);
            $pages = PageHelper::getItems($page, $cop);
            $data = '';
            $indexNumber = 0;
            foreach ($pages as $page) {
                $data .= $this->renderPartial(
                    '/page/_item',
                    [
                        'page' => $page,
                        'indexNumber' => $indexNumber,
                        'isLimitMobile' => true,
                    ]
                );
                $indexNumber++;
            }

            Yii::$app->cache->set($cacheId, $data, 3600);
        }

        return $data;
    }

    public function actionList_adm()
    {
        $pages = \app\models\Page::find()->orderby(['changedAt' => SORT_DESC, 'id' => SORT_DESC])->all();
        return $this->render('list_adm', [
            'pages' => $pages,
        ]);
    }

    public function actionList()
    {
        $paramsSeo = [
            'page' => 1,
            'countOnPage' => 12,
        ];
        $searchPages__url = $paramsSeo;
        $searchPages__url[0] = 'page/find'; //for URL create

        return $this->render('list', [
            'searchPages__url' => $searchPages__url,
        ]);
    }

    public function actionCru($id)
    {
        $page = false;
        if ($id) {
            $page = \app\models\Page::find()->where(['id' => $id,])->limit(1)->one();
            if (!$page) {
                throw new HttpException(404, 'Not found');
            }
        }

        if (Yii::$app->request->post()) {
            if (!$page) {
                $page = new \app\models\Page();
            }

            $page->title = (string) HelperY::sanitizeText(HelperY::getPost('title', '-'), 255, false) ?: '-';
            //$page->txt = (string) HelperY::sanitizeHtml(HelperY::getPost('txt', '-'), 65000, false) ?: '-';
            $page->txt = (string) HelperY::getPost('txt', '-');
            $page->txt = str_replace(array('<br><br><br>', '<br><br>'), '<br>', $page->txt);
            $page->txt = str_replace(array('\r\n', "\r\n"), ' ', $page->txt);
            $page->txt = str_replace(array('<?', "?>"), '', $page->txt);

            $page->seoKey = (string) HelperY::sanitizeHtml(HelperY::getPost('seoKey', '-'), 255, false) ?: '-';
            $page->seoDesc = (string) HelperY::sanitizeHtml(HelperY::getPost('seoDesc', '-'), 255, false) ?: '-';

            $page->imgUrl1 = (string) HelperY::getRelativeUrl(HelperY::sanitizeUrl(HelperY::getPost('imgUrl1', '-'))) ?: '-';
            $page->imgAlt1 = (string) HelperY::sanitizeText(HelperY::getPost('imgAlt1', '-'), 255) ?: '-';
            $page->imgUrl2 = (string) HelperY::getRelativeUrl(HelperY::sanitizeUrl(HelperY::getPost('imgUrl2', '-'))) ?: '-';
            $page->imgAlt2 = (string) HelperY::sanitizeText(HelperY::getPost('imgAlt2', '-'), 255) ?: '-';
            $page->imgUrl3 = (string) HelperY::getRelativeUrl(HelperY::sanitizeUrl(HelperY::getPost('imgUrl3', '-'))) ?: '-';
            $page->imgAlt3 = (string) HelperY::sanitizeText(HelperY::getPost('imgAlt3', '-'), 255) ?: '-';
            $page->changedAt = time();

            if ($page->save()) {
                Yii::$app->session->addFlash('success', 'Готово!');
            } else {
                echo '==' . $page->imgAlt3 . '==';
                print_r($page->errors);
                Yii::$app->session->addFlash('danger', 'Сохранить не получается!');
            }
        }
        return $this->render('cru', [
            'page' => $page,
        ]);
    }

    public function actionView($id)
    {
        $page = \app\models\Page::find()->where(['id' => $id,])->limit(1)->one();
        if (!$page) {
            throw new HttpException(404, 'Not found');
        }
        return $this->render('view', [
            'page' => $page,
        ]);
    }

    // API 
    public function actionFind()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $countOnPageRaw = CountOnPage::prepare(HelperY::getGet('countOnPage', 12));

        $page = new PageNumber($pageRaw);
        $countOnPage = new CountOnPage($countOnPageRaw);

        $html = '';
        $pages = PageHelper::getItems($page, $countOnPage);
        foreach ($pages as $page) {
            $html .= $this->renderPartial(
                '/page/_item',
                [
                    'page' => $page,
                    'indexNumber' => 0,
                    'isLimitMobile' => false,
                ]
            );
        }
        return $html;
    }
}
