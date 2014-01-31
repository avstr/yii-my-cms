<?php /* @var $this Controller */ ?>
<?php
Yii::app()->getClientScript()->registerCssFile('/css/navigation.css' );
//соцсети
Yii::app()->getClientScript()->registerPackage('sociallikes');
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

    <!--social-likes -->
    <ul class="social-likes" data-counters="no">
        <li class="facebook" title="Поделиться ссылкой на Фейсбуке">&nbsp</li>
        <li class="twitter" title="Поделиться ссылкой в Твиттере">Twitter</li>
        <li class="mailru" title="Поделиться ссылкой в Моём мире">Мой мир</li>
        <li class="vkontakte" title="Поделиться ссылкой во Вконтакте">Вконтакте</li>
        <li class="odnoklassniki" title="Поделиться ссылкой в Одноклассниках">Одноклассники</li>
        <li class="plusone" title="Поделиться ссылкой в Гугл-плюсе">Google+</li>
    </ul>
    <!--social-likes -->
    <br><br>
    <div class="top-panel">
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items'=>array_merge(Page::menu("front"), array(
                array('label'=>'Регистрация', 'url'=>array('/site/registration'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            )),
            'htmlOptions'=>array('class'=>'nav'),
            'activateParents'=>true,
        ));
        //echo Yii::app()->createAbsoluteUrl("site/login", array('id' => $page["id"]));
        ?>
    </div><!-- top-panel -->

    <div class="clear-both">

    </div>


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
