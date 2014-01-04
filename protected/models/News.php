<?php

/**
 * This is the model class for table "{{news}}".
 *
 * The followings are the available columns in table '{{news}}':
 * @property integer $id
 * @property string $date
 * @property string $title
 * @property string $shorh_desc
 * @property string $description
 * @property string $picture
 */
class News extends CActiveRecord
{
    public $image; // атрибут для хранения загружаемой картинки новости
    public $del_img; // атрибут для удаления уже загруженной картинки

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{news}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, title, shorh_desc, description', 'required'),
            array('del_img', 'boolean'),
            array('image', 'file',
                'types'=>'jpg, gif, png',
                'maxSize'=>1024 * 1024 * 5, // 5 MB
                'allowEmpty'=>'true',
                'tooLarge'=>'Файл весит больше 5 MB. Пожалуйста, загрузите файл меньшего размера.',
            ),
			array('title, image', 'length', 'max'=>255),
            array('hidden', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, date, title, shorh_desc, description, hidden', 'safe', 'on'=>'search'),
            array('id, date, title, shorh_desc, description, picture, hidden', 'safe'),
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
			'date' => 'Дата',
			'title' => 'Заголовок',
			'shorh_desc' => 'Краткое описание',
			'description' => 'Описание',
            'image' => 'Картинка к новости',
            'del_img'=>'Удалить картинку?',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('shorh_desc',$this->shorh_desc,true);
        $criteria->compare('hidden',$this->hidden,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function material_image($id, $create_year, $image, $title, $width='150', $class='material_img')
    {
        if(isset($image) && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::app()->urlManager->baseUrl.'/images/news/'.$create_year.'/'.$image))
            return CHtml::image(Yii::app()->urlManager->baseUrl.'/images/news/'.$create_year.'/'.$image, $title,
                array(
                    'width'=>$width,
                    'class'=>$class,
                ));
        else
            return CHtml::image(Yii::app()->getBaseUrl(true).'/images/pics/noimage.gif','Нет картинки',
                array(
                    'width'=>$width,
                    'class'=>$class
                ));
    }

    public function beforeSave(){
        //определяем hidden
        if($this->hidden != "yes"){
            $this->hidden = "no";
        }
        return parent::beforeSave();
    }
}
