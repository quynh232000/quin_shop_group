<?php
include_once "lib/database.php";
include_once "helpers/tool.php";
include_once "model/entity.php";
include_once "lib/session.php";
?>
<?php
class Traffic
{
    private $db;
    private $tool;
    private $db_name;
    private $response;

    public function __construct()
    {
        $this->db = new Database();
        $this->tool = new Tool();
        $this->db_name = DB_NAME;
    }

    public function set_traffic()
    {
        if (!isset($_SESSION['SET_TRAFFICT'])) {
            $ip_data = json_decode(self::get_data('https://api64.ipify.org?format=json'), true) ?? "";
            $clientIP = $ip_data['ip'];

            // Get additional IP information
            $ip_info = json_decode(self::get_data("http://ipinfo.io/$clientIP/json"), true);


            $ip = $ip_info['ip'];
            $location = $ip_info['loc'];
            $type = $ip_info['city'];
            $this->db->insert("INSERT INTO traffic (type,ip_address,location) values('$type','$ip','$location')");
            $_SESSION['SET_TRAFFICT'] = true;
            return new Response(true, 'success');
        } else {
            return new Response(false, 'fail');
        }
    }
    // traffic
    function get_data($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    // Function to make a POST request
    function post_data($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }



}



?>