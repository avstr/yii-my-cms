<?php
/* @var $this ServiceController */
/* @var $model Service */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'pid'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->dropDownList($model,'pid', Service::itemList($_GET["id"])); ?>
            <?php echo $form->error($model,'pid'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'prev_id'); ?>
        </div>
        <div class="row_content">
            <select id="Service_prev_id" name="Service[prev_id]">
                <?php
                $criteria = new CDbCriteria;
                $criteria->select = "id, title";
                $criteria->condition = "pid='{$model->pid}'";
                $criteria->order = "position";
                $service_levels = Service::model()->findAll($criteria);
                array_unshift($service_levels, array("id"=>0, "title"=>"Начало"));
                $service_levels[] = array();
                $prev_service = array();
                foreach((array)$service_levels as $service_level){
                    $add_str = '';
                    if($prev_service["id"]==$model->id && !$model->isNewRecord){
                        $add_str = "disabled";
                    }elseif(!empty($service_level) && $service_level["id"]==$model->id && !$model->isNewRecord){
                        $add_str = "selected";
                    }
                    if(!empty($prev_service)){
                        echo "<option value='{$prev_service['id']}' {$add_str}>{$prev_service['title']}</option>";
                    }
                    $prev_service = $service_level;
                }
                ?>
            </select>
        </div>
    </div>


    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'title'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'title'); ?>
        </div>
	</div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'short_desc'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textArea($model,'short_desc',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'short_desc'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'description'); ?>
        </div>
        <div class="row_content">
            <?php $this->widget('application.extensions.ckeditor.CKEditor', array( 'model'=>$model, 'attribute'=>'description', 'language'=>'ru', 'editorTemplate'=>'full', )); ?>
            <?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'image'); ?>
        </div>
        <div class="row_content">
            <?php // Вывод уже загруженной картинки или изображения No_photo
            echo Service::material_image("small", $model->picture, $model->title, 'small_img left');?>
            <br clear="all">
            <?php //Если картинка для данного товара загружена, предложить её удалить, отметив чекбокс
            if(isset($model->picture) && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/'.$model->picture))
            {
                echo $form->checkBox($model,'del_img',array('class'=>'span-1'));
                echo $form->labelEx($model,'del_img',array('class'=>'span-2'));
            }
            ?>
            <br>
            <?php //Поле загрузки файла
            echo CHtml::activeFileField($model, 'image'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'hidden'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->checkBox($model,'hidden', array("uncheckedValue"=>"no", "checkedValue"=>"yes", "value"=>"yes")); ?>
            <?php echo $form->error($model,'hidden'); ?>
        </div>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->