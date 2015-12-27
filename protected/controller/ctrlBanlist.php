<?php

class ctrlBanlist extends ctrl
{
    //поля таблицы
    //по хорошему их лучше вынести в отдельную модель
    private $fields = array('id',
                            'ip',
                            'reason',
                            'block_date',
                            'unblock_date',
                            'is_blocked'
    );
    
    private $log;
    private $error_msg;
    
    public function __construct(){
        parent::__construct();
        $this->log = log::getInstance();
        $valid_passwords = array ("admin" => "admin");
        $valid_users = array_keys($valid_passwords);
        
        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];
        
        $this->isAdmin = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
    } 
    
    public function index() {     
        if ($this->isAdmin)
            $this->out('banlist.php');
        else {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die ("Not authorized");
        }
        exit();
    }
    
    public function getAll() {
        if ($this->isAdmin)
            return $this->db->query('SELECT * FROM banlist ORDER BY block_date DESC')->all();
        return false;
    }
    
    public function add() {
        if ($this->isAdmin) {
        foreach ($_POST as $key=>$val) {
            if (!$this->validateField($key, $val)) {
                echo array2json(array('error'=>"Поле $key не прошло валидацию"));
                return false;
            }
            $$key = $val;
        }
        /*
         * Если дата блокировки < даты разблокировки - пользователь разблокирован
         * Во всех остальных случаях - заблокирован
         */
        $is_blocked = 1;
        $d1 =  new DateTime($block_date);
        $block_date_ins = $d1->format('Y-m-d H:i:s');
        //Дата разблокировки может быть пустой
        if (!empty($unblock_date)) {
            $d2 =  new DateTime($unblock_date);
            $unblock_date_ins = $d2->format('Y-m-d H:i:s');
            if ($d2>$d1)
                $is_blocked = 0;  
        }
        else $unblock_date_ins = '0000-00-00 00:00:00';
        
        
        if ($this->db->query('INSERT INTO banlist (ip, reason, block_date, unblock_date, is_blocked) 
                          VALUES (?, ?, ?, ?, ?)',
                          $ip, $reason, $block_date_ins, $unblock_date_ins, $is_blocked)) {

            $this->log->write("Добавлена новая запись: $ip, $reason, $block_date, $unblock_date");
            echo array2json(array('id'=>$this->db->insert_id(),
                                   'ip'=>$ip,
                                   'reason'=>$reason,
                                   'block_date'=>$block_date,
                                   'unblock_date'=>$unblock_date,
                                   'is_blocked'=>$is_blocked 
            ));
        }
        else 
            echo array2json(array('error'=>$this->error_msg));
        
    }
    }
    
    public function delete($user_id){
        if ($this->isAdmin && $this->validateField('id', $user_id)) {
            $this->db->query('DELETE FROM banlist WHERE id = ? LIMIT 1', $user_id);
            $this->log->write("Удалена запись id: $user_id");
        }     
        
        return false;
    }
    
    //Апдейт поля таблицы $field
    public function update($field){

        $field_value = $_POST['field_value'];
        $id = $_POST['id'];
        if ($this->isAdmin && 
            $this->validateField($field, $field_value) &&
            $this->validateField('id', $id))
        {
            //эта часть запроса общая для всех
            $query = "UPDATE banlist SET $field = ?";
            
            $d1_return = NULL;
            $d2_return = NULL;
            $is_blocked = isset($_POST['is_blocked'])?$_POST['is_blocked']:NULL;
            
            /*  Если пользователь на данный момент разблокирован, 
            *   а мы хотим установить дату блокировки > даты разблокировки
            *   => нужно заблокировать пользователя, а дату разблокировки поставить NULL.
            *   
            *   Если же пользователь на данный момент заблокирован, а мы хотим поставить 
            *   дату разблокировки > даты блокировки
            *   => пользователя нужно разблокировать.
            */
            if (($field === 'block_date' || $field === 'unblock_date') &&
                $this->validateField('block_date', $_POST['block_date']) &&
                $this->validateField('unblock_date', $_POST['unblock_date'])) {
                    
                   $d1 = new DateTime($_POST['block_date']);
                   $d2 = new DateTime($_POST['unblock_date']);
                   if ($d1 > $d2) {
                       $query .= ", unblock_date = NULL, is_blocked = 1";
                       $is_blocked = 1;
                   }    
                   else {
                       $query .= ", is_blocked = 0";
                       $is_blocked = 0;
                   }        
                   $d1_return = $d1->format('d.m.Y H:i:s');
                   $d2_return = $d2->format('d.m.Y H:i:s');
                   
            }
            //Если пользователь разблокирован - устанавливаем дату разблокировки на текущую,
            // заблокирован - дату блокировки на текущую, дату разблокировки удаляем:
            if ($field === 'is_blocked') {
                $date = new DateTime();
                $insert_date = $date->format('Y-m-d H:i:s');
                $return_date = $date->format('d.m.Y H:i:s');
                $query = ($_POST['field_value'] == 0)?$query.", unblock_date = '$insert_date'":
                                       $query.", block_date = '$insert_date', unblock_date = NULL";
                
            }
            
            $query .= " WHERE id = ?";
            
            if ($field === 'block_date' || $field === 'unblock_date') {
                $date = new DateTime($field_value);
                $field_value = $date->format('Y-m-d H:i:s');
            }
            $this->db->query($query,
                             $field_value, $id);

            $this->log->write("Изменена запись id: $id, поле: $field, значение: $field_value");            
            
            if ($field === 'is_blocked')
                echo array2json(array('date'=>$return_date));
            else 
                echo array2json(array('field'=>$field_value,
                                      'block_date'=>$d1_return,
                                      'unblock_date'=>$d2_return,
                                      'is_blocked'=>$is_blocked
                                       ));
        }
        else
            echo array2json(array('error'=>$this->error_msg));
    }
    
    //Проверяет входящие данные 
    private function validateField($field, $value) {
        if (in_array($field, $this->fields)) {
            // переписать
            switch($field){
            case 'is_blocked':
                if ($value==0 || $value==1) 
                    return true; 
                    break;
            case 'id':
                if (is_numeric($value))
                    return true;
                    break;
            case 'ip':
                if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
                    $this->error_msg = 'Неправильный ip адрес';
                    return false;
                }
                else
                    return true;
                    break;
            case 'reason':
                if (strlen($value) <= 255)
                    return true;
                    break;
           
            case 'unblock_date':
                if($value==NULL)
                    return true;
            case 'block_date':
                $format = "d.m.Y H:i:s";
                $d = DateTime::createFromFormat($format, $value);
                
                if (!$d || $d->format($format) != $value) {
                    $this->error_msg = 'Неверный формат даты';
                    return false;
                }
                else 
                    return true;
                    break;
                
            }
        }   
        return false; 
    }
}

?>