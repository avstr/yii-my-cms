<?php

class ServiceController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/service';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
            array('allow',  // allow all users to perform actions
                'actions'=>array('index','view', 'update', 'create', 'delete', 'delete1'),
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
    public function actionCreate($pid = 0)
    {
        $model=new Service;

        //инициализируем атрибуры
        $model->pid = $pid;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Service']))
        {
            $model->attributes=$_POST['Service'];
            $model->image=CUploadedFile::getInstance($model,'image');
            $model->prev_id = $_POST["Service"]["prev_id"];
            if(!$model->save()){
                $this->render('create',array(
                    'model'=>$model,
                ));
            }
            //Если поле загрузки файла не было пустым, то
            if ($model->image){
                //сохранить файл на сервере в каталог images/service/ под именем id.jpg
                $sourcePath = pathinfo($model->image->getName());
                $fileName = $model->id.'.'.$sourcePath['extension'];
                $model->picture = $fileName;
                if(!$model->save()){
                    $this->render('update',array(
                        'model'=>$model,
                    ));
                }

                //создаем необходимые папки если они не существуют
                if(!is_dir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small')){
                    mkdir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small', 0777, true);
                }
                $file = $_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small/'.$fileName;
                $setting = Setting::model()->findByPk(1);
                $widthServicePicture = $setting->widthServicePicture;
                $heightServicePicture = $setting->heightServicePicture;
                $widthSmallServicePicture = $setting->widthSmallServicePicture;
                $heightSmallServicePicture = $setting->heightSmallServicePicture;
                $model->image->saveAs($file);
                //Используем функции расширения CImageHandler;
                $ih = new CImageHandler(); //Инициализация
                Yii::app()->ih
                    ->load($file) //Загрузка оригинала картинки
                    ->thumb($widthServicePicture, $heightServicePicture) //Создание картинки
                    ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/'.$fileName) //Сохранение
                    ->reload() //Снова загрузка оригинала картинки
                    ->thumb($widthSmallServicePicture, $heightSmallServicePicture) //Создание превьюшки
                    ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small/'.$fileName); //Сохранение превьюшки в папку thumbs_small

            }
            $this->redirect(array('index','id'=>$model->id));
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Service']))
        {

            $model->attributes=$_POST['Service'];
           // echo "<pre>"; print_r($model); echo"</pre>";
            if($model->del_img == 1)
            {
                if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/'.$model->picture))
                {
                    //удаляем файл
                    unlink('./images/service/'.$model->picture);
                    unlink('./images/service/small/'.$model->picture);
                    $model->picture = '';
                }
            }
            $model->prev_id = $_POST["Service"]["prev_id"];
            $model->image=CUploadedFile::getInstance($model,'image');
            //Если поле загрузки файла не было пустым, то
            if ($model->image){
                //сохранить файл на сервере в каталог images/service/ под именем id.jpg
                $sourcePath = pathinfo($model->image->getName());
                $fileName = $model->id.'.'.$sourcePath['extension'];
                $model->picture = $fileName;
                //создаем необходимые папки если они не существуют
                if(!is_dir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small')){
                    mkdir($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small', 0777, true);
                }
                $file = $_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small/'.$fileName;
                $setting = Setting::model()->findByPk(1);
                $widthServicePicture = $setting->widthServicePicture;
                $heightServicePicture = $setting->heightServicePicture;
                $widthSmallServicePicture = $setting->widthSmallServicePicture;
                $heightSmallServicePicture = $setting->heightSmallServicePicture;
                $model->image->saveAs($file);
                //Используем функции расширения CImageHandler;
                $ih = new CImageHandler(); //Инициализация
                Yii::app()->ih
                    ->load($file) //Загрузка оригинала картинки
                    ->thumb($widthServicePicture, $heightServicePicture) //Создание картинки
                    ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/'.$fileName) //Сохранение
                    ->reload() //Снова загрузка оригинала картинки
                    ->thumb($widthSmallServicePicture, $heightSmallServicePicture) //Создание превьюшки
                    ->save($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/small/'.$fileName); //Сохранение превьюшки в папку thumbs_small

            }
            if($model->save()){
                $this->redirect(array('index','id'=>$model->id));
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
    public function actionDelete($id){

        Service::deleteNode($id);
        $this->redirect(array('index'));

    }

    public function actionGetchildren($pid){//работает через ajax (необходима для определения положения услуги)
        $criteria = new  CDbCriteria;
        $criteria->select = "id, title";
        $criteria->condition = "pid={$pid}";
        $criteria->order = "position";
        $services = Service::model()->findAll($criteria);
        $services_array = array();
        foreach($services as $service){
            $services_array[$service->id] = array(
                "id" => $service->id,
                "title" => $service->title
            );
        }
        //необходимо для обработки в js
        array_unshift($services_array, array("id"=>0, "title"=>"Начало"));
        $services_array[] = array();
        //print_r($services_array);
        echo json_encode($services_array);
        exit;
    }

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Service('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Service']))
			$model->attributes=$_GET['Service'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Service the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Service::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}
