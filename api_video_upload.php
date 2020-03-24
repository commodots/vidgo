<?php   include("includes/connection.php");
        include("includes/function.php");   

        $protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

        $file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/uploads/';

        $file_path_img = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

        define("PACKAGE_NAME",$settings_details['package_name']);

        define("VIDEO_ADD_POINTS_STATUS",$settings_details['video_add_status']);

        define("AUTO_APPROVE",$settings_details['auto_approve']);

        $get_method = checkSignSalt($_POST['data']); 
        
        if($get_method['method_name']=="user_video_upload")
        {            

            $path = "uploads/"; //set your folder path
            $video_local=rand(0,99999)."_".str_replace(" ", "-", $_FILES['video_local']['name']);

            $tmp = $_FILES['video_local']['tmp_name'];
            
            if (move_uploaded_file($tmp, $path.$video_local)) 
            {
                $video_url=$file_path.$video_local;
            }
               
              $video_thumbnail=rand(0,99999)."_".$_FILES['video_thumbnail']['name'];
       
              //Main Image
              $tpath1='images/'.$video_thumbnail;        
              $pic1=compress_image($_FILES["video_thumbnail"]["tmp_name"], $tpath1, 80);
         
              $video_id='';

              $user_id =$_POST['user_id'];  

              $sql_user="SELECT * FROM tbl_users WHERE id = '$user_id'";
              $res_user=mysqli_query($mysqli, $sql_user);

              $row_user=mysqli_fetch_assoc($res_user);

              if(AUTO_APPROVE=='on'){
                if($row_user['is_verified']==1){
                  $status=1;
                }else{
                  $status=0;
                }
              }else{
                $status=0;
              }

              


              $data = array( 
                  'user_id'  =>  $user_id,
                  'cat_id'  =>  $_POST['cat_id'],
                  'video_type'  =>  'local',
                  'video_title'  =>  $_POST['video_title'],
                  'video_url'  =>  $video_url,
                  'video_id'  =>  $video_id,
                  'video_layout'  =>  $_POST['video_layout'],
                  'video_thumbnail'  =>  $video_thumbnail,
                  'status'  => $status
              );      

              $qry = Insert('tbl_video',$data);

              $last_id = mysqli_insert_id($mysqli);

              if($status==1){

                if(VIDEO_ADD_POINTS_STATUS=='true')
                {

                  $qry="SELECT * FROM tbl_video where id='".$last_id."'";
                  $result=mysqli_query($mysqli,$qry);
                  $row=mysqli_fetch_assoc($result); 

                  $user_id =$row['user_id'];

                  $qry1 = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$last_id."' and user_id = '".$user_id."'";
                  $result1 = mysqli_query($mysqli,$qry1);
                  $num_rows1 = mysqli_num_rows($result1); 

                  $add_video_point=API_USER_VIDEO_ADD; 

                  if ($num_rows1 <= 0)
                  {

                    $qry2 = "SELECT * FROM tbl_users WHERE id = '".$user_id."'";
                    $result2 = mysqli_query($mysqli,$qry2);
                    $row2=mysqli_fetch_assoc($result2); 

                    $user_total_point=$row2['total_point']+$add_video_point;

                    $user_qry=mysqli_query($mysqli,"UPDATE tbl_users SET total_point='".$user_total_point."'  WHERE id = '".$user_id."'");

                    user_reward_activity($last_id,$user_id,"Add Video",$add_video_point);

                    }

                  }

                
                $img_path=$file_path_img.'images/thumbs/'.$video_thumbnail;

                // send notification to user's followers

                $user_name=ucwords($row_user['name']);
                $content = array(
                  "en" => "New video is added by ".$user_name,
                );  

                $sql_follower="SELECT * FROM tbl_follows, tbl_users WHERE tbl_follows.`follower_id`=tbl_users.`id` AND tbl_follows.`user_id`='$user_id'";

                $res_follower=mysqli_query($mysqli, $sql_follower);

                $followers=array();

                while ($row_follower=mysqli_fetch_assoc($res_follower)) {
                  $followers[]=$row_follower['player_id'];
                }

                $fields = array(
                  'app_id' => ONESIGNAL_APP_ID,                                          
                  'include_player_ids' => $followers, 
                  'data' => array("foo" => "bar","video_id"=>$last_id),
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

 
            $set['ANDROID_REWARDS_APP'][] = array('msg'=>'Video has been uploaded!','success'=>1);
    
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        else
        {
            $get_method = checkSignSalt($_POST['data']); 
        }
 
?>