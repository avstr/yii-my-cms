<?php

class UserController extends Controller
{
    public function actionUpdate()
    {
        $model = User::model()->findByPk(Yii::app()->user->id);
        $model->scenario = "update";
        if(isset($_POST["User"])){
            $model->attributes = $_POST["User"];
            if($model->save()){
                Yii::app()->user->setFlash("save", "Данные успешно сохранились");
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }
}