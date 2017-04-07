<?php

header('Content-Type:application/json');
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
require_once("Rest.inc.php");

class API extends REST {

	public $data = "";

	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "*******";
	const DB = "motoread";

	private $db = NULL;

	public function __construct(){
			parent::__construct();
			$this->dbConnect();
		}

		private function dbConnect(){
			$this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD,self::DB);
		}

		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),404);
		}


		private function login(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}


			if (!isset($this->_request['email']) || !isset($this->_request['pwd'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{
				$email = $this->_request['email'];
				$password = $this->_request['pwd'];
			}


			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysqli_query($this->db,"SELECT token,id,email FROM users WHERE email = '$email' AND password = '".md5($password)."' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_assoc($sql);
						$res['status'] = 'success';
						$res['user_id'] = $result['id'];
						$res['email'] = $result['email'];
						$res['token'] = $result['token'];
						$res['msg'] = 'Logged in Successfully';
						$this->response($this->json($res), 200);
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Email address or Password does not match");
						$this->response($this->json($error), 200);
					}
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "Invalid Email address or Password");
				$this->response($this->json($error), 200);
			}
		}

		private function account(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($token)){
				$sql = mysqli_query($this->db,"SELECT id,email FROM users WHERE id = '$id' AND token = '$token' LIMIT 1");
				if(mysqli_num_rows($sql) > 0){
					$result = mysqli_fetch_assoc($sql);
					$res['status'] = 'success';
					$res['user_id'] = $result['id'];
					$res['email'] = $result['email'];
					$this->response($this->json($res), 200);
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Credentials");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "Invalid Credentials");
				$this->response($this->json($error), 200);
			}
		}

		private function signup()
		{
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['email']) || !isset($this->_request['pwd'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{
				$email = $this->_request['email'];
				$password = $this->_request['pwd'];
			}

			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$user_check = mysqli_query($this->db,"SELECT email FROM users WHERE email = '$email' LIMIT 1");
					if(mysqli_num_rows($user_check) > 0){
						$error = array('status' => "failed", "msg" => "User Already Exist");
						$this->response($this->json($error), 200);
					}
					else
					{
						$token = bin2hex(openssl_random_pseudo_bytes(50))."".md5($email);
						$date_time = date('Y-m-d H:i:s');
						$sql = mysqli_query($this->db,"INSERT INTO users (email, password, date_time,token)
						VALUES ('$email', '".md5($password)."', '$date_time' , '$token')");
						if ($sql)
						{
$message = "Dear User

Your account has been Successfully Created.

Warm Regards,
Motoread Team";
							$headers = "From: peter@motoread.com";
							$this->send_email($email,"Welcome to Motoread",$message,$headers);
						    $result = array('status' => "success", "msg" => "User Registered Successfully");
							$this->response($this->json($result), 200);
						}
						else
						{
						    $error = array('status' => "failed", "msg" => "Error");
							$this->response($this->json($error), 200);
						}
					}
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "Invalid Email address or Password");
				$this->response($this->json($error), 200);
			}
		}

		private function search(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['q'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$search_string = $this->_request['q'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}

			}

			if(!empty($id) and !empty($token) and !empty($search_string)){

				$words = $this->search_keywords(trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $search_string)));
				$var = 0;
				foreach ($words as $key => $value) {
					if($var == 0)
					{
						$string_search = " a_data.title like '%".$key."%'";
						$string_search2 = " a_data.content_text like '%".$key."%'";
						$var = $var + 1;
					}
					else
					{
						$string_search .= " or a_data.title like '%".$key."%'";
						$string_search2 .= " or a_data.content_text like '%".$key."%'";
					}
				}

				if(strlen($search_string) <= 2)
				{

					$string_search .= " a_data.title like '%".$search_string."%'";
					$string_search2 .= " or a_data.content_text like '%".$search_string."%'";
				}


				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){

					if (isset($this->_request['page'])) {

						$limt_data = ($this->_request['page'] - 1) * 10;
						$limit_query = "LIMIT ".$limt_data.",10";

					}
					else
					{
						$limt_data = '';
						$limit_query = '';
					}

					if(isset($this->_request['h']) && $this->_request['h'] == 1)
					{
						$sql_count = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date FROM `article_data` a_data inner join users_article u_article on (".$string_search.") and u_article.user_id = '$id' and u_article.is_deleted = 0 and a_data.article_id = u_article.article_id");

						$sql = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date FROM `article_data` a_data inner join users_article u_article on (".$string_search.") and u_article.user_id = '$id' and u_article.is_deleted = 0 and a_data.article_id = u_article.article_id  ORDER BY id DESC ".$limit_query." ");
					}
					else
					{
						$sql_count = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date FROM `article_data` a_data inner join users_article u_article on u_article.user_id = '$id' and u_article.is_deleted = 0 and u_article.article_id = a_data.article_id and ((".$string_search.") or (".$string_search2.")) " );

						$sql = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date FROM `article_data` a_data inner join users_article u_article on u_article.user_id = '$id' and u_article.is_deleted = 0 and u_article.article_id = a_data.article_id and ((".$string_search.") or (".$string_search2."))  ORDER BY id DESC ".$limit_query." " );
					}

					if(mysqli_num_rows($sql) > 0){
						$result = array();
						while($rlt = mysqli_fetch_assoc($sql)){
							$result[] = $rlt;
						}
						$res = array('status' => 'success', 'total_results' => mysqli_num_rows($sql_count) , 'data' => $result );
						$this->response($this->json($res), 200);
					}
					else
					{
						$error = array('status' => "success", "msg" => "No results found");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function playlist(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['action']) && !isset($this->_request['article'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$action = $this->_request['action'];
					$article = $this->_request['article'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($article) and !empty($token) and !empty($action)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){
					if($action == 1)
					{
						$sql = mysqli_query($this->db,"UPDATE users_article SET playlist =0 where id = '$article' and user_id = '$id' ");
					}
					elseif($action == 2){
						$sql = mysqli_query($this->db,"UPDATE users_article SET playlist =1 where id = '$article' and user_id = '$id' ");
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Invalid Request");
						$this->response($this->json($error),406);
					}

					if($sql){
						$result = array('status' => "success", "msg" => "Playlist Updated");
						$this->response($this->json($result), 200);
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Update Error");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function favorite(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['action']) && !isset($this->_request['article'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$action = $this->_request['action'];
					$article = $this->_request['article'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($article) and !empty($token) and !empty($action)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){
					if($action == 1)
					{
						$sql = mysqli_query($this->db,"UPDATE users_article SET is_favorite =1 where id = '$article' and user_id = '$id' ");
						$msg = 'Added to favorite';
					}
					elseif($action == 2){
						$sql = mysqli_query($this->db,"UPDATE users_article SET is_favorite =0 where id = '$article' and user_id = '$id' ");
						$msg = 'Removed from favorite';
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Invalid Request");
						$this->response($this->json($error),406);
					}

					if($sql){
						$result = array('status' => "success", "msg" => $msg);
						$this->response($this->json($result), 200);
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Update Error");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function delete(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['article'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$article = $this->_request['article'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($article) and !empty($token)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){

					$sql = mysqli_query($this->db,"UPDATE users_article SET is_deleted = 1 where id = '$article' and user_id = '$id' ");

					if($sql){
						$result = array('status' => "success", "msg" => 'Article Deleted Successfully');
						$this->response($this->json($result), 200);
					}
					else
					{
						$error = array('status' => "failed", "msg" => "delete Error");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function article(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				if(isset($this->_request['article']))
				{
					if(!empty($this->_request['article']))
					{
						$article_id1 = $this->_request['article'];
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Invalid Request");
						$this->response($this->json($error),406);
					}
				}

				if(isset($this->_request['playlist']))
				{
					if(!empty($this->_request['playlist']))
					{
						$playlist_query = $this->_request['playlist'];
						if($playlist_query == 1)
						{
							$playlist_query = 1;
						}
						elseif($playlist_query == 2)
						{
							$playlist_query = 0;
						}
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Invalid Request");
						$this->response($this->json($error),406);
					}
				}
				else
				{
					$playlist_query = '%';
				}

				if(isset($this->_request['favorite']))
				{
					if(!empty($this->_request['favorite']))
					{
						$favorite_query = $this->_request['favorite'];
						if($favorite_query == 1)
						{
							$favorite_query = 1;
						}
						elseif($favorite_query == 2)
						{
							$favorite_query = 0;
						}
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Invalid Request");
						$this->response($this->json($error),406);
					}
				}
				else
				{
					$favorite_query = '%';
				}


				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}

			}

			if(!empty($id) and !empty($token)){

				if (isset($this->_request['page'])) {
					$limt_data = ($this->_request['page'] - 1) * 10;
					$limit_query = "LIMIT ".$limt_data.",10";
				}
				else
				{
					$limt_data = '';
					$limit_query = '';
				}

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){
					if(isset($article_id1))
					{
						$sql = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, a_data.content_text as article_content, a_data.content_html as article_content_html , u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date, a_data.language as article_language FROM `article_data` a_data inner join users_article u_article on u_article.id = '$article_id1' and u_article.user_id = '$id' and u_article.playlist like '".$playlist_query."' and u_article.is_favorite like '".$favorite_query."' and u_article.is_deleted = 0 and a_data.article_id = u_article.article_id");
					}
					else
					{
						$sql_count = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date, a_data.language as article_language FROM `article_data` a_data inner join users_article u_article on u_article.user_id = '$id'  and u_article.playlist like '".$playlist_query."' and u_article.is_favorite like '".$favorite_query."' and u_article.is_deleted = 0 and u_article.article_id = a_data.article_id");

						$sql = mysqli_query($this->db,"SELECT u_article.id as user_article_id, a_data.title as article_title , a_data.icon_url as favicon_url, a_data.url as article_url, u_article.is_favorite,u_article.is_deleted,u_article.playlist ,u_article.saved_date, a_data.language as article_language FROM `article_data` a_data inner join users_article u_article on u_article.user_id = '$id'  and u_article.playlist like '".$playlist_query."' and u_article.is_favorite like '".$favorite_query."' and u_article.is_deleted = 0 and u_article.article_id = a_data.article_id ORDER BY id DESC ".$limit_query." ");
					}
					if(mysqli_num_rows($sql) > 0){
						$result = array();
						while($rlt = mysqli_fetch_assoc($sql)){
							$result[] = $rlt;
						}

						// $result['content_text'] = preg_replace('/<figure\b[^>]*>(.*?)<\/figure>/i', '', $result['content_text']);

						$res = array('status' => 'success' , 'total_results' => mysqli_num_rows($sql_count) ,'data' => $result );
						$this->response($this->json($res), 200);
					}
					else
					{
						$error = array('status' => "success", "msg" => "No results found");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}



		private function save(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['url'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$article_url = urldecode($this->_request['url']);
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($token) and !empty($article_url)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){
					$date_time = date('Y-m-d H:i:s');
					$article_check = mysqli_query($this->db,"SELECT article_id FROM article_data WHERE url = '$article_url' ");
					if(mysqli_num_rows($article_check) > 0){
						$row = mysqli_fetch_assoc($article_check);
						$article_id = $row['article_id'];

						$article_user_check = mysqli_query($this->db,"SELECT id FROM users_article WHERE article_id = '$article_id' and user_id = '$id'");
						if(mysqli_num_rows($article_user_check) > 0){
							$row = mysqli_fetch_assoc($article_user_check);
							$sql = mysqli_query($this->db,"UPDATE users_article SET is_deleted = 0, playlist = 1, saved_date = '".$date_time."' WHERE id = ".$row['id']);
							if ($sql)
							{
								$result = array('status' => "success", "msg" => "Article saved Successfully");
								$this->response($this->json($result), 200);
							}
							else
							{
								$result = array('status' => "failed", "msg" => "Article Save Error");
								$this->response($this->json($result), 200);
							}
						}
						else
						{
							$sql = mysqli_query($this->db,"INSERT INTO users_article (article_id, url, user_id, is_favorite, is_deleted, saved_date)
							VALUES ('".$article_id."', '".$article_url."', '".$id."' ,0,0,'".$date_time."')");
							if ($sql)
							{
								$result = array('status' => "success", "msg" => "Article saved Successfully");
								$this->response($this->json($result), 200);
							}
							else
							{
								$result = array('status' => "failed", "msg" => "Article Save Error");
								$this->response($this->json($result), 200);
							}
						}
					}
					else
					{
						$url = "https://api.diffbot.com/v3/article?token=af6eef32f4304b4eedeaa8fd82fa223f&url=".urlencode($article_url);
						$curl = curl_init($url);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						$curl_response = curl_exec($curl);

						if ($curl_response === false) {
						    $info = curl_getinfo($curl);
						    curl_close($curl);
						    die("error occured during curl exec. Additioanal info: " . var_export($info));
						}

						curl_close($curl);

						$decoded = json_decode($curl_response,True);
						if (!isset($decoded['objects']) && empty($decoded['objects'])) {
						    die("error occured:");
						}
						$decoded_article = $decoded['objects'][0];

						$url_response = array();

						$url_response['id'] = $id;

						$date_time = date('Y-m-d H:i:s');

						$url_response['url'] = $article_url;
						if(isset($decoded_article['title']))
						{
							$url_response['title'] = $decoded_article['title'];
						}
						else
						{
							$url_response['title'] = '';
						}

						if(isset($decoded_article['icon']))
						{
							$url_response['icon'] = $decoded_article['icon'];
						}
						else
						{
							$url_response['icon'] = '';
						}

						if(isset($decoded_article['humanLanguage']))
						{
							$url_response['language'] = $decoded_article['humanLanguage'];
						}
						else
						{
							$url_response['language'] = '';
						}

						if(isset($decoded_article['text']) && trim($decoded_article['text']) != '')
						{
							$url_response['article_text'] = $decoded_article['text'];
							$url_response['article_html'] = $decoded_article['html'];

							$sql_article_data = mysqli_query($this->db,"INSERT INTO article_data (icon_url, url, title, content_text, content_html, language, saved_date)
							VALUES ('".addslashes($url_response['icon'])."', '".addslashes($article_url)."', '".addslashes($url_response['title'])."' , '".addslashes($url_response['article_text'])."', '".addslashes($url_response['article_html'])."', '".addslashes($url_response['language'])."', '".$date_time."')");
							if ($sql_article_data)
							{
								$inserted_article_id = mysqli_insert_id($this->db);

								$save_user_article = mysqli_query($this->db,"INSERT INTO users_article (article_id, url, user_id, is_favorite, is_deleted, saved_date)
								VALUES ('".$inserted_article_id."', '".$article_url."', '".$id."' ,0,0,'".$date_time."')");
								if ($save_user_article)
								{
									$result = array('status' => "success", "msg" => "Article saved Successfully");
									$this->response($this->json($result), 200);
								}
								else
								{
									$result = array('status' => "failed", "msg" => "Article Save Error");
									$this->response($this->json($result), 200);
								}
							}
							else
							{
								$result = array('status' => "failed", "msg" => "Article Save Error");
								$this->response($this->json($result), 200);
							}
						}
						else
						{
							$result = array('status' => "failed", "msg" => "Content not found in URL");
							$this->response($this->json($result), 200);
						}
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}



		private function subscribe(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['title']) && !isset($this->_request['stream_id']) && !isset($this->_request['url'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$title = $this->_request['title'];
					$stream_id = $this->_request['stream_id'];
					$url = $this->_request['url'];

					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($token) and !empty($title) and !empty($stream_id) and !empty($url)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){
					$date_time = date('Y-m-d H:i:s');
					$feed_check = mysqli_query($this->db,"SELECT id FROM rss_feed WHERE user_id = '$id' and stream_id = '".trim($stream_id)."' ");
					if(mysqli_num_rows($feed_check) > 0){
						$result = array('status' => "failed", "msg" => "Already Subscribed");
						$this->response($this->json($result),200);
					}
					else
					{
						$date_time = date('Y-m-d H:i:s');
						$sql = mysqli_query($this->db,"INSERT INTO rss_feed (user_id, title,url,stream_id ,added_date)
						VALUES ('$id', '$title', '$url' ,'$stream_id' , '$date_time')");
						if ($sql)
						{
							$result = array('status' => "success", "msg" => "Successfully Subscribed");
							$this->response($this->json($result),200);
						}
					}

				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function rss(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];

					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($token)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){

					$feed_list = mysqli_query($this->db,"SELECT id as rss_id, title,stream_id, url FROM rss_feed WHERE user_id = '$id' ");
					if(mysqli_num_rows($feed_list) > 0){
						$result = array();
						while($rlt = mysqli_fetch_assoc($feed_list)){
							$result[] = $rlt;
						}
						$res = array('status' => 'success' , 'data' => $result );
						$this->response($this->json($res), 200);
					}
					else
					{
						$error = array('status' => "success", "msg" => "No results found");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function unsubscribe(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user']) && !isset($this->_request['rss_id'])) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user'];
					$rss_id = $this->_request['rss_id'];

					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}
			}

			if(!empty($id) and !empty($token) and !empty($rss_id)){

				$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE id = '$id' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){

					$rss_delete = mysqli_query($this->db,"DELETE FROM rss_feed WHERE id= '$rss_id' and user_id = '$id' ");
					if($rss_delete && mysqli_affected_rows($this->db) > 0){

						$error = array('status' => "success", "msg" => "rss unsubscribed successfully");
						$this->response($this->json($error),200);
					}
					else
					{
						$error = array('status' => "failed", "msg" => "rss delete failed");
						$this->response($this->json($error),200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}






		private function update_email(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user_id']) || !isset($this->_request['new_email']) || !isset($this->_request['pwd']) ) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user_id'];
					$new_email = $this->_request['new_email'];
					$password = $this->_request['pwd'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => 'Invalid Request');
					$this->response($this->json($error),406);
				}

			}

			if(!empty($id) and !empty($token) and !empty($new_email) and !empty($password)){
				if(filter_var($new_email, FILTER_VALIDATE_EMAIL)){

					$user_check = mysqli_query($this->db,"SELECT email FROM users WHERE id = '$id' and password = '".md5($password)."' and token = '$token' LIMIT 1");
					if(mysqli_num_rows($user_check) > 0){

						$sql = mysqli_query($this->db,"UPDATE users SET email = '$new_email' where id = '$id' ");
						if($sql){

							$result = array('status' => "success", "msg" => "Email Updated Successfully");
							$this->response($this->json($result), 200);
						}
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Email Updation failed");
						$this->response($this->json($error), 200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Please enter valid email id");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}

		private function change_password(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['user_id']) || !isset($this->_request['new_pwd']) || !isset($this->_request['pwd']) ) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else{

				$headers = apache_request_headers();
				if(isset($headers['token']))
				{
					$id = $this->_request['user_id'];
					$new_password = $this->_request['new_pwd'];
					$password = $this->_request['pwd'];
					$token = $headers['token'];
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Invalid Request");
					$this->response($this->json($error),406);
				}

			}

			if(!empty($id) and !empty($new_password) and !empty($password)){

				$user_check = mysqli_query($this->db,"SELECT email FROM users WHERE id = '$id' and password = '".md5($password)."' and token = '$token' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){

					$sql = mysqli_query($this->db,"UPDATE users SET password = '".md5($new_password)."' where id = '$id' ");
					if($sql){
						$result = array('status' => "success", "msg" => "Password Changed Successfully");
						$this->response($this->json($result), 200);
					}

				}
				else
				{
					$error = array('status' => "failed", "msg" => "Password Updation failed");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}


		private function reset_pwd(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			if (!isset($this->_request['action']) || !isset($this->_request['pwd']) ) {
				$error = array('status' => "failed", "msg" => "Invalid Request");
				$this->response($this->json($error),406);
			}
			else
			{
				$action = $this->_request['action'];
				$password = $this->_request['pwd'];
			}

			if(!empty($action) and !empty($password)){

				$reset_code = substr($action, 0,-2);
				$user_id = substr($action,-2);

				$user_check = mysqli_query($this->db,"SELECT email FROM users WHERE id = '$user_id' and reset_code = '".$reset_code."' LIMIT 1");
				if(mysqli_num_rows($user_check) > 0){

					$sql = mysqli_query($this->db,"UPDATE users SET password = '".md5($password)."' where id = '$user_id' ");
					if($sql){
						$result = array('status' => "success", "msg" => "Password Reset Successfully");
						$this->response($this->json($result), 200);
					}

				}
				else
				{
					$error = array('status' => "failed", "msg" => "Password Updation failed");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "please enter all params");
				$this->response($this->json($error), 200);
			}
		}

		private function reset_password(){
			if($this->get_request_method() != "POST"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}

			$email = mysqli_real_escape_string($this->db,$this->_request['email']);

			if(!empty($email)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){

					$user_check = mysqli_query($this->db,"SELECT id FROM users WHERE email = '$email' LIMIT 1");
					if(mysqli_num_rows($user_check) > 0){
						$row = mysqli_fetch_assoc($user_check);
						$id = $row['id'];
						$date_time = date('Y-m-d H:i:s');
						$token = bin2hex(openssl_random_pseudo_bytes(50));
						$sql = mysqli_query($this->db,"UPDATE  `users` SET  `reset_code` =  '$token' ,`otp_used` =  0 , `reset_request_time`='$date_time'  WHERE  `email` = '$email'");
						if($sql){
$message="Dear User

Please click on the link below to reset your password.

http://motoread.com/resetpwd.php?reset=$token".$id."

Warm Regards,
Motoread Team";
							$headers = "From: peter@motoread.com";
							$this->send_email($email,"Password Reset",$message,$headers);

							$result = array('status' => "success", "msg" => "Password reset link sent");
							$this->response($this->json($result), 200);
						}
					}
					else
					{
						$error = array('status' => "failed", "msg" => "Email Does not Exist");
						$this->response($this->json($error), 200);
					}
				}
				else
				{
					$error = array('status' => "failed", "msg" => "Please enter valid email id");
					$this->response($this->json($error), 200);
				}
			}
			else
			{
				$error = array('status' => "failed", "msg" => "Please enter email id");
				$this->response($this->json($error), 200);
			}
		}




		// private function otp(){
		// 	if($this->get_request_method() != "POST"){
		// 		$error = array('status' => "failed", "msg" => "Bad Request");
		// 		$this->response($this->json($error),406);
		// 	}

		// 	$email = mysqli_real_escape_string($this->db,$this->_request['email']);
		// 	$otp = $this->_request['otp'];

		// 	if(!empty($email) and !empty($otp)){
		// 		if(filter_var($email, FILTER_VALIDATE_EMAIL)){

		// 			$user_check = mysqli_query($this->db,"SELECT reset_request_time,email FROM users WHERE email = '$email' and reset_code = '$otp' and otp_used = 0 LIMIT 1");
		// 			if(mysqli_num_rows($user_check) > 0){
		// 				$row = mysqli_fetch_assoc($user_check);

		// 				$date_time = date('Y-m-d H:i:s');
		// 				$to_time = strtotime($date_time);
		// 				$from_time = strtotime($row['reset_request_time']);
		// 				$diff = round(abs($to_time - $from_time) / 60,2);

		// 				if($diff < 5)
		// 				{
		// 					$sql = mysqli_query($this->db,"UPDATE  `users` SET `otp_used` =  1 WHERE  `email` = '$email'");
		// 					if($sql){
		// 						$result = array('status' => "success", "msg" => "OTP verified");
		// 						$this->response($this->json($result), 200);
		// 					}
		// 				}
		// 				else
		// 				{
		// 					$result = array('status' => "failed", "msg" => "OTP Expired");
		// 					$this->response($this->json($result), 200);
		// 				}
		// 			}
		// 			else
		// 			{
		// 				$error = array('status' => "failed", "msg" => "Wrong OTP");
		// 				$this->response($this->json($error), 200);
		// 			}
		// 		}
		// 		else
		// 		{
		// 			$error = array('status' => "failed", "msg" => "Please enter valid email id");
		// 			$this->response($this->json($error), 200);
		// 		}
		// 	}
		// 	else
		// 	{
		// 		$error = array('status' => "failed", "msg" => "Please enter email id");
		// 		$this->response($this->json($error), 200);
		// 	}
		// }


		private function test(){
			if($this->get_request_method() != "GET"){
				$error = array('status' => "failed", "msg" => "Bad Request");
				$this->response($this->json($error),406);
			}
			// include 'Browser.php';
			// $browser = new Browser();
			// if($browser->isMobile() == true || $browser->isTablet() == true)
			// {
			// 	$msg = 'mobile';
			// }
			// else
			// {
			// 	$msg = 'Not Mobile';
			// }
			$headers = apache_request_headers();

			// foreach($headers as $key => $value )
			// {
			// 	echo "{$key} => {$value}<br>";
			// }

			//$result = array('status' => "success", "msg" => $msg);
			$this->response($this->json($error), 200);
		}




		// private function users(){
		// 	if($this->get_request_method() != "GET"){
		// 		$error = array('status' => "failed", "msg" => "Bad Request");
		// 		$this->response($this->json($error),406);
		// 	}
		// 	$sql = mysqli_query($this->db,"SELECT email FROM users");
		// 	if(mysqli_num_rows($sql) > 0){
		// 		$result = array();
		// 		while($rlt = mysqli_fetch_assoc($sql)){
		// 			$result[] = $rlt;
		// 		}
		// 		$this->response($this->json($result), 200);
		// 	}
		// 	else
		// 	{
		// 		$error = array('status' => "failed", "msg" => "No results found");
		// 		$this->response($this->json($error),200);
		// 	}
		// }

		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

		private function send_email($email_id,$subject,$message,$headers){
			mail($email_id,$subject,$message,$headers);
		}

		private  function search_keywords($string){
			    $stopWords = array('am','i','a','about','an','and','www','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','which','will','with','whom','the','are');
			    $string = preg_replace('/\s\s+/i', '', $string);
			    $string = trim($string);
			    $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string);
			    $string = strtolower($string);
			    preg_match_all('/\b.*?\b/i', $string, $matchWords);
			    $matchWords = $matchWords[0];
			    foreach ( $matchWords as $key=>$item ) {
			      if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 1 ) {
			        unset($matchWords[$key]);
			      }
			    }
			    $keyword_array = array();
			    if ( is_array($matchWords) ) {
			      foreach ( $matchWords as $key => $val ) {
			        $val = strtolower($val);
			        if ( isset($keyword_array[$val]) ) {
			          $keyword_array[$val]++;
			        } else {
			          $keyword_array[$val] = 1;
			        }
			      }
			    }
			    arsort($keyword_array);
			    $keyword_array = array_slice($keyword_array, 0, 20);
			    return $keyword_array;
			 }
		}

	$api = new API;
	$api->processApi();

	?>
