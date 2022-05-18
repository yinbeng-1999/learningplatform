<?php
class mainpage
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }
    function getYear()
    {
        $sql = "SELECT year_id, name
                FROM public.year
                ORDER BY name DESC
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getSemester()
    {
        $sql = "SELECT semester_id, name
	            FROM public.semester    
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getChart($data)
    {
        $sql = "SELECT public.course.course_id, course_name, semester_id, year_id, type_id, credit, cs.score
                FROM (SELECT public.course_student.course_id, public.course_student.stu_id, public.course_student.score
                    FROM public.course_student
                    WHERE public.course_student.stu_id = 1
                )cs
                LEFT JOIN public.course
                ON public.course.course_id = cs.course_id
                LEFT JOIN public.student
                ON public.student.stu_id = cs.stu_id
                WHERE public.course.year_id = :year_id AND public.course.semester_id = :semester_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":year_id", $data['year'], PDO::PARAM_INT);
        $stmt->bindValue(":semester_id", $data['semester'], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getSchedule($data)
    {
        $sql = "SELECT public.course.course_id, course_name, semester_id, year_id, public.type.type_id, public.type.name as type, credit, cs.score, public.course.classdate, public.course.classtime, public.user.user_name
                FROM (SELECT public.course_student.course_id, public.course_student.stu_id, public.course_student.score
                    FROM public.course_student
                    WHERE public.course_student.stu_id = 1
                )cs
                LEFT JOIN public.course
                ON public.course.course_id = cs.course_id
                LEFT JOIN public.student
                ON public.student.stu_id = cs.stu_id
                LEFT JOIN public.type
                ON public.type.type_id = public.course.type_id
                LEFT JOIN public.course_teacher
                ON public.course_teacher.course_id = public.course.course_id
                LEFT JOIN public.teacher
                ON public.teacher.tec_id = public.course_teacher.tec_id
                LEFT JOIN public.user
                ON public.user.user_id = public.teacher.user_id
                WHERE public.course.year_id = :year_id AND public.course.semester_id = :semester_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":year_id", $data['year'], PDO::PARAM_INT);
        $stmt->bindValue(":semester_id", $data['semester'], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function patchUserData($data)
    {
        $sql = "UPDATE public.user
                SET user_name= :user_name, gender= :gender, telephone= :telephone, address= :address, email= :email, birthday= :birthday,picture=:picture
                WHERE public.user.user_id = :user_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(":user_name", $data['user_name'], PDO::PARAM_STR);
        $stmt->bindValue(":gender", $data['gender'], PDO::PARAM_STR);
        $stmt->bindValue(":telephone", $data['telephone'], PDO::PARAM_STR);
        $stmt->bindValue(":address", $data['address'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(":birthday", $data['birthday'], PDO::PARAM_STR);
        $stmt->bindValue(":picture", $data['photo'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
}
