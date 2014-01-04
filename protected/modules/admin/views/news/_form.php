<?php
/* @var $this NewsController */
/* @var $model News */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'file-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                'name'=>'News[date]',
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
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shorh_desc'); ?>
		<?php echo $form->textArea($model,'shorh_desc',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'shorh_desc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
        <?php $this->widget('application.extensions.ckeditor.CKEditor', array( 'model'=>$model, 'attribute'=>'description', 'language'=>'ru', 'editorTemplate'=>'full', )); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'hidden'); ?>
        <?php echo $form->checkBox($model,'hidden', array("uncheckedValue"=>"no", "checkedValue"=>"yes", "value"=>"yes")); ?>
        <?php echo $form->error($model,'hidden'); ?>
    </div>

	<div class="row">
        <?php echo $form->labelEx($model,'image'); ?>
        <?php // Вывод уже загруженной картинки или изображения No_photo
        echo $model->material_image($model->id, substr($model->date, 0, 4), $model->picture, $model->title, '150','small_img left');?>
        <br clear="all">
        <?php //Если картинка для данного товара загружена, предложить её удалить, отметив чекбокс
        if(isset($model->picture) && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.substr(0, 4, $model->date).'/'.$model->picture))
        {
            echo $form->checkBox($model,'del_img',array('class'=>'span-1'));
            echo $form->labelEx($model,'del_img',array('class'=>'span-2'));
        }
        ?>
        <br>
        <?php //Поле загрузки файла
        echo CHtml::activeFileField($model, 'image'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->