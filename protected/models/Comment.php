<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property integer $pid
 * @property string $module_name
 * @property integer $object_id
 * @property integer $user_id
 * @property string $date
 * @property string $title
 * @property string $comment
 * @property string $hidden
 */
class Comment extends CActiveRecord
{

    public static $commentsForObject;

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, comment', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, parents, level, module_name, object_id, user_id, date, title, comment, hidden', 'safe'),
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
            'user' => array(
                self::BELONGS_TO,
                'User',
                'user_id'
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => 'id родителя',
			'module_name' => 'Название модуля',
			'object_id' => 'id объекта',
			'user_name' => 'Имя пользователя',
			'date' => 'Дата',
			'title' => 'Заголовок',
			'comment' => 'Комментарий',
			'hidden' => 'Скрыто',
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
		$criteria->compare('module_name',$this->module_name,true);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('hidden',$this->hidden,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Возвращает список комментариев, в виде многомерного массива.
     * @param string имя модуля (напимер news, user).
     * @param int id объекта.
     * @return array многомерный список комментариев.
     */
    public static function listForObject($moduleName, $objectId){
        $criteria = new CDbCriteria();
        $criteria->alias = 'c';
        $criteria->with = array('user' => array('select' => 'user.name, user.surname'));
        $criteria->condition = "c.module_name=:module_name AND c.object_id=:object_id AND c.hidden=:hidden";
        $criteria->join = "LEFT JOIN `" . Yii::app()->db->tablePrefix . "user` AS u ON c.user_id = u.id";
        $criteria->params = array(
            ':module_name' => $moduleName,
            ':object_id' => $objectId,
            ':hidden' => 'no',
        );
        $criteria->order = "c.level ASC, c.date ASC";
        $comments = self::model()->findAll($criteria);
        //echo "<pre>"; print_r($comments); echo "</pre>";
        $items = array();
        $itemsIndex = array();
        foreach($comments as $comment){
            if($comment['pid'] == 0) {
                $items[$comment['id']] = self::item($comment);
                $items[$comment['id']]['items'] = array();

                $itemsIndex[$comment['id']] = &$items[$comment['id']];
            } else {
                $itemsIndex[$comment['pid']]['items'][$comment['id']] = self::item($comment);
                $itemsIndex[$comment['id']] = &$itemsIndex[$comment['pid']]['items'][$comment['id']];
            }
        }
        self::$commentsForObject = $items;
        //echo "<pre>"; print_r($items); echo "</pre>";
        return $items;
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave(){
        //echo "<pre>"; print_r($_POST); echo "</pre>";
        if(isset($_POST['mode']) && ($_POST['mode'] == 'admin') && Yii::app()->user->role === "admin"){
            if($this->hidden != "yes"){
                $this->hidden = "no";
            }
        }else{
            $this->hidden = "no";
            $this->date = date("Y-m-d  H:i:s");
            $this->user_id = Yii::app()->user->id;
            $parent = array();
            if($this->pid != 0){
                $parent = $this->findByPk($this->pid);
            }
            if(empty($parent)){
                $this->parents = '/';
                $this->level = 1;
            }else{
                $this->parents = "{$parent['parents']}{$parent['id']}/";
                $this->level = $parent['level'] + 1;
            }
        }
        //echo "<pre>"; print_r($this); echo "</pre>"; exit;
        return parent::beforeSave();
    }

    /** Возвращает элемент массива меню страниц
     * @param object
     * @return Array
     */
    public static function item($comment){
        return array(
            'id' => $comment['id'],
            'title' => $comment['title'],
            'comment' => $comment['comment'],
            'level' => $comment['level'],
            'date' => $comment['date'],
            'user_name' => $comment->user->name,
            'user_surname' => $comment->user->surname,
        );
    }

    /** Удаляет комментарий и детей
     * @param int
     * @return boolean
     */
    public static function deleteComment($id){
        if((int)$id > 0){
            $command = Yii::app()->db->createCommand();
            return $command->delete(Yii::app()->db->tablePrefix . 'comment', 'parents LIKE "%/' . $id . '/%" OR id=:id', array(':id' => $id));
        }
        return false;
    }
}
