<?php

    include($_SERVER['DOCUMENT_ROOT'] . "/api/lib/jwt.php");

    function check_cookie($page)
    {
        session_start();

        $pages_not_login = array("login", "register");
        $pages_need_login = array("profile", "postComment", "chat");

        if (in_array($page, $pages_not_login))
        {
            // Ban users who was already login but try to visit this page
            if (isset($_COOKIE['JWT']))
            {
                jwt_setUserID();
                if (isset($_SESSION['user_id']))
                {
                    header("Location: profile.php");
                    exit;
                }
            }
            // Bug
            else if (isset($_SESSION['user_id']) && !isset($_COOKIE['JWT'])) unset($_SESSION['user_id']);
        }
        else if (in_array($page, $pages_need_login))
        {
            if (!isset($_COOKIE['JWT']))
            {
                unset($_SESSION['user_id']);
                header("Location: login.php");
                exit;
            }
            else
            {
                jwt_setUserID();
                if (!isset($_SESSION['user_id']))
                {
                    header("Location: login.php");
                    exit;
                }
            }
        }
        else
        {
            if (isset($_COOKIE['JWT'])) jwt_setUserID();
            else unset($_SESSION['user_id']);
        }

    }

?>