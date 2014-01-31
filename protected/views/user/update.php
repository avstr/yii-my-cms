
<?php if(Yii::app()->user->hasFlash('save')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('save'); ?>
</div>
<?php endif ?>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'surname'); ?>
        <?php echo $form->textField($model,'surname',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'surname'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton("Сохранить"); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->