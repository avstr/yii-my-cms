<?php

class NewsController extends Controller
{
	//картинки для новостей лежат images/news/year/id.extention
    /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column2';

	/**
	 * @return array action filters
	 */
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
				'actions'=>array('index','view', 'update', 'create', 'delete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCreate()
    {
        $model=new News;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $model->date = date("Y-m-d");
        if(isset($_POST['News'])){
            $model->attributes=$_POST['News'];
            //преобразуем дату в нужный формат
            $model->date = date("Y-m-d", strtotime($model->date));
            //Полю image присвоить значения поля формы image
            $model->image=CUploadedFile::getInstance($model,'image');
            if(!$model->save()){
                $this->render('create',array(
                    'model'=>$model,
                ));

            }
            //Если поле загрузки файла не было пустым, то
            if ($model->image){
                //сохранить файл на сервере в каталог images/2011 под именем
                //month-day-alias.jpg
                $sourcePath = pathinfo($model->image->getName());
                $fileName = $model->id.'.'.$sourcePath['extension'];
                $model->picture = $fileName;
                echo "<pre>"; print_r($model); echo "</pre>";
                if(!$model->save()){
                    $this->render('update',array(
                        'model'=>$model,
                    ));

                }
                //создаем необходимые папки если они не существуют
                if(!is_dir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/small')){
                    mkdir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/small', 0777, true);
                }
                $file = $_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/'.$fileName;
                $setting = Setting::model()->findByPk(1);
                $sizeSideNewsPicture = $setting->sizeSideNewsPicture;
                $sizeSideSmallNewsPicture = $setting->sizeSideSmallNewsPicture;
                $model->image->saveAs($file);
                //Используем функции расширения CImageHandler;
                $ih = new CImageHandler(); //Инициализация
				Yii::app()->ih
                        ->load($file) //Загрузка оригинала картинки
                        ->thumb($sizeSideNewsPicture, false) //Создание превьюшки шириной $sizeSideNewsPicture
                        ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/'.$fileName) //Сохранение превьюшки в папку thumbs
                        ->reload() //Снова загрузка оригинала картинки
                        ->thumb($sizeSideSmallNewsPicture, $sizeSideSmallNewsPicture) //Создание превьюшки размером $sizeSideSmallNewsPicture
                        ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/small/'.$fileName); //Сохранение превьюшки в папку thumbs_small

            }
            $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['News']))
        {
            $model->attributes=$_POST['News'];
            $model->date = date("Y-m-d", strtotime($model->date));
            //Если отмечен чекбокс «удалить файл»
            if($model->del_img)
            {
                if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/'.$model->picture))
                {
                    //удаляем файл
                    unlink('./images/news/'.substr($model->date, 0, 4).'/'.$model->picture);
                    unlink('./images/news/'.substr($model->date, 0, 4).'/small/'.$model->picture);
                    $model->picture = '';
                }
            }

            $model->image=CUploadedFile::getInstance($model,'image');
            if ($model->image){
                $sourcePath = pathinfo($model->image->getName());
                $fileName = $model->id.'.'.$sourcePath['extension'];
                $model->picture = $fileName;
            }
            //echo "<pre>"; print_r($model); echo "</pre>";
            //Если поле загрузки файла не было пустым, то
            if ($model->image){
                //создаем необходимые папки если они не существуют
                if(!is_dir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/small')){
                    mkdir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/small', 0777, true);
                }
                $file = './images/news/'.substr($model->date, 0, 4).'/'.$fileName;
                //сохранить файл на сервере под именем
                //month-day-alias.jpg Если файл с таким именем существует, он будет заменен.
                $model->image->saveAs($file);
                Yii::app()->ih
                    ->load($file) //Загрузка оригинала картинки
                    ->thumb($sizeSideNewsPicture, false) //Создание превьюшки шириной $sizeSideNewsPicture
                    ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/'.$fileName) //Сохранение превьюшки в папку thumbs
                    ->reload() //Снова загрузка оригинала картинки
                    ->thumb($sizeSideSmallNewsPicture, $sizeSideSmallNewsPicture) //Создание превьюшки размером $sizeSideSmallNewsPicture
                    ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr($model->date, 0, 4).'/small/'.$fileName); //Сохранение превьюшки в папку thumbs_small
            }
            if($model->save()){

                $this->redirect(array('view','id'=>$model->id));
            }
        }
		$this->render('update',array(
            'model'=>$model,
        ));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new News('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['News']))
			$model->attributes=$_GET['News'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return News the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=News::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param News $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='news-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
