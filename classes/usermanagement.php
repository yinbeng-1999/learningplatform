<?php

use PHPMailer\PHPMailer\PHPMailer;

class usermanagement
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }
    function getRole($data)
    {
        $sql = "SELECT role_id, role_name
                FROM public.role    
                ORDER BY 1 ASC
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getUserManagement($data)
    {
        $sql = "SELECT public.user.user_id, user_name, account, password, email, user_number, picture, public.user_role.role_id
                FROM public.user
                LEFT JOIN public.user_role
                ON public.user_role.user_id = public.user.user_id
                ORDER BY 1 ASC
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addUserManagement($data)
    {
        $sql = "INSERT INTO public.user(user_name, email)
                VALUES (:user_name, :email)
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_name", $data['user_name'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->execute();
        $result = array(
            "user_id" => $this->db->lastInsertId()
        );
        return $result;
    }
    function addUserRole($data)
    {
        $sql = "INSERT INTO public.user_role(user_id, role_id)
                VALUES (:user_id, :role_id)
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(":role_id", $data['role_id'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addStuRole($data)
    {
        $sql = "INSERT INTO public.student(user_id, user_number, grade, major)
                VALUES (:user_id, :user_number, :grade, :major)
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(":user_number", $data['user_number'], PDO::PARAM_STR);
        $stmt->bindValue(":grade", $data['grade'], PDO::PARAM_INT);
        $stmt->bindValue(":major", $data['major'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addTeachRole($data)
    {
        $sql = "INSERT INTO public.teacher(user_id, user_number)
                VALUES (:user_id, :user_number)
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(":user_number", $data['user_number'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function patchUserManagement($data)
    {
        $sql = "UPDATE public.user
                SET user_name = :user_name
                WHERE public.user.user_id = :user_id;
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_name", $data['user_name'], PDO::PARAM_STR);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function patchUserRole($data)
    {
        if ($data["role_id"] != 0) {
            $sql = "UPDATE public.user_role
                    SET role_id = :role_id
                    WHERE public.user_role.user_id = :user_id
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":role_id", $data['role_id'], PDO::PARAM_INT);
            $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        }
        $query = ["status" => "failed"];
        return $query;
    }
    function deleteUserManagement($data)
    {
        $sql = "DELETE FROM public.user
                WHERE public.user.user_id = :user_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
}
