<?php if($subject == 'user_verify'): ?>
    <p>
        Уважаемый, <?php echo $model->name, ' ', $model->surname;?>,
        для регистрации на сайте <?php echo Yii::app()->name;?>
        перейдите по <a href='<?php echo $this->createAbsoluteUrl('site/verify', array('id' => $model->id, 'secure_code' => $model->secure_code))?>'>ссылке</a>.
    </p>
<?php elseif($subject == 'recover_password'): ?>
    <p>
        Уважаемый <?php echo $model->name, ' ', $model->surname;?>
        для восстановления пароля на сайте <?php echo Yii::app()->name;?>
        перейдите по <a href='<?php echo $this->createAbsoluteUrl('site/password', array('id' => $model->id, 'secure_code' => $model->secure_code))?>'>ссылке</a>.
    </p>

<?php endif ?>
