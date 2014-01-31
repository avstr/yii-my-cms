<?php /* @var $this Controller */ ?>
<?php $this->beginContent('/layouts/main'); ?>

<?php
//Yii::app()->getClientScript()->registerCoreScript('jquery')
Yii::app()->getClientScript()->registerPackage('jquery');
Yii::app()->getClientScript()->registerPackage('treeview');
Yii::app()->getClientScript()->registerPackage('cookie');
Yii::app()->getClientScript()->registerPackage('contextmenu');
Yii::app()->getClientScript()->registerPackage('ui');
?>
<div class="span-5 last">

    <div id="sidebar">
        <div id="add_service">
            <a href="<?php echo $this->createUrl("/admin/service/create")?>">Добавить страницу</a>
        </div>
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title'=>'Структура',
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items'=>Service::menu("admin", $_GET["id"]),
            'htmlOptions'=>array('id'=>'service_menu'),
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
        $("#Service_pid").change(function(){
            $("#Service_prev_id").html("");
            $.ajax({
                url: "<?php echo $this->createAbsoluteUrl('/admin/service/getchildren');?>",
                data: {pid: $(this).val()},
                method: 'get',
                success: function(data) {
                    var services = JSON.parse(data);
                    var prev_service, add_str;
                    for(var key in services){
                        add_str = '';
                        if(prev_service != undefined && prev_service["id"]==curId){
                            add_str = "disabled";
                        }
                        if(services[key]["id"] != undefined && services[key]["id"]==curId){
                            add_str = "selected";
                        }
                        if(prev_service != undefined){
                            $("#Service_prev_id").append("<option value=" + prev_service["id"] + " " + add_str + ">" +  prev_page["title"] + "</option>");
                        }
                        prev_service = services[key];
                    }
                }
            });
        });
        $("#service_menu").treeview();
        $.contextMenu({
            selector: '#service_menu li',
            callback: function(key, options) {
                var url;
                var current_service_id = $(options['$trigger']).attr('id');
                switch(key){
                    case "update":
                        var url_for_edit = "<?php echo $this->createAbsoluteUrl("/admin/service/update", array('id' => '__id__'))?>";
                        url = url_for_edit.replace("__id__", current_service_id);
                        window.location = url;
                        break;
                    case "add_child":
                        if(confirm("Вы уверены, что хотите добавить потомка для \"" + $(options['$trigger']).find("a").html() + "\"")){
                            var url_for_add_child = "<?php echo $this->createAbsoluteUrl("/admin/service/create", array('pid' => '__id__'))?>";
                            url = url_for_add_child.replace("__id__", current_service_id);
                            window.location = url;
                        }
                        break;
                    case "delete":
                        if(confirm("Вы уверены, что хотите удалить страницу \"" + $(options['$trigger']).find("a").html() + "\"")){
                            var url_for_delete = "<?php echo $this->createAbsoluteUrl("/admin/service/delete", array('id' => '__id__'))?>";
                            url = url_for_delete.replace("__id__", current_service_id);
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