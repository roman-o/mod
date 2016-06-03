<?php

class User extends Model{

    public function  getByEmail($email){
        $login = $this->db->escape($email);
        $sql = "select * from users where email = '{$email}' limit 1";
        $result = $this->db->query($sql);
        if( isset($result[0])){
            return $result[0];
        }
        return false;
    }

    public function singUp($data,$hash){
        if( !isset($data['email']) || !isset($data['pass'])){
            return false;
        }


        $email=$this->db->escape($data['email']);
        $pass=$this->db->escape($hash);


        $sql="INSERT INTO users SET email='{$email}',pass='{$pass}'";

        return $this->db->query($sql);
    }


    public function userProfile($login){
        if (!isset($login)){
           return false;
        }
        $login = $login=$this->db->escape($login);

        $sql = "Select email,login from users where login='{$login}'";
        return $this->db->query($sql);

    }


}