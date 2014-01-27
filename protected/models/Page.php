<?php

/**
 * This is the model class for table "{{page}}".
 *
 * The followings are the available columns in table '{{page}}':
 * @property integer $id
 * @property integer $pid
 * @property integer $position
 * @property string $parents
 * @property string $type
 * @property string $hidden
 * @property string $alias
 * @property string $title
 ** @property string $meta_t
 * @property string $meta_d
 * @property string $meta_k
 * @property string $description
 * @property string $URL
 * @property string $content
 */
class Page extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $prev_id;
    static $active_id;
    static $active_parents = array();
    static $items = array();
    static $list = array();
    static $allVisiblePageById = array();
    static $allVisiblePageByAlias = array();
    static $callBeforeSave = true;

	public function tableName()
	{
		return '{{page}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, type, alias, title', 'required'),
            array('alias', 'unique'),
            array('alias', 'match', 'pattern' => "/^[a-zA-Z0-9\-]+$/u", 'message' => "Допустимые символы a-zA-Z0-9-"),
            array('URL', 'match', 'pattern' => "/^[a-zA-Z0-9\-\/\?=]+$/u", 'message' => "Допустимые символы a-zA-Z0-9-/"),
			array('pid, position', 'numerical', 'integerOnly'=>true),
			array('parents, alias, title, meta_t, meta_d, meta_k, URL', 'length', 'max'=>255),
			array('type', 'length', 'max'=>6),
			array('hidden', 'length', 'max'=>3),
            array('id, pid, position, parents, type, hidden, alias, title, meta_t, meta_d, meta_k, description, URL, content', 'safe'),
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
			'parents_page' => 'Родители страницы',
			'type' => 'Тип',
			'hidden' => 'Скрыто',
			'alias' => 'Alias',
			'title' => 'Заголовок',
            'meta_t' => 'Meta заголовок',
			'meta_d' => 'Meta описание',
			'meta_k' => 'Meta слова',
			'description' => 'Описание страницы',
			'URL' => 'Url',
            'prev_id'   => "Сортировка",
			'content' => 'Содержание',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('position',$this->position);
		$criteria->compare('parents',$this->parents,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('hidden',$this->hidden,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
        $criteria->compare('meta_t',$this->meta_t,true);
		$criteria->compare('meta_d',$this->meta_d,true);
		$criteria->compare('meta_k',$this->meta_k,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('URL',$this->URL,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Page the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /** Возвращает меню страниц
     *
     * @param active page id
     * @param mode {admin, front}
     *@return Array pages
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
        $pages=self::model()->findAll($criteria);
       // $pages = Yii::app()->db->createCommand()->select('id, pid, title')->from("{{page}}")->order("level")->queryAll();
        //получаем всех родителей активной страницы
        if((int)self::$active_id > 0){
            $active_page = self::model()->findByPk(self::$active_id);
            if(!empty($active_page)){
                self::$active_parents = explode('/', $active_page->parents);
            }
        }

        $items = array();
        $itemsIndex = array();
        foreach($pages as $page){
            if($page['pid'] == 0) {
                $items[$page['id']] = self::itemMenu($page, $mode);
                $items[$page['id']]['items'] = array();
                $itemsIndex[$page['id']] = &$items[$page['id']];
            } else {
                $itemsIndex[$page['pid']]['items'][$page['id']] = self::itemMenu($page, $mode);
                $itemsIndex[$page['id']] = &$itemsIndex[$page['pid']]['items'][$page['id']];
            }
        }
        self::$items =  $items;
        //echo "<pre>"; print_r($items); echo "</pre>";
        return self::$items;
    }

    /** Возвращает элемент массива меню страниц
     *
     *@return Array
     */
    public static function itemMenu($page, $mode='admin'){
        $item = array(
            'label'         => $page['title'],
            'url'           => ($mode == "admin") ? array('/admin/page/update', 'id' => $page['id']) : Yii::app()->createAbsoluteUrl("page/index", array('id' => $page["id"])),
            'itemOptions'   => array('id' => $page['id']),
        );
        if($mode == "admin"){
            if(self::$active_id == $page['id']){
                $item['active'] = true;
            }
            if(!in_array($page['id'], self::$active_parents)){
                $item['itemOptions']['class'] = 'closed';
            }
        }else{
            $item['itemOptions']['class'] = ($page['level'] > 1) ? "menu-level-more-1" : "menu-level-1";
        }
        return $item;
    }

    public static function itemList($active_id = 0){
        self::$active_id = $active_id;
        if(!empty(self::$list)){
            return self::$list;
        }
        $pages = self::menu("admin", $_GET['id']);

        self::$list[0] = 'root';
        self::formingList($pages);
        return self::$list;
    }

    /** Рекурсивная функция для получения одномерного массива страниц для dropDownList
     *
     * @param Array
     * @param int
     */
    public static function formingList($pages, $level=0){
        foreach($pages as $page_id => $page){
            if($page_id != self::$active_id){
                self::$list[$page_id] = str_repeat('..', $level) . $page['label'];
                if(!empty($page['items'])){
                    self::formingList($page['items'], $level + 1);
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
        self::preparePageLevel($this->id, $parent, $this->prev_id);
        //в зависимости от типа страницы оставляем или URL или content
       // echo "<pre>"; print_r($this); echo "</pre>";
        switch($this->type){
            case 'sratic':
                $this->URL = "";
                break;
            case 'URL':
                $this->content = "";
                break;
        }
        //определяем hidden
        if($this->hidden != "yes"){
            $this->hidden = "no";
        }
       // echo "<pre>"; print_r($this); echo "</pre>";
        return parent::beforeSave();
    }

    /** подготавливает уровень страницы и родителей страницы и обновляет потомков, parents был '/1/2/17/18/' стал '/3/17/18', если родителя 17 перенесли в узел 3
     *
     * @param int
     * @param mixed(int, object)
     * @return boolen
     */
    public function preparePageLevel($id, $parent, $prev_id){
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
            $prev_page = self::model()->find($criteria);
            $this->position = $prev_page->position + 1;
        }
        //обновляем позиции страниц текущего уровня
        $sql = "UPDATE {{page}} SET position = position+1 WHERE pid={$parent_id} AND position >= {$this->position}";
        Yii::app()->db->createCommand($sql)->execute();
        $this->level = (empty($parent)) ? 1 : $parent->level + 1;
        //подготавливаем данные для обновления
        $this->parents = (empty($parent)) ? '/' : "{$parent->parents}{$parent->id}/";
        //страница не была пересена
        if(!$this->isNewRecord){
            $page = self::model()->findByPk($id);
            if($parent_id == $page->pid){
                return true;
            }

            //обновляем потомков
            $diffLevel = $page->level - $this->level;
            $oldParentsForChilddren = "{$page->parents}{$id}/";
            $newParentsForChilddren = "{$this->parents}{$id}/";
            //echo $oldParentsForChilddren . "___" . $newParentsForChilddren; exit();
            $sql = "UPDATE {{page}} SET level=level-{$diffLevel}, parents=REPLACE(parents, '{$oldParentsForChilddren}', '{$newParentsForChilddren}') WHERE parents LIKE '%{$oldParentsForChilddren}%'";
            return Yii::app()->db->createCommand($sql)->execute();
        }

     }

    /** расчет максимальной позиции
     *
     * @param int
     * @return int
     */
    public function calculateMaxPosition($pid){
        $maxPosition = self::model()->findBySql("SELECT MAX(position) as position FROM {{page}} WHERE pid=:pid", array(":pid" => $pid));
        return ($maxPosition->position >= 0) ? $maxPosition->position + 1 : 1;
    }

    /** перемещаем страницу
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
            $this->preparePageLevel($params["id"], $params["parent_id"], $params["prev_id"]);
        }
        return (empty($errors)) ? true : false;
    }

    public static function getAllVisiblePage(&$pageByAlias, &$pageById){
        if(empty(self::$allVisiblePageById)){
            $criteria = new CDbCriteria;
            $criteria->select = "id, alias, parents, URL, type";
            $criteria->condition = "hidden='no'";
            $pages = self::model()->findAll($criteria);
            foreach($pages as $page){
                self::$allVisiblePageByAlias[$page["alias"]] = $page;
                self::$allVisiblePageById[$page["id"]] = $page;
            }

        }
        $pageByAlias = self::$allVisiblePageByAlias;
        $pageById = self::$allVisiblePageById;
    }
}
