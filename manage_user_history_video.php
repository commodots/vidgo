<?php include('includes/header.php'); 
	include("includes/connection.php");
	
    include("includes/function.php");
	include("language/language.php"); 
 
	 	 $users_res=mysqli_query($mysqli,'SELECT * FROM tbl_users WHERE id='.$_GET['user_id'].'');
	   $users_res_row=mysqli_fetch_assoc($users_res);
			 
		 $users_rewards_qry="SELECT * FROM tbl_users_rewards_activity
		 LEFT JOIN tbl_users ON tbl_users_rewards_activity.user_id= tbl_users.id
		 WHERE tbl_users_rewards_activity.status=1 AND tbl_users_rewards_activity.user_id='".$_GET['user_id']."'
		 ORDER BY tbl_users_rewards_activity.id DESC";  
			 
		$users_rewards_result=mysqli_query($mysqli,$users_rewards_qry);
	


  function get_video_info($video_id,$field_name) 
   {
    global $mysqli;

    $qry_video="SELECT * FROM tbl_video WHERE id='".$video_id."' AND status='1'";
    $query1=mysqli_query($mysqli,$qry_video);
    $row_video = mysqli_fetch_array($query1);

    $num_rows1 = mysqli_num_rows($query1);
    
            if ($num_rows1 > 0)
        {     
        return $row_video[$field_name];
      }
      else
      {
        return "";
      }
   }		


$settings_qry="SELECT * FROM tbl_settings where id='1'";
  $settings_result=mysqli_query($mysqli,$settings_qry);
  $settings_row=mysqli_fetch_assoc($settings_result);

$qry_video="SELECT COUNT(*) as num FROM tbl_video WHERE user_id='".$_GET['user_id']."'";
$total_video = mysqli_fetch_array(mysqli_query($mysqli,$qry_video));
$total_video = $total_video['num'];


$qry_users_paid="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.user_id='".$_GET['user_id']."' AND tbl_users_redeem.status = 1";
$total_paid = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_paid));
$total_paid = $total_paid['num'];

$qry_users_pending="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.user_id='".$_GET['user_id']."' AND tbl_users_redeem.status = 0";
$total_pending = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_pending));
$total_pending = $total_pending['num'];				


