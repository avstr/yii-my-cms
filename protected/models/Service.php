<?php

/**
 * This is the model class for table "{{service}}".
 *
 * The followings are the available columns in table '{{service}}':
 * @property integer $id
 * @property integer $pid
 * @property string $parents
 * @property integer $level
 * @property integer $position
 * @property string $title
 * @property string $short_desc
 * @property string $description
 */
class Service extends CActiveRecord
{

    public $image; // атрибут для хранения загружаемой картинки услуги
    public $del_img; // атрибут для удаления уже загруженной услуги
    public $prev_id;
    static $active_id;
    static $active_parents = array();
    static $items = array();
    static $list = array();
    static $callBeforeSave = true;

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{service}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, title, short_desc, description', 'required'),
			array('pid, level, position', 'numerical', 'integerOnly'=>true),
            array('del_img', 'boolean'),
			array('parents, title', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, parents, level, position, title, short_desc, description, hidden, picture', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => 'Pid',
            'position' => 'Position',
			'parents_service' => 'Родители услуги',
            'prev_id'   => "Сортировка",
			'title' => 'Заголовок',
			'short_desc' => 'Краткое описание',
			'description' => 'Описание',
            'image' => 'Картинка',
            'del_img'=>'Удалить картинку?',
            'hidden' => 'Скрыто',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Service the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /** Возвращает меню услуг
     *
     * @param mode {admin, front}
     * @param active service id
     *@return Array services
     */
    public static function menu($mode = "admin", $active_id = 0){
        if(!empty(self::$items)){
            return self::$items;
        }
        if(empty(self::$active_id)){
            self::$active_id = $active_id;
        }
        $criteria = new CDbCriteria;
        $criteria->select = 'id, pid, title, parents, level';
        $criteria->order = "level, position";
        if($mode == "front"){
            $criteria->condition = "hidden = 'no'";
        }
        $services=self::model()->findAll($criteria);
        //получаем всех родителей активной страницы
        if((int)self::$active_id > 0){
            $active_service = self::model()->findByPk(self::$active_id);
            if(!empty($active_service)){
                self::$active_parents = explode('/', $active_service->parents);
            }
        }

        $items = array();
        $itemsIndex = array();
        foreach($services as $service){
            if($service['pid'] == 0) {
                $items[$service['id']] = self::itemMenu($service, $mode);
                $items[$service['id']]['items'] = array();
                $itemsIndex[$service['id']] = &$items[$service['id']];
            } else {
                $itemsIndex[$service['pid']]['items'][$service['id']] = self::itemMenu($service, $mode);
                $itemsIndex[$service['id']] = &$itemsIndex[$service['pid']]['items'][$service['id']];
            }
        }
        self::$items =  $items;
        //echo "<pre>"; print_r($items); echo "</pre>";
        return self::$items;
    }

    /** Возвращает элемент массива меню услуг
     *
     *@return Array
     */
    public static function itemMenu($service, $mode='admin'){
        $item = array(
            'label'         => $service['title'],
            'url'           => ($mode == "admin") ? array('/admin/service/update', 'id' => $service['id']) : Yii::app()->createAbsoluteUrl("service/index", array('id' => $service["id"])),
            'itemOptions'   => array('id' => $service['id']),
        );
        if($mode == "admin"){
            if(self::$active_id == $service['id']){
                $item['active'] = true;
            }
            if(!in_array($service['id'], self::$active_parents)){
                $item['itemOptions']['class'] = 'closed';
            }
        }else{
            $item['itemOptions']['class'] = ($service['level'] > 1) ? "menu-level-more-1" : "menu-level-1";
        }
        return $item;
    }

    public static function itemList($active_id = 0){
        self::$active_id = $active_id;
        if(!empty(self::$list)){
            return self::$list;
        }
        $services = self::menu("admin", $_GET['id']);

        self::$list[0] = 'root';
        self::formingList($services);
        return self::$list;
    }

    /** Рекурсивная функция для получения одномерного массива услуг для dropDownList
     *
     * @param Array
     * @param int
     */
    public static function formingList($services, $level=0){
        foreach($services as $service_id => $service){
            if($service_id != self::$active_id){
                self::$list[$service_id] = str_repeat('..', $level) . $service['label'];
                if(!empty($service['items'])){
                    self::formingList($service['items'], $level + 1);
                }
            }
        }
    }

    public function beforeSave(){
        if(!self::$callBeforeSave){
            return parent::beforeSave();
        }
        //получаем
        //заполняем поле parents
        $parent = NULL;
        if($this->pid > 0){
            $parent = self::model()->findByPk($this->pid);
        }
        /*if($this->isNewRecord){
            $this->position = $this->calculateMaxPosition($this->pid);
            $this->level = (empty($parent)) ? 1 : $parent->level + 1;
            $this->parents = (empty($parent)) ? "/" : $parent->parents .$this->pid . "/";
        }else{

        }*/
        self::prepareServiceLevel($this->id, $parent, $this->prev_id);
        //определяем hidden
        if($this->hidden != "yes"){
            $this->hidden = "no";
        }
        // echo "<pre>"; print_r($this); echo "</pre>";
        return parent::beforeSave();
    }

    /** подготавливает уровень услуги и родителей услуги и обновляет потомков, parents был '/1/2/17/18/' стал '/3/17/18', если родителя 17 перенесли в узел 3
     *
     * @param int
     * @param mixed(int, object)
     * @return boolen
     */
    public function prepareServiceLevel($id, $parent, $prev_id){
        if(is_numeric($parent)){
            $parent = ($parent > 0) ? self::model()->findByPk($parent) : NULL;
        }
        $parent_id = ($parent > 0) ? $parent->id : 0;
        if($prev_id == 0){
            $this->position = 1;
        }else{
            //получаем предыдущую страницу
            $criteria = new CDbCriteria;
            $criteria->select = "id, position";
            $criteria->condition = "id = :id";
            $criteria->params = array(":id" => $prev_id);
            $prev_service = self::model()->find($criteria);
            $this->position = $prev_service->position + 1;
        }
        //обновляем позиции страниц текущего уровня
        $sql = "UPDATE {{service}} SET position = position+1 WHERE pid={$parent_id} AND position >= {$this->position}";
        Yii::app()->db->createCommand($sql)->execute();
        $this->level = (empty($parent)) ? 1 : $parent->level + 1;
        //подготавливаем данные для обновления
        $this->parents = (empty($parent)) ? '/' : "{$parent->parents}{$parent->id}/";
        //страница не была пересена
        if(!$this->isNewRecord){
            $service = self::model()->findByPk($id);
            if($parent_id == $service->pid){
                return true;
            }

            //обновляем потомков
            $diffLevel = $service->level - $this->level;
            $oldParentsForChilddren = "{$service->parents}{$id}/";
            $newParentsForChilddren = "{$this->parents}{$id}/";
            //echo $oldParentsForChilddren . "___" . $newParentsForChilddren; exit();
            $sql = "UPDATE {{service}} SET level=level-{$diffLevel}, parents=REPLACE(parents, '{$oldParentsForChilddren}', '{$newParentsForChilddren}') WHERE parents LIKE '%{$oldParentsForChilddren}%'";
            return Yii::app()->db->createCommand($sql)->execute();
        }

    }

    /** расчет максимальной позиции
     *
     * @param int
     * @return int
     */
    public function calculateMaxPosition($pid){
        $maxPosition = self::model()->findBySql("SELECT MAX(position) as position FROM {{service}} WHERE pid=:pid", array(":pid" => $pid));
        return ($maxPosition->position >= 0) ? $maxPosition->position + 1 : 1;
    }

    /** перемещаем услугу
     *
     * @param array("id", "prev_id", "parent_id")
     * @param array()
     * @return bool
     */
    public function move($params, &$errors){

        if(!isset($params["prev_id"])){
            $errors["prev_id"] = "Не передан prev_id страницы";
        }
        if(!isset($params["parent_id"])){
            $errors["parent_id"] = "Не передан parent_id страницы";
        }
        if(empty($errors)){
            self::$callBeforeSave = false;
            $this->prepareServiceLevel($params["id"], $params["parent_id"], $params["prev_id"]);
        }
        return (empty($errors)) ? true : false;
    }

    public static function getAllVisibleService(&$serviceByAlias, &$serviceById){
        if(empty(self::$allVisibleServiceById)){
            $criteria = new CDbCriteria;
            $criteria->select = "id, alias, parents, URL, type";
            $criteria->condition = "hidden='no'";
            $services = self::model()->findAll($criteria);
            foreach($services as $service){
                self::$allVisibleServiceByAlias[$service["alias"]] = $service;
                self::$allVisibleServiceById[$service["id"]] = $service;
            }

        }
        $serviceByAlias = self::$allVisibleServiceByAlias;
        $serviceById = self::$allVisibleServiceById;
    }

    /** Возвращает html картинки
     *
     * @param string ("small", "normal")
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public static function material_image($type="small", $image, $title, $class='material_img')
    {
        if(isset($image) && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/'.$image))
            if($type == "small"){
                return CHtml::image(Yii::app()->urlManager->baseUrl.'/images/service/small/'.$image, $title,
                    array(
                        'class'=>$class,
                    ));
            }else{
                return CHtml::image(Yii::app()->urlManager->baseUrl.'/images/service/'.$image, $title,
                    array(
                        'class'=>$class,
                    ));
            }

        else
            return CHtml::image(Yii::app()->getBaseUrl(true).'/images/pics/noimage.gif','Нет картинки',
                array(
                    'class'=>$class
                ));
    }


    /** удаляет услугу с потомками и картинками
     *
     * @param int
     * @return bool
     */
    public static function deleteNode($id){
        //получаем всех детей
        $criteria = new CDbCriteria;
        $criteria->select = "id, picture";
        $criteria->condition = "parents LIKE '%/{$id}/%' OR id={$id}";
        $services = self::model()->findAll($criteria);
        //echo "<pre>"; print_r($services); echo "</pre>";exit;
        foreach($services as $service){
            if($service->picture != '' && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/service/'.$service->picture)){
                //удаляем файлы
                unlink('./images/service/'.$service->picture);
                unlink('./images/service/small/'.$service->picture);
            }

        }

        //удаляем всх детей услуги
        $command = Yii::app()->db->createCommand();

        return $command->delete(Yii::app()->db->tablePrefix . 'service', 'parents LIKE "%/' . $id . '/%" OR id=:id', array(':id'=>$id));
    }

    /** Получаем список не скрытых услуг определенного родителя
     *
     * @param int
     * @return array
     */
    public static function all($pid = 0){
        $criteria = new CDbCriteria;
        $criteria->condition = "pid=:pid AND hidden=:hidden";
        $criteria->params = array(
            ':pid' => $pid,
            ':hidden' => 'no',
        );
        $criteria->order = "position";
        return self::model()->findAll($criteria);
    }

}
