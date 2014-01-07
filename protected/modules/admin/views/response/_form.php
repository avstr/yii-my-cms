<?php
/* @var $this ResponseController */
/* @var $model Response */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'response-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'Pesponse[date]',
            'language'=>'ru',
            'value'=>date("d.m.Y", strtotime($model->date)),
            // additional javascript options for the date picker plugin
            'options'=>array(
                'showAnim'=>'fold',
            ),
            'htmlOptions'=>array(
                'style'=>'height:20px;'
            ),
        ));
        ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'answer'); ?>
        <?php $this->widget('application.extensions.ckeditor.CKEditor', array( 'model'=>$model, 'attribute'=>'answer', 'language'=>'ru', 'editorTemplate'=>'full', )); ?>
		<?php echo $form->error($model,'answer'); ?>
	</div>

	<div class="row">
        <?php echo $form->labelEx($model,'hidden'); ?>
        <?php echo $form->checkBox($model,'hidden', array("uncheckedValue"=>"no", "checkedValue"=>"yes", "value"=>$model->hidden)); ?>
        <?php echo $form->error($model,'hidden'); ?>
	</div>
    <input type="hidden" name="mode" value="admin">
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->