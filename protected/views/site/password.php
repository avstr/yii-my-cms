<?php if(!empty($errors)):?>
    <?php if(isset($errors['time_secure_code'])): ?>
        <div>
            Истек срок действия ссылки
            <a href='<?php echo Yii::app()->createUrl("site/recoverpass");?>'>Выслать повторно письмо</a>
        </div>
    <?php endif ?>
<?php else: ?>
    <div class="form">

        <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'user-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>
        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'password_repeat'); ?>
            <?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'password_repeat'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton("Сменить пароль"); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
<?php endif ?>