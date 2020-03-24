<?php 
	require("includes/connection.php");
	require("includes/function.php");
	require("language/language.php");

	$protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

    $file_path_img = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

	$response=array();

	define("VIDEO_ADD_POINTS_STATUS",$settings_details['video_add_status']);

	// get total comments
	function total_comments($post_id)
	{
		global $mysqli;

		$query="SELECT COUNT(*) AS total_comments FROM tbl_comments WHERE `post_id`='$post_id'";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
		$row=mysqli_fetch_assoc($sql);
		return stripslashes($row['total_comments']);
	}

	function get_user_info($user_id,$field_name) 
	{
		global $mysqli;

		$qry_user="SELECT * FROM tbl_users WHERE id='".$user_id."' AND status='1'";
		$query1=mysqli_query($mysqli,$qry_user);
		$row_user = mysqli_fetch_array($query1);

		$num_rows1 = mysqli_num_rows($query1);

		if ($num_rows1 > 0)
		{     
		  return $row_user[$field_name];
		}
		else
		{
		  return "";
		}
	}

	function delete_videos($video_ids) 
	{
		global $mysqli;

		$sql="SELECT * FROM tbl_video WHERE id IN ($video_ids)";
		$res=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

		while ($row=mysqli_fetch_assoc($res)) {

			if($row['video_thumbnail']!="")
			{
				unlink('images/thumbs/'.$row['video_thumbnail']);
				unlink('images/'.$row['video_thumbnail']);
				unlink('uploads/'.basename($row['video_url']));
			}
		}

		$deleteSql="DELETE FROM tbl_video WHERE id IN ($video_ids)";

		mysqli_query($mysqli, $deleteSql);

		$delete_comment="DELETE FROM tbl_comments WHERE post_id IN ($video_ids)";

		mysqli_query($mysqli, $delete_comment);

	}



	switch ($_POST['action']) {
		case 'toggle_status':
			$id=$_POST['id'];
			$for_action=$_POST['for_action'];
			$column=$_POST['column'];
			$tbl_id=$_POST['tbl_id'];
			$table_nm=$_POST['table'];

			if($for_action=='active'){
				$data = array($column  =>  '1');
			    $edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");

			    if(isset($_POST['video_status']) && $column!='featured'){
			    	//User Points
					if(VIDEO_ADD_POINTS_STATUS=='true')
					{

						$qry="SELECT * FROM tbl_video where id='".$id."'";
						$result=mysqli_query($mysqli,$qry);
						$row=mysqli_fetch_assoc($result); 

						$user_id =$row['user_id'];

						if(is_suspend($user_id)==0){
							$qry1 = "SELECT * FROM tbl_users_rewards_activity WHERE video_id = '".$id."' and user_id = '".$user_id."'";
							$result1 = mysqli_query($mysqli,$qry1);
							$num_rows1 = mysqli_num_rows($result1); 

							$user_video_id=$id;
							$add_video_point=API_USER_VIDEO_ADD; 

							if ($num_rows1 <= 0)
							{

								$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
								$result2 = mysqli_query($mysqli,$qry2);
								$row2=mysqli_fetch_assoc($result2); 

								$user_total_point=$row2['total_point']+$add_video_point;

								$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

								user_reward_activity($user_video_id,$user_id,"Add Video",$add_video_point);

								

							}

							$img_path=$file_path_img.'images/thumbs/'.$row['video_thumbnail'];

							$user_name=ucwords(get_user_info($user_id,'name'));
							$content = array(
	                     		"en" => "New video is added by ".$user_name,
	                      	);	

							$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id' AND tbl_follows.`user_id` <> 0";

							$res_follower=mysqli_query($mysqli, $sql_follower);

							$followers=array();

							while ($row_follower=mysqli_fetch_assoc($res_follower)) {
								$followers[]=$row_follower['player_id'];
							}

							if(!empty($followers))
							{
								$fields = array(
						              'app_id' => ONESIGNAL_APP_ID,                                       
						              'include_player_ids' => $followers, 
						              'data' => array("foo" => "bar","video_id"=>$user_video_id),
						              'headings'=> array("en" => APP_NAME),
						              'contents' => $content,
						              'big_picture' =>$img_path
						              );

						        $fields = json_encode($fields);

						        $ch = curl_init();
						        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
						        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
						        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						        curl_setopt($ch, CURLOPT_HEADER, FALSE);
						        curl_setopt($ch, CURLOPT_POST, TRUE);
						        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
						        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

						        $notify_res = curl_exec($ch);       
						        
						        curl_close($ch);

						        
							}

							$_SESSION['msg']="13";
						}else{
							$data = array($column  =>  '0');
			    			$edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");
							// for susspend message
							$response['status']=2;
					      	echo json_encode($response);
					      	exit;
						}

				    }
			    }

			}else{
				$data = array($column  =>  '0');
			    $edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");
			    $_SESSION['msg']="14";
			}
			
	      	$response['status']=1;
	      	$response['action']=$for_action;
	      	echo json_encode($response);
			break;

		case 'removeComment':
			$id=$_POST['id'];
			$post_id=$_POST['post_id'];

			Delete('tbl_comments','id='.$id);

	      	$response['status']=1;
	      	$response['total_comments']=total_comments($post_id);
	      	echo json_encode($response);
			break;

		case 'removeAllComment':
			$post_id=$_POST['post_id'];

			Delete('tbl_comments','post_id='.$news_id);


	      	$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'notify':
			
			$sql = "SELECT * FROM tbl_verify_user WHERE `is_opened`='0' AND `status`='0' ORDER BY `id` DESC";
			$qry = mysqli_query($mysqli, $sql);
			$info=array();
			while ($row = mysqli_fetch_assoc($qry)) {
				
				$data='<li>
	                    <a href="" class="btn_verify" data-id="'.$row['id'].'">
	                      <span class="badge badge-success pull-right">'.date('d M, Y',$row['created_at']).'</span>
	                      <div class="message">
	                        <div class="content">
	                          <div class="title">New Verification request</div>
	                          <div class="description"><strong>By:</strong> '.$row['full_name'].' </div>
	                        </div>
	                      </div>
	                    </a>
	                  </li>';

				array_push($info, $data);
			}

			$response['content']=$info;

	      	$response['status']=1;
	      	$response['count']=mysqli_num_rows($qry);

	      	echo json_encode($response);
			break;

		case 'openAllNotify':
			
			$data = array('is_opened'  =>  '1');
			$edit_status=Update('tbl_verify_user', $data, "WHERE is_opened = '0'");
			$info=array();
			$response['content']=$info;

	      	$response['status']=1;
	      	$response['count']=0;

	      	echo json_encode($response);
			break;

		case 'openNotify':

			$id=$_POST['id'];
			
			$data = array('is_opened'  =>  '1');
			$edit_status=Update('tbl_verify_user', $data, "WHERE id = '$id'");

			$sql = "SELECT * FROM tbl_verify_user WHERE `is_opened`='0' AND `status`='0' ORDER BY `id` DESC";
			$qry = mysqli_query($mysqli, $sql);
			$info=array();
			while ($row = mysqli_fetch_assoc($qry)) {
				
				$data='<li>
	                    <a href="" class="btn_verify" data-id="'.$row['id'].'">
	                      <span class="badge badge-success pull-right">'.date('d M, Y',$row['created_at']).'</span>
	                      <div class="message">
	                        <div class="content">
	                          <div class="title">New Verification request</div>
	                          <div class="description"><strong>By:</strong> '.$row['full_name'].' </div>
	                        </div>
	                      </div>
	                    </a>
	                  </li>';

				array_push($info, $data);
			}

			$response['content']=$info;

	      	$response['status']=1;
	      	$response['count']=mysqli_num_rows($qry);

	      	echo json_encode($response);
			break;
			

		case 'multi_action':

			$action=$_POST['for_action'];
			$ids=implode(",", $_POST['id']);
			$table=$_POST['table'];

			if($action=='enable'){
				$sql="UPDATE $table SET status='1' WHERE id IN ($ids)";
				if(mysqli_query($mysqli, $sql)){

					if(VIDEO_ADD_POINTS_STATUS=='true')
					{
						foreach ($_POST['id'] as $key => $value) {
							$qry="SELECT * FROM tbl_video where id='".$value."'";
							$result=mysqli_query($mysqli,$qry);
							$row=mysqli_fetch_assoc($result); 

							$user_id =$row['user_id'];

							if(is_suspend($user_id)==0){
								$qry1 = "SELECT * FROM tbl_users_rewards_activity WHERE video_id = '".$value."' and user_id = '".$user_id."'";
								$result1 = mysqli_query($mysqli,$qry1);
								$num_rows1 = mysqli_num_rows($result1); 

								$user_video_id=$value;
								$add_video_point=API_USER_VIDEO_ADD; 

								if ($num_rows1 <= 0)
								{

									$qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
									$result2 = mysqli_query($mysqli,$qry2);
									$row2=mysqli_fetch_assoc($result2); 

									$user_total_point=$row2['total_point']+$add_video_point;

									$user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

									user_reward_activity($user_video_id,$user_id,"Add Video",$add_video_point);

									$user_name=ucwords(get_user_info($user_id,'name'));
									$content = array(
		                         		"en" => "New video is added by ".$user_name,
		                          	);	

									$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

									$res_follower=mysqli_query($mysqli, $sql_follower);

									$followers=array();

									while ($row_follower=mysqli_fetch_assoc($res_follower)) {
										$followers[]=$row_follower['player_id'];
									}

									if(!empty($followers)){
										$fields = array(
								              'app_id' => ONESIGNAL_APP_ID,
								              'include_player_ids' => $followers, 
								              'data' => array("foo" => "bar","video_id"=>$user_video_id),
								              'headings'=> array("en" => APP_NAME),
								              'contents' => $content 
								              );

								        $fields = json_encode($fields);

								        $ch = curl_init();
								        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
								        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
								        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								        curl_setopt($ch, CURLOPT_HEADER, FALSE);
								        curl_setopt($ch, CURLOPT_POST, TRUE);
								        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
								        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

								        $notify_res = curl_exec($ch);       
								        
								        curl_close($ch);
								    }
								}
							}

							
							

						}
						

				    }

					$response['status']=1;	
					$_SESSION['msg']="13";
				}
			}
			else if($action=='disable'){
				$sql="UPDATE $table SET status='0' WHERE id IN ($ids)";
				if(mysqli_query($mysqli, $sql)){
					$response['status']=1;	
					$_SESSION['msg']="14";
				}
			}
			else if($action=='delete'){
				delete_videos($ids);
				$response['status']=1;	
			}


	      	echo json_encode($response);
			break;

		case 'verifyUser':
			
			$verify_user_id=$_POST['verify_user_id'];
			$user_id=$_POST['user_id'];
			$perform=$_POST['perform'];

			$sql="SELECT * FROM tbl_verify_user WHERE id='$verify_user_id'";
			$res=mysqli_query($mysqli, $sql);

			$row=mysqli_fetch_assoc($res);

			if(is_suspend($user_id)==0){

				if($perform=='approve'){

					$content = array("en" => "Your verification request has been approved by admin!");

					$fields = array(
		                'app_id' => ONESIGNAL_APP_ID,
		                'included_segments' => array('Subscribed Users'),                                            
		                'data' => array("foo" => "bar","user_id" => "true"),
		                'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
		                'headings'=> array("en" => APP_NAME),
		                'contents' => $content                   
		            );

					$fields = json_encode($fields);

					$ch = curl_init();
			        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
			                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			        curl_setopt($ch, CURLOPT_HEADER, FALSE);
			        curl_setopt($ch, CURLOPT_POST, TRUE);
			        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			        $notify_res = curl_exec($ch);
			        curl_close($ch);

					$data = array('status'  =>  '1','verify_at'  =>  strtotime(date('d-m-Y h:i:s A')));
				    $update=Update("tbl_verify_user", $data, "WHERE id = '$verify_user_id'");

				    $data = array('is_verified'  =>  '1');
				    $update=Update("tbl_users", $data, "WHERE id = '$user_id'");

				    $_SESSION['msg']="22";

				}
				else if($perform=='reject'){


					$content = array("en" => "Your verification request has been rejected by admin!");

					$fields = array(
		                'app_id' => ONESIGNAL_APP_ID,
		                'included_segments' => array('Subscribed Users'),                                            
		                'data' => array("foo" => "bar","user_id" => "true"),
		                'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
		                'headings'=> array("en" => APP_NAME),
		                'contents' => $content                  
		            );

					$fields = json_encode($fields);

					$ch = curl_init();
			        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
			                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			        curl_setopt($ch, CURLOPT_HEADER, FALSE);
			        curl_setopt($ch, CURLOPT_POST, TRUE);
			        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			        $notify_res = curl_exec($ch);
			        curl_close($ch);

					$reason=addslashes(trim($_POST['reject_reason']));

					$data = array('reject_reason'  =>  $reason,'status'  =>  '2','verify_at'  =>  strtotime(date('d-m-Y h:i:s A')));
				    $update=Update("tbl_verify_user", $data, "WHERE id = '$verify_user_id'");

				    $data = array('is_verified'  =>  '2');
				    $update=Update("tbl_users", $data, "WHERE id = '$user_id'");

				    $_SESSION['msg']="23";
				}
			}
			else{
				$_SESSION['msg']="24";
			}
			
			$response['status']=1;
			echo json_encode($response);
			break;

		case 'auto_approve':

			$auto_approve=$_POST['auto_approve'] ? 'on' : "off";

			$data = array
			(
				'auto_approve' => $auto_approve
			);

			$settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'notify_users':

			$video_id=$_POST['vid'];
			$user_id=$_POST['uid'];

			$user_name=ucwords(get_user_info($user_id,'name'));
			$content = array(
         		"en" => "New video is added by ".$user_name,
          	);	

			$sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

			$res_follower=mysqli_query($mysqli, $sql_follower);

			$followers=array();

			while ($row_follower=mysqli_fetch_assoc($res_follower)) {
				$followers[]=$row_follower['player_id'];
			}

			if(!empty($followers)){
				$fields = array(
		              'app_id' => ONESIGNAL_APP_ID,
		              'include_player_ids' => $followers, 
		              'data' => array("foo" => "bar","video_id"=>$video_id),
		              'headings'=> array("en" => APP_NAME),
		              'contents' => $content 
		              );

		        $fields = json_encode($fields);

		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		        curl_setopt($ch, CURLOPT_HEADER, FALSE);
		        curl_setopt($ch, CURLOPT_POST, TRUE);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		        $notify_res = curl_exec($ch);       
		        
		        curl_close($ch);
		    }
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		case 'account_status':

			$for_action=$_POST['for_action'];
			$user_id=$_POST['id'];

			if($for_action=='suspend'){

				$data = array(
		            'user_id'  => $user_id,
		            'suspended_on'  => strtotime(date('d-m-Y h:i:s A')),
		            'suspension_reason'  => trim($_POST['suspend_reason'])
		        );		

	          	$insert = Insert('tbl_suspend_account',$data);

	          	$data = array('status' => '0');

				$update=Update('tbl_video', $data, "WHERE user_id =".$user_id);

				$data = array('status' => '0');

				$update=Update('tbl_comments', $data, "WHERE user_id =".$user_id);

	          	$data = array('status' => '2');

				$update=Update('tbl_users', $data, "WHERE id =".$user_id);


				$content = array("en" => "Your account has been suspended.");

				$fields = array(
	              'app_id' => ONESIGNAL_APP_ID,
	              'included_segments' => array('Subscribed Users'),                                       
	              'data' => array("foo" => "bar","account_status" =>"true","account_id" =>$user_id),     
	              'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
	              'headings'=> array("en" => APP_NAME),
	              'contents' => $content 
	            );

	            $fields = json_encode($fields);

		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		        curl_setopt($ch, CURLOPT_HEADER, FALSE);
		        curl_setopt($ch, CURLOPT_POST, TRUE);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		        $account_notify = curl_exec($ch);
		        curl_close($ch);

	          	$response['status']=1;
			}
			else if($for_action=='active'){

				$data = array(
					'activated_on'  => strtotime(date('d-m-Y h:i:s A')),
		            'status'  => 0
		        );		

				$update=Update('tbl_suspend_account', $data, "WHERE user_id =".$user_id." AND status=1");

	          	$data = array('status' => '1');

				$update=Update('tbl_users', $data, "WHERE id =".$user_id);

				$sql="SELECT video_id FROM tbl_users_rewards_activity WHERE `user_id`='$user_id'";
				$res=mysqli_query($mysqli, $sql);

				while ($row=mysqli_fetch_assoc($res)) {
					$data = array('status' => '1');
					$update=Update('tbl_video', $data, "WHERE id =".$row['video_id']);
				}

				$data = array('status' => '1');

				$update=Update('tbl_comments', $data, "WHERE user_id =".$user_id);

				$content = array("en" => "Your account has been activated.");

				$fields = array(
	              'app_id' => ONESIGNAL_APP_ID,
	              'included_segments' => array('Subscribed Users'),                                       
	              'data' => array("foo" => "bar","account_status" =>"true","account_id" =>$user_id),     
	              'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
	              'headings'=> array("en" => APP_NAME),
	              'contents' => $content 
	            );

	            $fields = json_encode($fields);

		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		        curl_setopt($ch, CURLOPT_HEADER, FALSE);
		        curl_setopt($ch, CURLOPT_POST, TRUE);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		        $account_notify = curl_exec($ch);
		        curl_close($ch);

	          	$response['status']=1;

			}
			
	      	echo json_encode($response);
			break;

		case 'delete_video':

			$ids=$_POST['id'];

			delete_videos($ids);
			
			$response['status']=1;
	      	echo json_encode($response);
			break;

		default:
			$response['message']='No method available !';
			$response['status']=0;
	      	echo json_encode($response);
			break;
	}

?>