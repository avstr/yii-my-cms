<?php

class CommentController extends Controller
{

    public function actionCreate()
    {
        //комментарий не может оставить не зарегистрированный пользователь
        if(Yii::app()->user->isGuest){
            throw new CHttpException(404,"Оставить комментарий может только зарегистрированный пользователь!");
        }
        $model=new Comment;
        if(isset($_POST['Comment']))
        {
            $model->attributes=$_POST['Comment'];
            if($model->save()){
                header("Location:{$_SERVER['HTTP_REFERER']}#comment_{$model['id']}");
                exit;
            }
        }
        //записываем в сессию данные по модели
        Yii::app()->session->add("comment_model", serialize($model));
        //echo "<pre>"; print_r($_SESSION);echo "</pre>";
        header("Location:{$_SERVER['HTTP_REFERER']}#form_comment");
        exit;
    }
}