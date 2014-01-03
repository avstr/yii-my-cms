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
        <div id="add_page">
            <a href="<?php echo $this->createUrl("/admin/page/create")?>">Добавить страницу</a>
        </div>
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Структура',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>Page::menu("admin", $_GET["id"]),
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
        $("#Page_pid").change(function(){
            $("#Page_prev_id").html("");
            $.ajax({
                url: "<?php echo $this->createAbsoluteUrl('/admin/page/getchildren');?>",
                data: {pid: $(this).val()},
                method: 'get',
                success: function(data) {
                    var pages = JSON.parse(data);
                    var prev_page, add_str;
                    for(var key in pages){
                        add_str = '';
                        if(prev_page != undefined && prev_page["id"]==curId){
                            add_str = "disabled";
                        }
                        if(pages[key]["id"] != undefined && pages[key]["id"]==curId){
                            add_str = "selected";
                        }
                        if(prev_page != undefined){
                            $("#Page_prev_id").append("<option value=" + prev_page["id"] + " " + add_str + ">" +  prev_page["title"] + "</option>");
                        }
                        prev_page = pages[key];
                    }
                }
            });
        });
        $("#page_menu").treeview()/*.sortable({
            connectWith: '#page_menu',
            items: 'li',
            placeholder: 'placeholder',
            stop: function(e, ui) {
                if(confirm("Вы уверены, что хотите переместить страницу '" + $(ui.item).find("a").html() + "'?")){
                    var prev_id = ($(ui.item).prev("li").length > 0) ? $(ui.item).prev("li").attr("id") : 0;
                    var parent_id = ($(ui.item).parent("ul").parent("li").attr("id"));
                    if(parent_id == undefined){
                        parent_id = 0;
                    }
                    alert(prev_id);
                    alert(parent_id);
                    $.ajax({
                        url: "<?php echo $this->createAbsoluteUrl('/admin/page/move');?>",
                        data: {id: $(ui.item).attr("id"), parent_id: parent_id, prev_id: prev_id},
                        method: 'post',
                        async: false, // это важно
                        success: function(data) {
                            if(data["result"] == "error"){
                                alert("Не удалось переместить узел");
                                $('#page_menu').sortable('cancel');
                            }else{
                                $('#page_menu .hitarea, #page_menu .hitarea').removeClass('lastCollapsable-hitarea lastExpandable-hitarea');
                                $('#page_menu li').removeClass('lastExpandable lastCollapsable expandable collapsable last');

                                $('#page_menu li').filter(":last-child:not(ul)").addClass('last');
                                var br =$('#page_menu li').filter(":has(>ul)");

                                br.filter(":has(>ul:hidden)")
                                        .addClass($.treeview.classes.expandable)
                                        .replaceClass($.treeview.classes.last, $.treeview.classes.lastExpandable);

                                br.not(":has(>ul:hidden)")
                                        .addClass($.treeview.classes.collapsable)
                                        .replaceClass($.treeview.classes.last, $.treeview.classes.lastCollapsable);

                                var hitarea = br.find("div." + $.treeview.classes.hitarea);
                                hitarea.each(function() {
                                    var classes = "";
                                    $.each($(this).parent().attr("class").split(" "), function() {
                                        classes += this + "-hitarea ";
                                    });
                                    $(this).addClass( classes );
                                });
                            }

                        }
                    });
                }else{
                    $('#page_menu').sortable('cancel');
                }
            }
        });*/;
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