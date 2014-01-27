<?php /* @var $this Controller */ ?>
<?php
//подключаем css для нашей админки
/*$patchUrl = Yii::getPathOfAlias('application.components.admin'); //Путь до нашего виджета
$patchAssets=$patchUrl.'\assets';
$patchAssets = Yii::app()->assetManager->publish($patchAssets, false, -1, YII_DEBUG); //Подключаем наши assets
$patchJs=$patchAssets.'\js';
$patchCss=$patchAssets.'\css';
http://www.pvsm.ru/javascript/23950
$cs = Yii::app()->clientScript;
echo $patchCss;
$cs->registerCssFile($patchCss."/admin.css");
Yii::app()->clientScript->registerCssFile(
    Yii::app()->assetManager->publish(
        Yii::getPathOfAlias('ext.myExtension.assets').'/admin.css'
    )
);
$dir = YiiBase::getPathOfAlias('application').DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'admin';
$baseUrl = Yii::app()->getAssetManager()->publish($dir);
// подключить css
$clientScript->registerCssFile($baseUrl.'/css/admin.css');*/
Yii::app()->getClientScript()->registerCssFile('/css/admin.css' );
Yii::app()->getClientScript()->registerScriptFile('/js/admin.js' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Страницы', 'url'=>array('/admin/page')),
				array('label'=>'Пользователи', 'url'=>array('/admin/user')),
                array('label'=>'Настройки', 'url'=>array('/admin/setting')),
                array('label'=>'Новости', 'url'=>array('/admin/news')),
                array('label'=>'Отзывы', 'url'=>array('/admin/response')),
                array('label'=>'Комментарии', 'url'=>array('/admin/comment')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
