<?php
    $count_services = sizeof($services);
    $count_row_two_services = floor($count_services/2);
?>
<?php if($count_services > 0):?>
    <table>
    <!--Выводим услуги по двум колонкам-->
    <?php for($i = 0; $i < $count_row_two_services; $i++):?>
        <tr>
            <?php for($j = 0; $j < 2; $j++): ?>
                <td>
                    <?php $current_index = $i * 2 + $j;?>
                    <?php echo CHtml::encode($services[$current_index]['title']); ?>
                    <div>
                        <?php echo Service::material_image("normal", $services[$current_index]['picture'], $services[$current_index]['title'], '');?>
                    </div>
                    <div>
                        <?php echo Helper::truncate($services[$current_index]['description'], 10); ?>
                    </div>

                </td>
            <?php endfor ?>
        </tr>
    <?php endfor ?>
    <!--Одна услуга в двух колонках-->
    <?php if($count_row_two_services * 2 != $count_services): ?>
        <?php $current_index = $count_services - 1;?>
        <td>
            <?php echo Service::material_image("normal", $services[$current_index]['picture'], $services[$current_index]['title'], '');?>
        </td>
        <td>
            <?php echo CHtml::encode($services[$current_index]['title']); ?>
            <div>
                <?php echo Helper::truncate($services[$current_index]['description'], 50); ?>
            </div>
        </td>
    <?php endif ?>
    </table>
<?php endif ?>
