<?php

class SettingController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index', 'clearcache'),
                'roles'=>array('admin'),
            ),
            array("deny",
                "users" => array("*")
            )
        );
    }
    public function actionIndex()
    {
        $model=Setting::model()->findByPk(1);
        if(isset($_POST['Setting']))
        {
            $model->attributes=$_POST['Setting'];
            if($model->save()){
                Yii::app()->user->setFlash('setting','Настройки успешно сохранились.');
            }

        }
        $this->render('index',array(
            'model'=>$model,
        ));
    }

    public function actionClearcache()
    {
        $model=Setting::model()->findByPk(1);
//        echo "<pre>"; print_r($_POST); echo "</pre>";
        if(isset($_POST))
        {
            if(Yii::app()->cache->flush()){
                Yii::app()->user->setFlash('setting','Cache успешно очищена.');
            }else{
                Yii::app()->user->setFlash('setting','Не удалось очистить cache.');
            }
        }
        $this->redirect(array('index'));
    }
}