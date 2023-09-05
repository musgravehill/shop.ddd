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
use app\components\Shared\Domain\ValueObject\CountOnPage;
//
use app\components\Shared\Domain\ValueObject\PageNumber;
use app\components\User\App\Form\FormUserU;
use app\components\User\Domain\Contract\UserRepositoryInterface;
use app\components\User\Domain\Entity\User;
use app\components\User\Domain\ValueObject\Address;
use app\components\User\Domain\ValueObject\CityName;
use app\components\User\Domain\ValueObject\Phone;
use app\components\User\Domain\ValueObject\Username;
use app\components\UserCompany\App\Form\FormUserCompanyCru;
use app\components\UserCompany\Domain\Contract\UserCompanyRepositoryInterface;
use app\components\UserCompany\Domain\Entity\UserCompany;
use app\components\UserCompany\Domain\ValueObject\Bik;
use app\components\User\Domain\ValueObject\UserId;
use app\components\UserCompany\Domain\ValueObject\Inn;
use app\components\UserCompany\Domain\ValueObject\Kpp;
use app\components\UserCompany\Domain\ValueObject\Name;
use app\components\UserCompany\Domain\ValueObject\Rs;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private UserCompanyRepositoryInterface $userCompanyRepository;

    public function __construct(
        $id,
        $module,
        UserRepositoryInterface $userRepository,
        UserCompanyRepositoryInterface $userCompanyRepository,
        $config = []
    ) {
        $this->userRepository = $userRepository;
        $this->userCompanyRepository = $userCompanyRepository;
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
                        'actions' => ['profile', 'u', 'settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['list_adm'],
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

    public function actionSettings()
    {
        $user = $this->userRepository->getById(IdentityService::userId());
        if (is_null($user)) {
            throw new HttpException(404, 'Not found');
        }

        return $this->render('settings', [
            'user' => $user,
        ]);
    }

    public function actionProfile($id)
    {
        $userIdRaw = UserId::prepare($id);

        $targetUserId = UserId::fromString($userIdRaw);
        $visitorUserId = IdentityService::userId();

        if (!$targetUserId->isEqualsTo($visitorUserId) && !IdentityService::userIsAdmin()) {
            throw new HttpException(404, 'Not found');
        }

        $user = $this->userRepository->getById($targetUserId);

        if (is_null($user)) {
            throw new HttpException(404, 'Not found');
        }

        $userId = UserId::fromString($user->getId()->getId());
        $userCompany = $this->userCompanyRepository->getByUserId($userId);

        return $this->render('profile', [
            'user' => $user,
            'userCompany' => $userCompany,
        ]);
    }

    public function actionU($id)
    {
        $userIdRaw = UserId::prepare($id);

        $targetUserId = UserId::fromString($userIdRaw);
        $visitorUserId = IdentityService::userId();

        if (!$targetUserId->isEqualsTo($visitorUserId) && !IdentityService::userIsAdmin()) {
            throw new HttpException(404, 'Not found');
        }

        $user = $this->userRepository->getById($targetUserId);

        if (is_null($user)) {
            throw new HttpException(404, 'Not found');
        }

        $formUserUpdate = new FormUserU();
        $formUserUpdate->username = $user->getUsername()->getUsername();
        $formUserUpdate->email = $user->getEmail()->getEmail();
        $formUserUpdate->phone = $user->getPhone()->getPhone();
        $formUserUpdate->cityName = $user->getCityName()->getCityName();
        $formUserUpdate->address = $user->getAddress()->getAddress();

        if ($formUserUpdate->load(Yii::$app->request->post())) {
            if ($formUserUpdate->validate()) {

                $user = $user->changePersonalDataCustomer(
                    username: new Username($formUserUpdate->username),
                    phone: new Phone($formUserUpdate->phone),
                    cityName: new CityName($formUserUpdate->cityName),
                    address: new Address($formUserUpdate->address),
                );
                $user = $this->userRepository->save($user);

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['user/u', 'id' => $id,]));
            }
        }

        $userId = UserId::fromString($user->getId()->getId());
        $userCompany = $this->userCompanyRepository->getByUserId($userId);

        $formUserCompanyCru = new FormUserCompanyCru();
        if (!is_null($userCompany)) {
            $formUserCompanyCru->name = $userCompany->getName()->getName();
            $formUserCompanyCru->inn = $userCompany->getInn()->getInn();
            $formUserCompanyCru->kpp = $userCompany->getKpp()->getKpp();
            $formUserCompanyCru->rs = $userCompany->getRs()->getRs();
            $formUserCompanyCru->bik = $userCompany->getBik()->getBik();
        }
        if ($formUserCompanyCru->load(Yii::$app->request->post())) {
            if ($formUserCompanyCru->validate()) {

                $name = new Name($formUserCompanyCru->name);
                $inn = new Inn($formUserCompanyCru->inn);
                $kpp = new Kpp($formUserCompanyCru->kpp);
                $rs = new Rs($formUserCompanyCru->rs);
                $bik = new Bik($formUserCompanyCru->bik);

                if (is_null($userCompany)) {
                    $userCompany = UserCompany::new(
                        userId: $userId,
                        name: $name,
                        inn: $inn,
                        kpp: $kpp,
                        rs: $rs,
                        bik: $bik
                    );
                } else {
                    $userCompany = $userCompany->changeData(
                        name: $name,
                        inn: $inn,
                        kpp: $kpp,
                        rs: $rs,
                        bik: $bik
                    );
                }
                $this->userCompanyRepository->save($userCompany);

                Yii::$app->session->addFlash('success', 'Сохранено!');
                return $this->redirect(Url::to(['user/u', 'id' => $id,]));
            }
        }

        return $this->render('u', [
            'user' => $user,
            'formUserUpdate' => $formUserUpdate,
            'formUserCompanyCru' => $formUserCompanyCru,
        ]);
    }


    public function actionList_adm()
    {
        $pageRaw = PageNumber::prepare(HelperY::getGet('page', 1));

        $items = [];
        $page = new PageNumber($pageRaw);
        $cop = new CountOnPage(10);
        $users = $this->userRepository->list(page: $page, cop: $cop);
        foreach ($users as $user) {
            /** @var User $user */
            $userId = UserId::fromString($user->getId()->getId());
            $userCompany = $this->userCompanyRepository->getByUserId($userId);
            $items[] = [
                'user' => $user,
                'company' => $userCompany,
            ];
        }

        $urlParams = [];
        $urlParams[0] = 'user/list_adm'; //for URL create
        $urlParams['page'] = $pageRaw;

        return $this->render('list_adm', [
            'items' => $items,
            'urlParams' => $urlParams,
        ]);
    }
}
