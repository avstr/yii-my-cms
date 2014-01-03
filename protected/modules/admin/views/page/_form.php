<?php
/* @var $this PageController */
/* @var $model Page */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'page-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Поля с <span class="required">*</span> должны быть заполнены.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'pid'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->dropDownList($model,'pid', Page::itemList($_GET["id"])); ?>
            <?php echo $form->error($model,'pid'); ?>
        </div>
    </div>
    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'prev_id'); ?>
        </div>
        <div class="row_content">
            <select id="Page_prev_id" name="Page[prev_id]">
                <?php
                    $criteria = new CDbCriteria;
                    $criteria->select = "id, title";
                    $criteria->condition = "pid='{$model->pid}'";
                    $criteria->order = "position";
                    $page_levels = Page::model()->findAll($criteria);
                    array_unshift($page_levels, array("id"=>0, "title"=>"Начало"));
                    $page_levels[] = array();
                    $prev_page = array();
                    foreach((array)$page_levels as $page_level){
                        $add_str = '';
                        if($prev_page["id"]==$model->id && !$model->isNewRecord){
                            $add_str = "disabled";
                        }elseif(!empty($page_level) && $page_level["id"]==$model->id && !$model->isNewRecord){
                            $add_str = "selected";
                        }
                        if(!empty($prev_page)){
                            echo "<option value='{$prev_page['id']}' {$add_str}>{$prev_page['title']}</option>";
                        }
                        $prev_page = $page_level;
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'type'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->radioButtonList($model,'type',array('static'=>'Статическая страница','URL'=>'URL')); ?>
            <?php echo $form->error($model,'type'); ?>
         </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'URL'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textField($model,'URL',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'URL'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'content'); ?>
        </div>
        <div class="row_content">
            <?php $this->widget('application.extensions.ckeditor.CKEditor', array( 'model'=>$model, 'attribute'=>'content', 'language'=>'ru', 'editorTemplate'=>'full', )); ?>
            <?php echo $form->error($model,'content'); ?>
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

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'alias'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'alias'); ?>
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
            <?php echo $form->labelEx($model,'meta_d'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textField($model,'meta_d',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'meta_d'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'meta_k'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textField($model,'meta_k',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'meta_k'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'meta_t'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textField($model,'meta_t',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'meta_t'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row_label">
            <?php echo $form->labelEx($model,'description'); ?>
        </div>
        <div class="row_content">
            <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
            <?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script>
    var curId = <?php if($model->id > 0)echo $model->id; else echo 'undefined';?>
</script>