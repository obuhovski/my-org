<?php

namespace app\controllers;

use app\models\forms\ImportDataOrgForm;
use app\models\User;
use Yii;
use app\models\Organization;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * OrganizationController implements the CRUD actions for Organization model.
 */
class OrganizationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $importDataOrgForm = new ImportDataOrgForm();

        

        $dataProvider = new ActiveDataProvider([
            'query' => Organization::find(),
        ]);



        if (Yii::$app->request->isPost) {
            $importDataOrgForm->file = UploadedFile::getInstance($importDataOrgForm, 'file');
            $filePath = $importDataOrgForm->upload();
            if ($filePath !== null) {
                if (file_exists($filePath)) {
                    if ($importDataOrgForm->parseXmlFileAndSaveData($filePath)) {
                        $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('error', 'Ошибка: Некорректный xml-файл');
                    }
                } else {
                    Yii::error('Файл '.$filePath.' не существует');
                    Yii::$app->session->setFlash('error', 'Произошла ошибка. Попробуйте позже');
                }
            }
        }

        return $this->render('index', compact('dataProvider', 'importDataOrgForm'));
    }

    /**
     * Displays a single Organization model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user = new User();
        if (Yii::$app->request->isAjax && $user->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        if (Yii::$app->request->isPost && $user->load(Yii::$app->request->post())) {
            $user->org_id = $id;
            if ($user->save()) {
                $this->refresh();
            }
        }

            return $this->render('view', [
            'model' => $this->findModel($id),
            'user' => $user
        ]);
    }


    /**
     * Finds the Organization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organization::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
