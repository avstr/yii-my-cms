<div class="form">
    <a id="form_comment"></a>
    <div>
        <b>Оставить комментарий</b>
    </div>

<?php
    Yii::app()->getClientScript()->registerScriptFile('/js/comment.js' );
?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'action'=>$this->createUrl('comment/create'),
    'enableAjaxValidation'=>false,
)); ?>

    <input type="hidden" name="Comment[module_name]" value="<?php echo $moduleName; ?>">
    <input type="hidden" name="Comment[object_id]" value="<?php echo $objectId; ?>">
    <input type="hidden" name="Comment[pid]" value="0" id="comment_pid">

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255, 'id' => 'comment_title')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'comment'); ?>
        <?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'comment'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Добавить'); ?>
    </div>
<?php $this->endWidget(); ?>
</div>
