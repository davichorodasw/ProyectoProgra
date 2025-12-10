<?php
class Utils
{
    public static function deleteSession($name) // elimina la variable de sesión
    {
        if (isset($_SESSION[$name])) {
            $_SESSION[$name] = null;
            unset($_SESSION[$name]);
        }
        return $name;
    }

    public static function isAdmin() // no da permiso a los usuarios normales a acceder a páginas de admins
    {
        $esAdmin = false;

        if (isset($_SESSION['identity']) && isset($_SESSION['identity']->rol)) {
            $esAdmin = ($_SESSION['identity']->rol == 'admin');
        } elseif (isset($_SESSION['user_rol'])) {
            $esAdmin = ($_SESSION['user_rol'] == 'admin');
        }

        if (!$esAdmin) {
            header("Location: " . BASE_URL);
            exit();
        }
        return true;
    }
}
