<?php  
  
  include("includes/connection.php");
    
  // Get page data
  $tableName="tbl_reports";    
  $targetpage = "manage_reports.php";  
  $limit = 15; 
  
  $query = "SELECT COUNT(*) as num FROM $tableName LEFT JOIN tbl_video ON tbl_reports.video_id= tbl_video.id ORDER BY tbl_reports.id";
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


    $sql="SELECT report.*, video.`video_title`, user.`name`, user.`email` FROM tbl_reports report
          JOIN tbl_video video ON report.`video_id`=video.`id`
          JOIN tbl_users user ON report.`user_id`=user.`id`
          ORDER BY report.`id` DESC LIMIT $start, $limit";   

    $result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
 
  
  if(isset($_GET['report_id']))
  {
    Delete('tbl_reports','id='.$_GET['report_id'].'');

    $_SESSION['msg']="12";
    header( "Location:user_interaction.php");
    exit;
     
  } 


  if(isset($_POST['delete_rec1']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      Delete('tbl_reports','id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:user_interaction.php");
    exit;
  } 
   
?>

<div class="col-md-12 mrg-top manage_report_btn">

  <form method="post" action="">
    <button type="submit" class="btn btn-danger btn_delete" style="margin-bottom:20px;" name="delete_rec1" value="delete_post" onclick="return confirm('Are you sure you want to delete this reports ?');"><i class="fa fa-trash"></i> Delete All</button>
    
  <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th style="width:40px">
          <div class="checkbox" style="margin: 0px">
          <input type="checkbox" name="checkall" id="checkall" value="">
          <label for="checkall"></label>
          </div>
        </th> 
        <th style="width:120px;">Name</th>
        <th style="width:140px;">Email</th>
        <th style="width:190px;">Video Title</th>
        <th style="width:120px;">Report Type</th>
        <th style="width:130px;">Report</th> 
        <th class="cat_action_list" style="width: 40px;">Action</th>
      </tr>
    </thead>
    <tbody>
    	<?php
	$i=0;
	while($row=mysqli_fetch_array($result))
	{
	 
?>
      <tr>
        <td> 
       
        <div>
        <div class="checkbox">
          <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $row['id']; ?>">
          <label for="checkbox<?php echo $i;?>">
          </label>
        </div>
        
      </div>
     </td>
        <td><?php echo $row['name'];?></td>
        <td><?php echo $row['email'];?></td>
        <td><?php echo $row['video_title'];?></td>
        <td><?php echo $row['type'];?></td>
        <td style="width: 120px;"><p><?php echo $row['report'];?></p></td>                  
        <td>
          <a href="user_interaction.php?report_id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure you want to delete this report?');" class="btn btn-default" class="btn btn-default" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>
      </tr>
     <?php
	
	$i++;
	}
?>
    </tbody>
  </table>

  </form> 
</div>
<div class="col-md-12 col-xs-12">
  <div class="pagination_item_block">
    <nav>
    	<?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
    </nav>
  </div>
</div>