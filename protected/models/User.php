<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $name
 * @property string $secondname
 * @property string $surname
 * @property string $login
 * @property string $password
 * @property integer $created
 * @property string $role
 * @property string $status
 * @property string $email
 */
class User extends CActiveRecord
{

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_BANNED = 'banned';
    public $verifyCode;
    public $password_repeat;
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, surname, login, password, email', 'required'),
            array('role', 'required', 'on' => 'create, update'),
            array('login, email', 'unique'),
            array('email','email'),
			array('name, secondname, surname, login, password, email', 'length', 'max'=>255),
			array('role', 'length', 'max'=>5),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'registration'),
            array('password_repeat', 'required', 'on' => 'registration'),
			array('password', 'compare', 'compareAttribute'=>'password_repeat', 'on' => 'registration, password', 'message' => 'Пароли не совпадают'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
            array('id, name, secondname, surname, login, password, created, role, email, status', 'safe'),
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
			'name' => 'Имя',
			'secondname' => 'Отчество',
			'surname' => 'Фамилия',
			'login' => 'Логин',
			'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
			'created' => 'Дата создания',
			'status' => 'Статус',
            'role' => 'Роль',
			'email' => 'Email',
            'verifyCode' => "Код с картинки",
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('secondname',$this->secondname,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function beforeSave(){
        if($this->isNewRecord){
            $this->created = time();
            //выставляем роль
            if(empty($this->role)){
                $this->role = 'user';
            }
            //выставляем secure_code
            if(Yii::app()->user->isGuest){
                $this->secure_code = md5(time().$this->email);
                $this->status = 'no_verify';
            }
        }
        if($this->scenario == "registration" || $this->scenario == "password"){
            $this->password = md5($this->password);
        }

        return parent::beforeSave();
    }
}
