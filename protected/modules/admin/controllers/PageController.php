<?php

class PageController extends Controller
{
    public $layout='/layouts/page';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index', 'create', 'update', 'delete'),
                'roles'=>array('admin'),
            ),
            array("deny",
                "users" => array("*")
            )
        );
    }

    public function actionIndex()
	{
		//echo "<pre>"; print_r(Yii::app()->user); echo "</pre>";
        $this->render('index');
	}

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($pid = 0)
    {
        $model=new Page;

        //инициализируем атрибуры
        $model->type = 'static';
        $model->pid = $pid;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Page']))
        {
            $model->attributes=$_POST['Page'];
            if($model->save())
                $this->redirect(array('index','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Page']))
        {

            $model->attributes=$_POST['Page'];
            if($model->save())
                $this->redirect(array('index','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        $command = Yii::app()->db->createCommand();
        $command->delete('cms_page', 'parents LIKE ":id" OR id=:id', array(':id'=>$id));
        $this->redirect(array('index'));

    }

    public function loadModel($id)
    {
        $model=Page::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Нет страницы с таким id.');
        return $model;
    }

}