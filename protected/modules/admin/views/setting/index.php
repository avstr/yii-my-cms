<?php
/* @var $this SettingController */

?>
<h1>Настройки</h1>

<?php if(Yii::app()->user->hasFlash('setting')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('setting'); ?>
</div>

<?php endif ?>


<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'settig-form',
    'action' => '/admin/setting/index',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'sizeSideNewsPicture'); ?>
        <?php echo $form->textField($model,'sizeSideNewsPicture'); ?>
        <?php echo $form->error($model,'sizeSideNewsPicture'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'sizeSideSmallNewsPicture'); ?>
        <?php echo $form->textField($model,'sizeSideSmallNewsPicture'); ?>
        <?php echo $form->error($model,'sizeSideSmallNewsPicture'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'hiddenNewResponse'); ?>
        <?php echo $form->checkBox($model,'hiddenNewResponse', array("uncheckedValue"=>"no", "checkedValue"=>"yes", "value"=>"yes")); ?>
        <?php echo $form->error($model,'hiddenNewResponse'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton("Сохранить"); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>

<div class="form">
    <div class="row">
        <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'clear_cache-form',
        'action' => '/admin/setting/clearcache',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>
            Очистить кеш
            <?php echo CHtml::submitButton("Очистить cache"); ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
