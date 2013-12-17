<?php /* @var $this Controller */ ?>
<?php $this->beginContent('/layouts/main'); ?>

<?php
//Yii::app()->getClientScript()->registerCoreScript('jquery')
Yii::app()->getClientScript()->registerPackage('jquery');
Yii::app()->getClientScript()->registerPackage('treeview');
Yii::app()->getClientScript()->registerPackage('cookie');
Yii::app()->getClientScript()->registerPackage('contextmenu');
?>
<div class="span-5 last">

	<div id="sidebar">
        <div id="add_page">
            <a href="<?php echo $this->createUrl("/admin/page/create")?>">Добавить страницу</a>
        </div>
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Структура',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>Page::menuAdmin($_GET["id"]),
			'htmlOptions'=>array('id'=>'page_menu'),
            'activateParents'=>true,
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>

<div class="span-19">
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>

<script>
    $(document).ready(function(){
        $("#page_menu").treeview();
        $.contextMenu({
            selector: '#page_menu li',
            callback: function(key, options) {
                var url;
                var current_page_id = $(options['$trigger']).attr('id');
                switch(key){
                    case "update":
                        var url_for_edit = "<?php echo $this->createAbsoluteUrl("/admin/page/update", array('id' => '__id__'))?>";
                        url = url_for_edit.replace("__id__", current_page_id);
                        window.location = url;
                        break;
                    case "add_child":
                        if(confirm("Вы уверены, что хотите добавить потомка для \"" + $(options['$trigger']).find("a").html() + "\"")){
                            var url_for_add_child = "<?php echo $this->createAbsoluteUrl("/admin/page/create", array('pid' => '__id__'))?>";
                            url = url_for_add_child.replace("__id__", current_page_id);
                            window.location = url;
                        }
                        break;
                    case "delete":
                            if(confirm("Вы уверены, что хотите удалить страницу \"" + $(options['$trigger']).find("a").html() + "\"")){
                                var url_for_delete = "<?php echo $this->createAbsoluteUrl("/admin/page/delete", array('id' => '__id__'))?>";
                                url = url_for_delete.replace("__id__", current_page_id);
                                window.location = url;
                            }
                        break;
                }
            },
            items: {
                "update": {name: "Редактировать", icon: "edit", accesskey: "e"},
                // first unused character is taken (here: o)
                "add_child": {name: "Добавить ребенка", icon: "copy", accesskey: "c o p y"},
                // words are truncated to their first letter (here: p)
                "delete": {name: "Удалить", icon: "delete"},
                "sep1": "---------",
                "quit": {name: "Выход", icon: "quit"}
            }
        });
    });

</script>