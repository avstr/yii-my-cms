<?php
/* @var $this NewsController */
/* @var $data News */
?>
gfgfdgsdf
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo date("d.m.Y", $data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shorh_desc')); ?>:</b>
	<?php echo CHtml::encode($data->shorh_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    array(
    'name' => 'description',
    'type' => 'html'
    ),
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('hidden')); ?>:</b>
    <?php echo ($data->hidden == "yes") ? "да" : "нет"; ?>
    <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('picture')); ?>:</b>
    <?php echo $this->material_image($data->id, substr($data->create_time, 0, 4), $data->picture, $data->title, '150','small_img left');?>
	<br />


</div>