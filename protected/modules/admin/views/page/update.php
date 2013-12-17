<?php
/* @var $this PageController */
/* @var $model Page */

?>

<h1>Изменение страницы <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>