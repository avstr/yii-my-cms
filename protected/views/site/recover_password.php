<?php if(Yii::app()->user->hasFlash('mail')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('mail'); ?>
    </div>
<?php else: ?>

    <h1>Восстановление пароля</h1>
    <h5>На ваш почтовый ящик придет инструкция по восстановлению пароля</h5>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'recover_password',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

        <?php echo $form->labelEx($model,'email'); echo $errors['email']; ?>
        <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
        <div class="error">
            <?php if(!empty($errors)): ?>
                <?php if($errors['email'] == 'empty_email'):?>
                    Не заполнен email.
                <?php elseif($errors['email'] == 'validation_email'): ?>
                    Введите правильный email.
                 <?php elseif($errors['email'] == 'not_user'): ?>
                    Нет пользователя с таким email.
                <?php endif ?>

            <?php endif ?>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton('Отправить'); ?>
        </div>

    <?php $this->endWidget(); ?>
<?php endif ?>