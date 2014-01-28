<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
        if (Yii::app()->user->role == 'admin') {
            $this->layout = 'application.modules.admin.views.layouts.main';
        }
        if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

    public function actionRegistration()
    {
        $model=new User;
        $model->scenario = "registration";

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->save()){
                //после сохранение отсылаем письмо пользователю
                if(Yii::app()->user->isGuest){
                    $setting = Setting::model()->findByPk(1);
                    Yii::app()->mailer->IsMail();
                    Yii::app()->mailer->From = $setting->email;
                    Yii::app()->mailer->FromName = Yii::app()->name;
                    Yii::app()->mailer->AddAddress($model->email); //кому
                    Yii::app()->mailer->Subject = 'Авторизация на сайте';
                    Yii::app()->mailer->CharSet = 'UTF-8';
                    Yii::app()->mailer->ContentType = 'text/html';
                    Yii::app()->mailer->Body =$this->renderPartial('mail',array(
                        'model' => $model,
                        'subject' => 'user_verify',
                    ), true);
                    Yii::app()->mailer->Send();
                }
                Yii::app()->user->setFlash("registration", "На Ваш почтовый ящик выслано письмо");
            }

        }

        $this->render('registration',array(
            'model'=>$model,
        ));
    }

    public function actionVerify($id, $secure_code){
        $result = '';
        $model = User::model()->findByPk($id);
        if($model == null){
            $result = "not_user";
        }
        if($result == ''){
            if($model->status == 'verify'){
                $result = 'earlier_verify';
            }
        }
        if($result == ''){
            if($model->id == $id && $model->secure_code == $secure_code){
                $model->secure_code = '';
                $model->status = 'verify';
                $model->save(false);
                $result = 'verify';
            }else{
                $result = 'error_id_secure_code';
            }
        }
        $this->render('verify', array(
            'result' => $result,
        ));
    }

    public function actionRecoverpass(){
        $model = new User;
        $errors = array();
        if(isset($_POST['User'])){
            $model->email = $_POST['User']['email'];
            //проверяем email
            if($model->email == ''){
                $errors['email'] = "empty_email";
            }
            if(empty($errors) && !preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $model->email)){
                $errors['email'] = "validation_email";
            }
            if(empty($errors)){
                //получаем запись с заданным email
                $criteria = new CDbCriteria();
                $criteria->condition = "email = :email";
                $criteria->params = array(
                    ':email' => $model->email,
                );
                $user = User::model()->find($criteria);
                if($user == null){
                    $errors['email'] = "not_user";
                }else{
                    $model = $user;
                }
            }
            if(empty($errors)){
                //генерим случайный код и время, до которого действует пароль
                $model->secure_code = md5(time().$model->email);
                $model->time_secure_code = date("Y-m-d H:i:s", time() + 24*60*60);

                if($model->save(false)){
                    //отправляем письмо по заданному email
                    $setting = Setting::model()->findByPk(1);
                    Yii::app()->mailer->IsMail();
                    Yii::app()->mailer->From = $setting->email;
                    Yii::app()->mailer->FromName = Yii::app()->name;
                    Yii::app()->mailer->AddAddress($model->email); //кому
                    Yii::app()->mailer->Subject = 'Восстановление пароля';
                    Yii::app()->mailer->CharSet = 'UTF-8';
                    Yii::app()->mailer->ContentType = 'text/html';
                    Yii::app()->mailer->Body =$this->renderPartial('mail',array(
                        'model' => $model,
                        'subject' => 'recover_password',
                    ), true);
                    Yii::app()->mailer->Send();
                    Yii::app()->user->setFlash('mail','Вам на почту выслано письмо!');
                }

            }

        }
        $this->render('recover_password', array(
            'model' => $model,
            'errors' => $errors,
        ));

    }

    public function actionPassword($id, $secure_code){
        $errors = array();
        $model = User::model()->findByPk($id);
        $model->scenario = "password";
        if($model == null){
            throw new CHttpException(404,"Нет пользователя с id = {$id}.");
        }
        $model->password = '';
        if($model->id == $id && $model->secure_code == $secure_code){
            if(time() > strtotime($model->time_secure_code)){
                $errors['time_secure_code'] = "Out of time secure code";
            }elseif(isset($_POST['User'])){
                $model->password=$_POST['User']['password'];
                $model->password_repeat=$_POST['User']['password_repeat'];
                $model->secure_code = '';
                $model->time_secure_code = '';
                if($model->save()){
                    $this->redirect(Yii::app()->homeUrl);
                }
            }
        }else{
            throw new CHttpException(404,"Неверный секретный код.");;
        }

        $this->render('password', array(
            'model' => $model,
            'errors' => $errors,
        ));

    }
    /**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}