//Get User Video
      $tableName="tbl_video";   
      $targetpage = "manage_user_history_video.php"; 
      $limit = 12; 
      
      $query = "SELECT COUNT(*) as num FROM $tableName 
                LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
                WHERE user_id='".$_GET['user_id']."'";
      $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
      $total_pages = $total_pages['num'];
      
      $stages = 3;
      $page=0;
      if(isset($_GET['page'])){
      $page = mysqli_real_escape_string($mysqli,$_GET['page']);
      }
      if($page){
        $start = ($page - 1) * $limit; 
      }else{
        $start = 0; 
        } 
       

    $video_qry="SELECT tbl_category.category_name,tbl_video.* FROM tbl_video
                      LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
                      WHERE user_id='".$_GET['user_id']."'
                      ORDER BY tbl_video.id DESC LIMIT $start, $limit";     
    $result=mysqli_query($mysqli,$video_qry); 
   
   

  if(isset($_GET['video_id']))
  { 
        
    $img_res=mysqli_query($mysqli,'SELECT * FROM tbl_video WHERE id='.$_GET['video_id'].'');
    $img_res_row=mysqli_fetch_assoc($img_res);
           
    if($img_res_row['video_thumbnail']!="")
     {
          unlink('images/thumbs/'.$img_res_row['video_thumbnail']);
          unlink('images/'.$img_res_row['video_thumbnail']);
          unlink('uploads/'.basename($img_res_row['video_url']));

      }
 
    Delete('tbl_video','id='.$_GET['video_id'].'');
    
    $_SESSION['msg']="12";
    header( "Location:manage_user_history_video.php?user_id=".$_GET['user_id']);
    exit;
    
  }


  if(isset($_POST['delete_rec']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      $img_res=mysqli_query($mysqli,'SELECT * FROM tbl_video WHERE id='.$del_id.'');
      $img_res_row=mysqli_fetch_assoc($img_res);
             
      if($img_res_row['video_thumbnail']!="")
       {
            unlink('images/thumbs/'.$img_res_row['video_thumbnail']);
            unlink('images/'.$img_res_row['video_thumbnail']);
            unlink('uploads/'.basename($img_res_row['video_url']));

        }
   
      Delete('tbl_video','id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:manage_user_history_video.php?user_id=".$_POST['user_id']);
    exit;
  }

  //Active and Deactive status
if(isset($_GET['status_deactive_id']))
{
   $data = array('status'  =>  '0');
  
   $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['status_deactive_id']."'");
  
   $_SESSION['msg']="14";
   header( "Location:manage_user_history_video.php?user_id=".$_GET['user_id']);
   exit;
}
if(isset($_GET['status_active_id']))
{
    $data = array('status'  =>  '1');
    
    $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['status_active_id']."'");

     //User Points
     if(VIDEO_ADD_POINTS_STATUS=='true')
     {

        $qry="SELECT * FROM tbl_video where id='".$_GET['status_active_id']."'";
        $result=mysqli_query($mysqli,$qry);
        $row=mysqli_fetch_assoc($result); 

        $user_id =$row['user_id'];

        $qry1 = "SELECT * FROM tbl_users_rewards_activity WHERE  video_id = '".$_GET['status_active_id']."' and user_id = '".$user_id."'";
        $result1 = mysqli_query($mysqli,$qry1);
        $num_rows1 = mysqli_num_rows($result1); 

         $user_video_id=$_GET['status_active_id'];
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

    }
    
    $_SESSION['msg']="13";   
    header( "Location:manage_user_history_video.php?user_id=".$_GET['user_id']);
    exit;
} 

//Active and Deactive featured
  if(isset($_GET['featured_deactive_id']))
  {
    $data = array('featured'  =>  '0');
    
    $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['featured_deactive_id']."'");
    
     $_SESSION['msg']="14";
     header( "Location:manage_user_history_video.php?user_id=".$_GET['user_id']);
     exit;
  }
  if(isset($_GET['featured_active_id']))
  {
    $data = array('featured'  =>  '1');
    
    $edit_status=Update('tbl_video', $data, "WHERE id = '".$_GET['featured_active_id']."'");
    
    $_SESSION['msg']="13";
     header( "Location:manage_user_history_video.php?user_id=".$_GET['user_id']);
     exit;
  } 


    function get_user_info($post_id)
   {
    global $mysqli;

    $query="SELECT * FROM tbl_users WHERE tbl_users.id='".$post_id."'";
     
    $sql = mysqli_query($mysqli,$query);
    $row=mysqli_fetch_assoc($sql);

    return $row['name'];
   } 
	 
?>
 

