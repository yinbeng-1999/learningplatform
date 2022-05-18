<?php

use PHPMailer\PHPMailer\PHPMailer;

class user
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }
    function login($data)
    {
        $sql = "SELECT user_id, user_name, account, password
                FROM public.user
                WHERE public.user.account = :account AND public.user.password = :password";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":account", $data['account'], PDO::PARAM_STR);
        $stmt->bindValue(":password", $data['password'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        foreach ($query as $key => $id) {
            $_SESSION['id'] = $id['user_id'];
            break;
        }
        return $query;
    }
    function register($data)
    {
        $sql = "INSERT INTO public.user(user_name, account, password, email, user_number)
                SELECT :user_name, :account, :password, :email, :user_number
                WHERE NOT EXISTS (
                    SELECT *
                    FROM public.user
                    WHERE public.user.account = :account AND public.user.password = :password )
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":account", $data['account'], PDO::PARAM_STR);
        $stmt->bindValue(":password", $data['password'], PDO::PARAM_STR);
        $stmt->bindValue(":user_name", $data['user_name'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(":user_number", $data['user_number'], PDO::PARAM_STR);
        $stmt->execute();
        $result = array(
            "user_id" => $this->db->lastInsertId()
        );
        return $result;
    }
    function getPassword($data)
    {
        $sql = "SELECT password
                FROM public.user
                WHERE public.user.email = :email
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function sendMail($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $mail = new PHPMailer(true);

        try {
            // smtp
            $mail->CharSet = "UTF-8"; //設定郵件編碼
            $mail->SMTPDebug = 0; // 除錯模式輸出
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "thomas89321@gmail.com";
            $mail->Password = "NKNU410777008";
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";

            // email setting
            $mail->isHTML(true);
            $mail->Subject = "忘記密碼";
            $mail->Body = "用此信箱註冊的密碼： " . $password;
            $mail->setFrom($email, "LearningPlatform"); // 寄信人
            $mail->addAddress($email, "receiver");  // 收信人

            $mail->send();
        } catch (Exception $e) {
            return [
                "status" => $e,
            ];
        }
    }
    function getRolePermission($data)
    {
        $sql = "SELECT user_id, public.permission.permission_id, public.permission.permission_name, public.permission.router
                FROM public.user_role
                LEFT JOIN public.role_permission
                ON public.role_permission.role_id = public.user_role.role_id
                LEFT JOIN public.permission
                ON public.permission.permission_id = public.role_permission.permission_id
                WHERE public.user_role.user_id = :user_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
}
