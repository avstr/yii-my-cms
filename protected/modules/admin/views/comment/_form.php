<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
    <input type="hidden" name='mode' value="admin">
	<p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

	<div>
        <?php echo $form->labelEx($model,'user_name'); ?>
        <?php echo "{$model->user->surname} {$model->user->name}"; ?>
    </div>
    <div>
        <?php echo $form->labelEx($model,'date'); ?>
        <?php echo date("d.m.Y H:i", strtotime($model->date)); ?>
    </div>
    <?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'hidden'); ?>
        <?php echo $form->checkBox($model,'hidden', array("uncheckedValue"=>"no", "checkedValue"=>"yes", "value"=>"yes")); ?>
        <?php echo $form->error($model,'hidden'); ?>
    </div>

	<div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->