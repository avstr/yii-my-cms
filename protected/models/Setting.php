<?php

/**
 * This is the model class for table "{{setting}}".
 *
 * The followings are the available columns in table '{{setting}}':
 * @property integer $id
 * @property integer $sizeSideNewsPicture
 * @property integer $sizeSideSmallNewsPicture
 */
class Setting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{setting}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sizeSideNewsPicture, sizeSideSmallNewsPicture, email', 'required'),
			array('sizeSideNewsPicture, sizeSideSmallNewsPicture', 'numerical', 'integerOnly'=>true),
            array('email', 'email'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sizeSideNewsPicture, sizeSideSmallNewsPicture, hiddenNewResponse, widthServicePicture, heightServicePicture, widthSmallServicePicture, heightSmallServicePicture, email', 'safe'),
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
			'sizeSideNewsPicture' => 'Размер стороны картинки для новостей, рх',
			'sizeSideSmallNewsPicture' => 'Размер стороны маленькой картинки для новостей, рх',
            'widthServicePicture' => 'Ширина картинки для услуги, рх',
            'heightServicePicture' => 'Высота картинки для услуги, рх',
            'widthSmallServicePicture' => 'Ширина превью картинки для услуги, рх',
            'heightSmallServicePicture' => 'Высота превью картинки для услуги, рх',
            'hiddenNewResponse' => 'Скрыть отзыв при добавлении отзыва пользователем',
            'email' => 'Email сайта'
		);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Setting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
