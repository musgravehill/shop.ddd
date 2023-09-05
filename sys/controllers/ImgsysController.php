<?php

namespace app\controllers;

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
use app\components\Imgsys\App\Form\FormImgUpload;
//
use app\components\Imgsys\Domain\Contract\ImgsysRepositoryInterface;
use app\components\Imgsys\Domain\Entity\Imgsys;
use app\components\Imgsys\Domain\ValueObject\ImgsysId;
use app\components\Imgsys\Domain\ValueObject\ImgsysTags;
use app\components\Imgsys\Infrastructure\HelperImgsys;
use app\components\Shared\Domain\ValueObject\CountOnPage;
use app\components\Shared\Domain\ValueObject\PageNumber;
use Exception;
use yii\web\UploadedFile;

class ImgsysController extends Controller
{
    private ImgsysRepositoryInterface $imgsysRepository;

    public function __construct(
        $id,
        $module,
        ImgsysRepositoryInterface $imgsysRepository,
        $config = []
    ) {
        $this->imgsysRepository = $imgsysRepository;
        parent::__construct($id, $module, $config);
    }

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
                        'actions' => ['list_adm', 'settags'],
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

    public function actionSettags()
    {
        $imgsysIdRaw = ImgsysId::prepare(HelperY::getPost('id', null));        
        $tagsRaw = ImgsysTags::prepare(HelperY::getPost('tags', ''));

        $imgsys = $this->imgsysRepository->getById(ImgsysId::fromString($imgsysIdRaw));
        if (is_null($imgsys)) {
            throw new HttpException(404, 'Not found');
        }

        $imgsys = $imgsys->changeImgsys(
            tags: new ImgsysTags($tagsRaw)
        );
        $imgsys = $this->imgsysRepository->save($imgsys);

        return $this->asJson(1);
    }

    public function actionList_adm()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));
        $tagsRaw = ImgsysTags::prepare(HelperY::getGet('tags', ''));

        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(20);
        $tags = new ImgsysTags($tagsRaw);
        $imgsyss = $this->imgsysRepository->list(
            page: $page,
            cop: $cop,
            tags: $tags
        );

        $urlParams = [];
        $urlParams[0] = 'imgsys/list_adm'; //for URL create
        $urlParams['page'] =  $page->getPageNumber();
        $urlParams['tags'] = $tags->getTags();

        $formImgUpload = new FormImgUpload();
        if ($formImgUpload->load(Yii::$app->request->post())) {
            $formImgUpload->imageFiles = UploadedFile::getInstances($formImgUpload, 'imageFiles');
            if ($formImgUpload->validate()) {
                foreach ($formImgUpload->imageFiles as $file) {
                    $imgsys = Imgsys::newImgsys(
                        tags: new ImgsysTags($formImgUpload->tags)
                    );
                    $imgsys = $this->imgsysRepository->save($imgsys);
                    if (is_null($imgsys)) {
                        throw new Exception('Save:error.');
                    }

                    $pathFull = HelperImgsys::getPathFull($imgsys->getId());
                    $file->saveAs($pathFull);
                }

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['imgsys/list_adm']));
            }
        }

        $imgsysIdDel = HelperY::getPost('imgsysIdDel', null);
        if (!is_null($imgsysIdDel)) {
            $id = ImgsysId::fromString(ImgsysId::prepare($imgsysIdDel));
            HelperImgsys::delete($id);
            $this->imgsysRepository->delete($id);
            return $this->redirect(Url::to(['imgsys/list_adm']));
        }

        return $this->render('list_adm', [
            'formImgUpload' => $formImgUpload,
            'imgs' => $imgsyss,
            'urlParams' => $urlParams,
        ]);
    }

    public function actionTmp()
    {

        /*
        $iss = Yii::$app->db->createCommand("
        SELECT
        img_sys1.*              
        FROM  {{img_sys1}}       
        WHERE brandcatId=0                    
        ")->queryAll();

        foreach ($iss as $is) {
            $imgsys = Imgsys::newImgsys(
                tags: new ImgsysTags($is['tags'])
            );
            $imgsys = $this->imgsysRepository->save($imgsys);
            if (is_null($imgsys)) {
                echo $is['id'] . '<br>' . PHP_EOL;
            } else {
                $name = $is['name'];
                $imgPathInit = Yii::getAlias('@webroot' . '/imgsys/bu/' . $name[0] . '/' . $name . '.jpg');
                $imgPathFinal = HelperImgsys::getPathFull($imgsys->getId()->getId());

                try {
                    copy($imgPathInit, $imgPathFinal);
                } catch (\Throwable $th) {
                    echo $imgPathInit . '<br>' . PHP_EOL;
                }
            }
        }
        */

        /*
        $bcs = Yii::$app->db->createCommand("
        SELECT
            brand_category.*              
        FROM  {{brand_category}}                           
        ")
            ->queryAll();

        foreach ($bcs as $bc) {
            
            $tmp = explode('/', $bc['img_url']);
            $name = str_replace('.jpg', '', $tmp[3]);

            
            Yii::$app->db->createCommand()->update(
                'img_sys1',
                [
                    'brandcatId' =>  $bc['id']
                ],
                " name = '$name' "
            )->execute();
             
            $imgPathInit = Yii::getAlias('@webroot' . '/imgsys/bu/' . $name[0] . '/' . $name . '.jpg');
            $imgPathFinal = Yii::getAlias('@webroot' . '/imgbrandcat' . '/' . $bc['id'] . '.jpg');
            try {
                copy($imgPathInit, $imgPathFinal);
            } catch (\Throwable $th) {
                echo $imgPathInit . '<br>' . PHP_EOL;
            }            
        }
        */
    }
}
