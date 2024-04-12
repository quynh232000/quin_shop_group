<?php
include_once "lib/database.php";
include_once "helpers/tool.php";
include_once "model/entity.php";
include_once "lib/session.php";
?>
<?php
class Conversation
{
    private $db;
    private $tool;
    private $response;

    public function __construct()
    {
        $this->db = new Database();
        $this->tool = new Tool();
    }

    // user message
    public function get_conversation_user($shop_id = '', $page = 1, $limit = 100)
    {
        if(Session::get('isLogin') ==false){
            return new Response(false,'Vui lòng đăng nhập');

        }
        $user_id = Session::get('id');
        $currentPage = ($page - 1) * $limit;
        
        $query = "WITH TB AS (
                SELECT
                    message.sender_id,
                    CASE
                        WHEN EXISTS (SELECT * FROM shop WHERE shop.uuid = message.sender_id)
                            THEN 'shop'
                        ELSE 'user'
                    END AS sender_type
                FROM message order by created_at DESC
            )
            SELECT distinct message.*, TB.sender_type,
            user.full_name as user_name, user.avatar user_image,user.id as user_id,shop.name as shop_name,shop.uuid as shop_uuid, shop.icon as shop_image
            FROM message
            JOIN TB ON message.sender_id = TB.sender_id
            LEFT JOIN shop ON message.sender_id = shop.uuid AND TB.sender_type = 'shop'
            LEFT JOIN user ON message.sender_id = user.id AND TB.sender_type = 'user'
            inner join conversation
            ON conversation.id = message.conversation_id
            WHERE
            conversation.shop_id = '$shop_id' AND conversation.user_id ='$user_id'
            ORDER BY message.created_at DESC
            LIMIT $currentPage, $limit
            ";
        $data = $this->db->select($query)->fetchAll();
        return new Response(true, 'success', array_reverse($data));
    }
    public function crete_message_user($shop_id, $message_text = "", $file = null)
    {

        if(Session::get('isLogin') ==false){
            return new Response(false,'Vui lòng đăng nhập');
        }
        $user_id = Session::get('id');

        $check_conversation = $this->db->select("SELECT * FROM conversation WHERE shop_id = '$shop_id' AND user_id = '$user_id'")->fetchAll();
        if (count($check_conversation) > 0) {
            $conversation_id = $check_conversation[0]['id'];
        } else {
            $this->db->insert("INSERT INTO conversation (shop_id,user_id) values('$shop_id','$user_id')");
            $conversation_id = $this->db->get_lastest_id();
        }
        $message_media = [];
        // if has media
        $count_media = isset($file) ? count($file['name']) : 0;

        if ($count_media > 0) {
            for ($i = 0; $i < $count_media; $i++) {
                $fileDir = "./assest/upload/" . 'message/';
                if (isset($file['error'][$i]) && $file['error'][$i] == 0) {
                    $fileName = basename($file['name'][$i]);
                    if (!file_exists($fileDir)) {
                        mkdir($fileDir, 0, true);
                    }
                    $fileNameNew = $this->tool->GUID() . "." . (explode(".", $fileName)[1]);
                    $fileDir = $fileDir . $fileNameNew;
                    if (move_uploaded_file($file['tmp_name'][$i], $fileDir)) {
                        $message_media[] = "message/$fileNameNew";
                    }
                }
            }
        }
        $message_media = json_encode($message_media);
        $this->db->insert("INSERT INTO message (conversation_id,sender_id,message_text,message_media)
            values('$conversation_id','$user_id','$message_text','$message_media')
        ");
        return self::get_conversation_user($shop_id);
    }

    // shop=================================

    function get_list_conversation()  {
        
        if(Session::get('isLogin') ==false){
            return new Response(false,'Vui lòng đăng nhập');

        }
        $user_id = Session::get('id');
        $shop_id = $this->db->select("SELECT id from shop where user_id = '$user_id'")->fetchColumn();
        // $shop_id = 1;
        $list_conversation = $this->db->select("SELECT * from (
            SELECT 
                c.id as conversation_id,
                c.user_id,
                c.shop_id,
                u.full_name, 
                m.id ms_id,
                u.avatar as user_avatar,
                m.message_text, 
                m.created_at as message_created_at,
                m.is_read,
                row_number() over (partition by c.id) as r 
            FROM 
                conversation as c
            INNER JOIN 
                user as u ON u.id = c.user_id
            left JOIN 
                (SELECT id,
                    conversation_id, 
                    sender_id, 
                    message_text, 
                    created_at,
                    is_read
                FROM 
                    message
                ORDER BY 
                    created_at DESC
                ) as m ON c.id = m.conversation_id AND m.sender_id = c.user_id
            WHERE 
                c.shop_id = '$shop_id'
            ORDER BY 
                c.created_at DESC
            ) Sample
            where Sample.r = 1; 
        ")->fetchAll();
        return new Response(true, 'success', $list_conversation);
    }
    public function get_conversation_shop($user_id = '', $page = 1, $limit = 10)
    {

        if(Session::get('isLogin') ==false){
            return new Response(false,'Vui lòng đăng nhập');

        }
        $user_auth_id = Session::get('id');
        $shop_id = $this->db->select("SELECT id from shop where user_id = '$user_auth_id'")->fetchColumn();
        // $shop_id = 1;
        // $user_id = 'E4B8ECF8-4A6D-4EEC-B16B-5B0F137E2AC3';
        $currentPage = ($page - 1) * $limit;
        $query = "WITH TB AS (
                SELECT
                    message.sender_id,
                    CASE
                        WHEN EXISTS (SELECT * FROM shop WHERE shop.uuid = message.sender_id)
                            THEN 'shop'
                        ELSE 'user'
                    END AS sender_type
                FROM message
            )
            SELECT distinct message.*, TB.sender_type,
            user.full_name as user_name, user.avatar user_image,user.id as user_id,shop.name as shop_name,shop.uuid as shop_uuid, shop.icon as shop_image
            FROM message
            JOIN TB ON message.sender_id = TB.sender_id
            LEFT JOIN shop ON message.sender_id = shop.uuid AND TB.sender_type = 'shop'
            LEFT JOIN user ON message.sender_id = user.id AND TB.sender_type = 'user'
            inner join conversation
            ON conversation.id = message.conversation_id
            WHERE
            conversation.shop_id = '$shop_id' AND conversation.user_id ='$user_id'
            ORDER BY message.created_at DESC
            LIMIT $currentPage, $limit
            ";
        $data = $this->db->select($query)->fetchAll();
        return new Response(true, 'success', array_reverse($data));
    }
    public function create_message_shop($user_id, $message_text = "", $file = null)
    {
        if (Session::get('isLogin') == false) {
            return new Response(false, 'Vui lòng đăng nhập');
        }
        $user_auth_id = Session::get('id');
        $shop_info = $this->db->select("SELECT  id,uuid from shop WHERE user_id =  '$user_auth_id'")->fetch();
        $shop_id = $shop_info['id'];
        $shop_uuid = $shop_info['uuid'];
        // $shop_id = 1;
        // $shop_uuid = "9E735629-1207-46CE-9414-334ECA90k82E";

        $check_conversation = $this->db->select("SELECT * FROM conversation WHERE shop_id = '$shop_id' AND user_id = '$user_id'")->fetchAll();
        if (count($check_conversation) > 0) {
            $conversation_id = $check_conversation[0]['id'];
        } else {
            $this->db->insert("INSERT INTO conversation (shop_id,user_id) values('$shop_id','$user_id')");
            $conversation_id = $this->db->get_lastest_id();
        }
        $message_media = [];
        // if has media
        $count_media = isset($file) ? count($file['name']) : 0;

        if ($count_media > 0) {
            for ($i = 0; $i < $count_media; $i++) {
                $fileDir = "./assest/upload/" . 'message/';
                if (isset($file['error'][$i]) && $file['error'][$i] == 0) {
                    $fileName = basename($file['name'][$i]);
                    if (!file_exists($fileDir)) {
                        mkdir($fileDir, 0, true);
                    }
                    $fileNameNew = $this->tool->GUID() . "." . (explode(".", $fileName)[1]);
                    $fileDir = $fileDir . $fileNameNew;
                    if (move_uploaded_file($file['tmp_name'][$i], $fileDir)) {
                        $message_media[] = "message/$fileNameNew";
                    }
                }
            }
        }
        $message_media = json_encode($message_media);
        $this->db->insert("INSERT INTO message (conversation_id,sender_id,message_text,message_media)
            values('$conversation_id','$shop_uuid','$message_text','$message_media')
        ");
        return self::get_conversation_shop($user_id);
    }
}



?>