<div class="row">
    <div class="col-xs-12 mr_bottom20">
    <div class="card mr_bottom20 mr_top10">
      <div class="page_title_block user_dashboard_item" style="background-color: #333;">
      <div class="user_dashboard_mr_bottom">
        <div class="col-md-10 col-xs-12"> <br>
          <span class="badge badge-success badge-icon">
            <div class="user_profile_img">
            
             <?php 
                if($users_res_row['user_type']=='Google'){
                  echo '<img src="assets/images/google-logo.png" style="width: 16px;height: 16px;position: absolute;top: 35px;z-index: 1;left: 62px;">';
                }

              ?>
              <?php if(isset($_GET['user_id']) and $users_res_row['user_image']!="" and file_exists('images/'.$users_res_row['user_image'])) {?>
              <img type="image" src="images/<?php echo $users_res_row['user_image'];?>" alt="image" style=""/>
              <?php }else{?>  
              <img type="image" src="assets/images/user_photo.png" alt="image"/>
             <?php } ?>           

            </div>
            <span style="font-size: 14px;"><?php echo $users_res_row['name'];?>
              <?php 
                  if($users_res_row['is_verified']==1){
                    echo '<img src="assets/images/verification_150.png" style="border: none;width: 15px;height: 15px">';
                  }
                ?>
            </span>
          </span> 
          <span class="badge badge-success badge-icon">
          <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
          <span style="font-size: 14px;"><?php echo $users_res_row['email'];?></span>
          </span> 
          <br><br>
        </div>
        <div class="col-md-2 col-xs-12">
          <div class="search_list">
          <div class="add_btn_primary"> <a href="manage_users.php">Back</a> </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="manage_user_history_video.php?user_id=<?php echo $users_res_row['id'];?>" class="card card-banner card-alicerose-light">
        <div class="card-body"> <i class="icon fa fa-film fa-4x"></i>
        <div class="content">
          <div class="title">Total Videos</div>
          <div class="value"><span class="sign"></span><?php echo $total_video;?></div>
        </div>
        </div>
        </a> 
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="manage_user_history_pending_points.php?user_id=<?php echo $users_res_row['id'];?>" class="card card-banner card-orange-light">
        <div class="card-body"> <i class="icon fa fa-clock-o fa-4x"></i>
        <div class="content">
          <div class="title">Pending Points</div>
          <div class="value"><span class="sign"></span><?php echo $users_res_row['total_point'];?></div>
        </div>
        </div>
        </a> 
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mr_bot60"> <a href="javascript::void();" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fa fa-money fa-4x"></i>
        <div class="content">
          <div class="title">Pending</div>
          <div class="value"><span class="sign"></span><?php echo $total_pending ? $total_pending : '0';?><span class="sign"><?php echo $settings_row['redeem_currency'];?></span></div>
        </div>
        </div>
        </a> 
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mr_bot60"> <a href="javascript::void();" class="card card-banner card-blue-light">
        <div class="card-body"> <i class="icon fa fa-money fa-4x"></i>
        <div class="content">
          <div class="title">Total Paid</div>
          <div class="value"><span class="sign"></span><?php echo $total_paid ? $total_paid : '0';?><span class="sign"><?php echo $settings_row['redeem_currency'];?></span></div>
        </div>
        </div>
        </a> 
      </div>  
      </div>
      <div class="user_dashboard_info">
      <ul>
        <li><a href="manage_user_history.php?user_id=<?php echo $users_res_row['id'];?>">Edit Info</a></li>
        <li><a href="manage_user_history_followers.php?user_id=<?php echo $users_res_row['id'];?>"><?php echo $users_res_row['total_followers'];?> Followers</a></li>
        <li><a href="manage_user_history_followings.php?user_id=<?php echo $users_res_row['id'];?>"><?php echo $users_res_row['total_following'];?> Following</a></li>        
        <li><a href="manage_user_history_withdrawal.php?user_id=<?php echo $users_res_row['id'];?>">Withdrawal</a></li>
        <li><a href="manage_user_history_total_points.php?user_id=<?php echo $users_res_row['id'];?>">All Points History</a></li>
      </ul>
      </div>
    </div>
    </div>
     <div class="col-xs-12">
    <div class="card">
      <div class="card-header">Total Videos</div>
        <div class="col-md-5 col-xs-12" style="margin-bottom: -40px;margin-top: -20px;">
            <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'];?>">
            <div class="page_title">
              <div class="checkbox">
                <input type="checkbox" name="checkall" id="checkall" value="">                    
                <label for="checkall" style="margin-right:10px;">Select All</label>
                <button type="submit" class="btn btn-danger btn_delete" name="delete_rec" value="delete_wall" onclick="return confirm('Are you sure you want to delete this items?');"><i class="fa fa-trash"></i> Delete</button>                 
              </div>               
            </div>            
          </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
                 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <?php echo $client_lang[$_SESSION['msg']] ; ?></div>
                <?php unset($_SESSION['msg']);}?> 
              </div>
            </div>
          </div>       
          <div class="col-md-12 mrg-top">
            <div class="row">
              <?php 
      
              $i=0;
              while($row=mysqli_fetch_array($result))
              {
              ?>
              <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="block_wallpaper">
                  <div class="wall_category_block">
                    <h2><?php echo $row['category_name'];?></h2>  

                     <?php if($row['featured']!="0"){?>
                         <a href="javascript:void(0)" class="toggle_btn_a" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="featured" data-toggle="tooltip" data-tooltip="Slider"><div style="color:green;"><i class="fa fa-sliders"></i></div></a> 
                      <?php }else{?>
                         <a href="javascript:void(0)" class="toggle_btn_a" data-id="<?php echo $row['id'];?>" data-action="active" data-column="featured" data-tooltip="Add to Slider"><i class="fa fa-sliders"></i></a> 
                      <?php }?>

                      <div class="checkbox" style="float: right;">
                          <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $row['id']; ?>" class="post_ids">
                          <label for="checkbox<?php echo $i;?>">
                          </label>
                        </div>

                  </div>
                  <div class="wall_image_title">
                     <p style="font-size: 16px;"><?php echo $row['video_title'];?></p>

                     <p>By: <a href="manage_user_history.php?user_id=<?php echo $row['user_id'];?>" style="color: #ddd"><?=ucwords(get_user_info($row['user_id'],'name'))?></a> 
                      <?php 
                        if(get_user_info($row['user_id'],'is_verified')==1){
                          echo '<img src="assets/images/verification_150.png" style="border: none;width: 15px !important;height: 15px !important">';
                        }
                      ?>
                     </p>
                    <ul>
                      <?php if($row['video_layout']=='Portrait'){?>
                        <li><a href="javascript:void(0)" data-toggle="tooltip" data-tooltip="Portrait"><i class="fa fa-mobile"></i></a></li>
                      <?php }else{?>
                        <li><a href="javascript:void(0)" data-toggle="tooltip" data-tooltip="Landscape"><i class="fa fa-mobile" style="transform:rotate(90deg);"></i></a></li>
                      <?php }?>  
                      
                     <li><a href="" class="btn_preview" data-title="<?=$row['video_title']?>" data-url="<?=$row['video_url']?>" data-toggle="tooltip" data-tooltip="Video Preview"><i class="fa fa-video-camera"></i></a></li>

                      <li><a href="javascript:void(0)" data-toggle="tooltip" data-tooltip="<?php echo $row['totel_viewer'];?> Views"><i class="fa fa-eye"></i></a></li>                      

                      <li><a href="edit_video.php?video_id=<?php echo $row['id'];?>" target="_blank" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>

                      <li><a href="" data-toggle="tooltip" data-tooltip="Delete" class="btn_delete_a" data-id="<?php echo $row['id'];?>"><i class="fa fa-trash"></i></a></li>

                      <?php if($row['status']!="0"){?>
                       <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li> 

                      <?php }else{?>
                      
                       <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-id="<?=$row['id']?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li> 
                  
                      <?php }?>  


                    </ul>
                  </div>
          
                  <span>
                    <?php if($row['video_thumbnail']!=""){?>
                    <img src="images/<?php echo $row['video_thumbnail'];?>" />
                    <?php }else{?>
                    <img src="images/default_img.jpg" />
                  </span>                     
                  <?php }?>  
                 </div>
              </div>
              <?php
              $i++;
              }
              ?>
            </div>

            <div class="pagination_item_block">
              <nav>
              <?php include("user_history_pagination.php");?>
              </nav>
            </div>
      </div>
    </div>
    </div>    
  </div> 
    <!-- Video Preview Modal -->
    <div id="videoPreview" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="padding-top: 15px;padding-bottom: 15px;background: rgba(0,0,0.05);border-bottom-width: 0px;">
            <button type="button" class="close" data-dismiss="modal" style="color: #fff;font-size: 35px;font-weight: normal;opacity: 1">&times;</button>
            <h4 class="modal-title" style="color: #fff"></h4>
          </div>
          <div class="modal-body" style="padding: 0px;background: #000">
             <iframe width="100%" height="500" style="border:0" src=""></iframe>
          </div>
        </div>

      </div>
    </div>
    
        
