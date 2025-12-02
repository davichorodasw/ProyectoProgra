<?php
class Utils {
    public static function deleteSession($name){
        if(isset($_SESSION[$name])){
            $_SESSION[$name] = null;
            unset($_SESSION[$name]);
        }
        return $name;
    }

    public static function isAdmin(){
        if(!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin'){
            header("Location: " . base_url); // Redirige si no es admin
            exit();
        }
        return true;
    }
}