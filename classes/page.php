<?php
class page
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }
    function getUserData($data)
    {
        // 從資料庫 public.user 的 table 裏面取出所有的 user_name
        $sql = "SELECT public.user.user_id, user_name, account, password, gender, telephone, address, email, birthday, public.user.user_number, picture, public.student.major, public.student.grade
                FROM public.user
                LEFT JOIN public.student
                ON public.student.user_id = public.user.user_id
                WHERE public.user.user_id = :user_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getCourse($data)
    {
        // 從資料庫 public.course 的 table 裏面取出所有的 course_id, course_name
        $sql = "SELECT course_id, course_name, semester_id, credit, classtime, year_id, type_id
                FROM public.course
                WHERE public.course.year_id = :year_id AND public.course.semester_id = :semester_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":year_id", $data['year'], PDO::PARAM_STR);
        $stmt->bindValue(":semester_id", $data['semester'], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getChat()
    {
        $sql = "SELECT username, content, time, channel
                FROM public.chat
                ORDER BY time
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addChat($data)
    {
        $sql = "INSERT INTO public.chat(username, content, channel)
                 VALUES (:username, :content, :channel) 
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $data["username"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["content"], PDO::PARAM_STR);
        $stmt->bindValue(':channel', $data["channel"], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getAnnouncement($data)
    {
        //從資料庫取得個科目公告
        if ($data["subject"] != 0) {
            $sql = "SELECT announcement_id , public.announcement.course_id , title , content , post_user , time_now 
                    FROM public.announcement
                    LEFT JOIN public.course
                    ON public.course.course_id = public.announcement.course_id
                    WHERE public.announcement.course_id = :subject
                    ORDER BY time_now DESC 
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':subject', $data["subject"], PDO::PARAM_INT);
        } else {
            $sql = "SELECT announcement_id , public.announcement.course_id , title , content , post_user , time_now 
                    FROM public.announcement
                    LEFT JOIN public.course
                    ON public.course.course_id = public.announcement.course_id
                    ORDER BY time_now DESC 
                    ";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addAnnouncement($data)
    {

        $sql = "INSERT INTO public.announcement(course_id, title, content , post_user)
                 VALUES (:course, :title, :content , :post_user) 
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':course', $data["subject"], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["content"], PDO::PARAM_STR);
        $stmt->bindValue(':post_user', $data["post_user"], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function updateAnnouncement($data)
    {

        $sql = "UPDATE public.announcement
                SET content = :content, title = :title
                WHERE public.announcement.announcement_id = :announcement_id
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':announcement_id', $data["announcement_id"], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["content"], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function deleteAnnouncement($data)
    {
        $sql = "DELETE FROM public.announcement
                WHERE public.announcement.announcement_id = :announcement_id
            ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':announcement_id', $data["announcement_id"], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }

    function getMaterial($data)
    {
        if ($data["subject"] != 0) {
            $sql = "SELECT public.material.material_id, public.material.course_id, public.material.title, public.course.course_name, public.material.content, public.material.time_now
                    FROM public.material
                    LEFT JOIN public.course
                    ON public.course.course_id = public.material.course_id
                    WHERE public.material.course_id = :subject
                    ORDER BY 1
                ;";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':subject', $data["subject"], PDO::PARAM_INT);
        } else {
            $sql = "SELECT public.material.material_id, public.material.course_id, public.material.title, public.course.course_name, public.material.content, public.material.time_now
                    FROM public.material
                    LEFT JOIN public.course
                    ON public.course.course_id = public.material.course_id
                    ORDER BY 1
                ;";
            $stmt = $this->db->prepare($sql);
        }

        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addMaterial($data)
    {
        $sql = "INSERT INTO public.material(course_id, title, content)
                VALUES (:course, :title, :content) 
            ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':course', $data["subject"], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["newfile"], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function updateMaterial($data)
    {
        $sql = "UPDATE public.material
                SET title = :title, content = :content
                WHERE public.material.material_id = :material_id 
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':title', $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["newfile"], PDO::PARAM_STR);
        $stmt->bindValue(':material_id', $data["material_id"], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function deleteMaterial($data)
    {
        $sql = "DELETE FROM public.material
                WHERE public.material.material_id = :material_id
            ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':material_id', $data["material_id"], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function getAssessment($data)
    {
        if ($data["subject"] != 0) {
            $sql = "SELECT public.assessment.assessment_id, public.assessment.course_id, public.assessment.title, public.assessment.content, public.assessment.finish_state, public.assessment.open_date,public.assessment.close_date,public.course.course_name
                    FROM public.assessment
                    LEFT JOIN public.course
                    ON public.course.course_id = public.assessment.course_id
                    WHERE public.assessment.course_id = :subject
                    ORDER BY 1
                ;";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':subject', $data["subject"], PDO::PARAM_INT);
        } else {
            $sql = "SELECT public.assessment.assessment_id, public.assessment.course_id, public.assessment.title, public.assessment.content, public.assessment.finish_state, public.assessment.open_date,public.assessment.close_date,public.course.course_name
                    FROM public.assessment
                    LEFT JOIN public.course
                    ON public.course.course_id = public.assessment.course_id
                    ORDER BY 1
                ;";
            $stmt = $this->db->prepare($sql);
        }

        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function deleteAssessment($data)
    {
        $sql = "DELETE FROM public.assessment
                WHERE public.assessment.assessment_id =  :assessment_id
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':assessment_id', $data["assessment_id"], PDO::PARAM_INT);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function addAssessment($data)
    {
        $sql = "INSERT INTO public.assessment(course_id, close_date, title, content )
                 VALUES (:course, :close_date, :title, :content ) 
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':course', $data["subject"], PDO::PARAM_INT);
        $stmt->bindValue(':close_date', $data["close_date"], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["content"], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
    function updateAssessment($data)
    {

        $sql = "UPDATE public.assessment
                SET content = :content, title = :title
                WHERE public.assessment.assessment_id = :assessment_id
                ;";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':assessment_id', $data["assessment_id"], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data["content"], PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $query;
    }
}