<?php include("includes/footer.php");?>       

<script type="text/javascript">

  $('#videoPreview').on('hidden.bs.modal', function(){
      $("#videoPreview iframe").removeAttr("src");
  });

  $(".btn_preview").on("click",function(e){
    e.preventDefault();
    $("#videoPreview .modal-title").text($(this).data("title"));
    if($(this).data("url").substring(0,4)=='http' && $(this).data("url").substring(0,4)!='asse'){
      $("#videoPreview iframe").attr('src',$(this).data("url"));  
    }
    else if($(this).data("url").substring(0,4)=='asse'){
      $("#videoPreview iframe").attr('src',$(this).data("url"));  
    }
    else{
      $("#videoPreview iframe").attr('src','uploads/'+$(this).data("url"));
    }
    
    $("#videoPreview").modal("show");
  });


  $(".toggle_btn a, .toggle_btn_a").on("click",function(e){
    e.preventDefault();
    var _for=$(this).data("action");
    var _id=$(this).data("id");
    var _column=$(this).data("column");
    var _table='tbl_video';

    $.ajax({
      type:'post',
      url:'processData.php',
      dataType:'json',
      data:{id:_id,for_action:_for,column:_column,table:_table,'action':'toggle_status','video_status':'yes','tbl_id':'id'},
      success:function(res){
          console.log(res);
          if(res.status=='1'){
            location.reload();
          }
          else if(res.status=='2'){
            swal("This account is currently suspended !");
          }
        }
    });

  });

  

  $(".actions").click(function(e){
      e.preventDefault();

      var _ids = $.map($('.post_ids:checked'), function(c){return c.value; });
      var _action=$(this).data("action");

      if(_ids!='')
      {
        swal({
          title: "Do you really want to perform?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger btn_edit",
          cancelButtonClass: "btn-warning btn_edit",
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          closeOnConfirm: false,
          closeOnCancel: false,
          showLoaderOnConfirm: true
        },
        function(isConfirm) {
          if (isConfirm) {

            var _table='tbl_video';

            $.ajax({
              type:'post',
              url:'processData.php',
              dataType:'json',
              data:{id:_ids,for_action:_action,table:_table,'action':'multi_action'},
              success:function(res){
                  console.log(res);
                  if(res.status=='1'){
                    swal({
                        title: "Successfully", 
                        text: "You have successfully done", 
                        type: "success"
                    },function() {
                        location.reload();
                    });
                  }
                }
            });
          }
          else{
            swal.close();
          }

        });
      }
      else{
        swal("Sorry no video selected !!");
      }
  });


  $(".btn_delete_a").click(function(e){

    e.preventDefault();

    var _id=$(this).data("id");

    swal({
        title: "Are you sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger btn_edit",
        cancelButtonClass: "btn-warning btn_edit",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true
      },
      function(isConfirm) {
        if (isConfirm) {

          $.ajax({
            type:'post',
            url:'processData.php',
            dataType:'json',
            data:{id:_id,'action':'delete_video'},
            success:function(res){
                console.log(res);
                if(res.status=='1'){
                  swal({
                      title: "Successfully", 
                      text: "Video is deleted...", 
                      type: "success"
                  },function() {
                      location.reload();
                  });
                }
              }
          });
        }
        else{
          swal.close();
        }
    });
  });

</script>