<?php

class PageController extends Controller
{
    public $layout='/layouts/page';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
           // 'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform actions
                'actions'=>array('index', 'create', 'update', 'delete', 'move', 'getchildren'),
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
            $model->prev_id = $_POST["Page"]["prev_id"];
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
            $model->prev_id = $_POST["Page"]["prev_id"];
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
        $command->delete(Yii::app()->db->tablePrefix . 'page', 'parents LIKE "%/' . $id . '/%" OR id=:id', array(':id'=>$id));
        $this->redirect(array('index'));

    }

    public function actionGetchildren($pid){//работает через ajax (необходима для определения положения страницы)
        $criteria = new  CDbCriteria;
        $criteria->select = "id, title";
        $criteria->condition = "pid={$pid}";
        $criteria->order = "position";
        $pages = Page::model()->findAll($criteria);
        $pages_array = array();
        foreach($pages as $page){
            $pages_array[$page->id] = array(
                "id" => $page->id,
                "title" => $page->title
            );
        }
        //необходимо для обработки в js
        array_unshift($pages_array, array("id"=>0, "title"=>"Начало"));
        $pages_array[] = array();
        //print_r($pages_array);
        echo json_encode($pages_array);
        exit;
    }

    /*public function actionMove(){
        $errors = array();
        $model=Page::model()->findByPk((int)$_POST["id"]);
        if($model === null){
            $errors["id"] = "Нет записи с id={$_POST['id']}";
        }
        if(empty($errors)){
            $model->move($_POST, $errors);
        }
        if(empty($errors)){
            $model->pid = (int)$_POST["parent_id"];
            if(!$model->save()){
                $errors["save"] = "Не удалось перенести модуль";
            }
        }
        $result = array();
        if(empty($errors)){
            $result["result"] = "OK";
        }else{
            $result["result"] = "error";
            $result["errors"] = $errors;
        }
        echo json_encode($result);
        exit;
    }*/

    public function loadModel($id)
    {
        $model=Page::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Нет страницы с таким id.');
        return $model;
    }

}