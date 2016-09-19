<?php
class SignupController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new SignupModel();
    }
    
    public function index()
    {

        Session::setFlash('Заполните форму для регистрации');

        $this->data['message'] = '';
        $this->data['color'] = '';
        $this->data['email'] = '';

        if(Session::get('login')){
            Router::redirect('/user');
        }

        if(isset($_POST['password']) && isset($_POST['email']) && isset($_POST['passwordRepeat']) ){
            $validate = new Validate($_POST);
            $this->data['email'] = $_POST['email'];
            
            if ( $validate->getFail()!= "") {
                $this->data['message'] = 'В Вашей форме найдены ошибки: <br>'.$validate->getFail().'';
                $this->data['color'] = 'red';
            } else {
                /*Новый вариант проверки email и создания нового пользователя*/
                $email = Validate::fixString($_POST['email']);
                $avaliable_email = $this->model->emailCheck($email);
                if (!$avaliable_email){
                    $this->data['message'] = 'Пользовать с email: '.$email.' уже зарегистрирован';
                    $this->data['color'] = 'red';
                    $this->data['color'] = 'red';
                }else{
                    $password = Validate::fixString($_POST['password']);
                    $new_user = $this->model->userCreate($email, $password);
                    if(!$new_user){
                        $this->data['message'] = 'Произошла ошибка при регистрации. Попробуйте пройти регистрацию заново';
                        $this->data['color'] = 'red';
                    }
                    else {
                        $this->data['message'] = 'Вы успешно зарегистрировались. Теперь Вы можете зайти в свой личный кабинет';
                        $this->data['color'] = '#a0c437';
                        $this->data['email'] = '';
                    }
                }
                /*Завершение нового варианта*/
            }
        }
    }

}
