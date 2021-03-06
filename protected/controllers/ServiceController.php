<?php

class ServiceController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

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
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view'),
                'users'=>array('*'),
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
     * Lists all models.
     */
    public function actionIndex($id=0)
    {
        $services = Service::all($id);
        $this->render('index',array(
            'id'=>$id,
            'services' => $services,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "hidden=:hidden AND id=:id";
        $criteria->params = array(
            ':hidden' => 'no',
            ':id' => $id,
        );
        $model=Service::model()->find($criteria);
        if($model===null)
            throw new CHttpException(404,"Нет статьи с id = {$id}.");
        return $model;
    }

}
?>
