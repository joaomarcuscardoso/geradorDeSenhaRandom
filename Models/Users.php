<?php 
class Users extends Model {
    public function validateLogin($email, $password) {
        $array =array();


        $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":email", $email);
        $sql->bindValue(":password", $password);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetch();
            
            $password_token = md5(time().rand(0,999).$array['id'].time());

            $sql = "UPDATE  users SET password_token = :password_token WHERE id = :id_user";    
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":password_token", $password_token);
            $sql->bindValue(":id_user", $array['id']);
            $sql->execute();

            $array['users']['password_token'] = $password_token;
            $_SESSION['token'] = $password_token;
        }
        return $array;
    }

 
    public function isLogged() {
        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $sql = "SELECT id FROM users WHERE password_token = :token";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':token', $token);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                 return true;
            }
        }

        return false;
    }

    public function getUser() {
        $array = array();

        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $sql = "SELECT * FROM users WHERE password_token = :token";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":token", $token);
            $sql->execute();
            
            print_r($token);
            exit;
            if($sql->rowCount() > 0) {
                $array = $sql->fetch();
            }
        } 

        return $array;

    }

    public function getId() {
        $id_user = array();

        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $sql = "SELECT id FROM users WHERE password_token = :token";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":token", $token);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $id_user = $sql->fetch();
                $id_user = $id_user['id'];
            }
        }

 
        return $id_user;
    }


    public function getEmail($email) {
        $array = array();

        $sql = "SELECT email FROM users WHERE email = :email";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":email", $email);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetch();
        }

        return $array;
    }


    public function createForget_password($email) {
        $hash = 0;

        /*Caso o usuário tenha esquecido a senha dele*/
        $sql = "SELECT id FROM users WHERE email = :email";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":email", $email);
		$sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $id = $array['id'];


            date_default_timezone_set('America/Sao_Paulo');
            $hash = md5(time().round(0,9999).rand(0,9999).rand(0,9999));



            $sql = "INSERT INTO users_forget (id_user, hash, expired_data) VALUES (:id_user, :hash, :expired_data)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":id_user", $id);
            $sql->bindValue(":hash", $hash);
            $sql->bindValue(":expired_data", date('Y-m-d H:i', strtotime('+4 hours')));
            $sql->execute();





        }

        return $hash;
    }


    public function getTokenForgetPassword() {
        $checked = false;
        $sql = "SELECT * FROM users_forget WHERE hash = :token";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":token", $_SESSION['token']);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $checked = true;
        }   
        return $checked;
    }

    public function changedPassword($password) {
        $array = array();
        $sql = "SELECT id_user FROM users_forget WHERE hash = :token";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":token", $_SESSION['token']);
        $sql->execute();

        if($sql->rowCount()) {
            $array = $sql->fetch();
            $sql = "UPDATE users SET password = :password WHERE id = :id_user";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":password", md5($password));
            $sql->bindValue(":id_user", $array['id_user']);
            $sql->execute();

        }

    }

}