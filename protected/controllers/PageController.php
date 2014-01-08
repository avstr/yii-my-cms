<?php

class PageController extends Controller
{


    public function actionIndex($id)
    {
        echo "{$id}<br>";
        $model=$this->loadModel($id);

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model=Page::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Такая страница не найдена');
        return $model;
    }
}