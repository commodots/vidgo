<?php  
    include("includes/connection.php");

    function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hr',
	        'i' => 'min',
	        's' => 'sec',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}


	function get_video_info($post_id)
	{
		global $mysqli;

		$query="SELECT * FROM tbl_video WHERE tbl_video.id='".$post_id."'";

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
		$row=mysqli_fetch_assoc($sql);

		return stripslashes($row['video_title']);
	}

	function total_comments($post_id)
	{
		global $mysqli;

		$query="SELECT COUNT(*) AS total_comments FROM tbl_comments WHERE `post_id`='$post_id'";
		$sql = mysqli_query($mysqli,$query) or die(mysqli_error());
		$row=mysqli_fetch_assoc($sql);
		
		return stripslashes($row['total_comments']);
	}
	 
		$tableName="tbl_comments";		
		$targetpage = "user_interaction.php"; 	
		$limit = 20; 
		
		$query = "SELECT COUNT(*) as num FROM $tableName";
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
		
		$users_qry="SELECT comment.`id`, comment.`post_id`, max(comment.`comment_on`) as comment_on FROM
				tbl_comments comment
				GROUP BY comment.`post_id`
				ORDER BY comment.`id` DESC LIMIT $start, $limit";	
		 
		$users_result=mysqli_query($mysqli,$users_qry);
							
	 
	if(isset($_GET['post_id']))
	{
		
		Delete('tbl_comments','post_id='.$_GET['post_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:user_interaction.php");
		exit;
	}
	
 if(isset($_POST['delete_rec']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      Delete('tbl_comments','post_id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:user_interaction.php");
    exit;
  } 
	
	
?>



<div class="col-md-12 mrg-top manage_comment_btn">

	<form method="post" action="">
	<button type="submit" class="btn btn-danger btn_delete" style="margin-bottom:20px;" name="delete_rec" value="delete_post" onclick="return confirm('Are you sure you want to delete this comments ?');"><i class="fa fa-trash"></i> Delete All</button>	

<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      	<th style="width:40px">
          	<div class="checkbox" style="margin: 0px">
		    	<input type="checkbox" name="checkall" id="checkall" value="">
		    	<label for="checkall"></label>
		    </div>
	  	</th>	
		  	<th>Video Title</th>	
	  	<th>Total Comment</th>	
	  	<th>Last Comment</th>	  
      	<th class="cat_action_list" style="width:60px">Action</th>
    </tr>
  </thead>
  <tbody>
  	<?php
		$i=0;
		while($users_row=mysqli_fetch_array($users_result))
		{	 
	?>
    <tr>
       <td> 
			 
		<div>
	      <div class="checkbox">
	        <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['post_id']; ?>">
	        <label for="checkbox<?php echo $i;?>">
	        </label>
	      </div>
	      
	    </div>
		   </td>	
           <td><?php echo get_video_info($users_row['post_id']);?></td>
           <td>
       		<a href="view_comments.php?post_id=<?=$users_row['post_id']?>"><?php echo total_comments($users_row['post_id']);?> Comments</a>
       	</td>
       	<td>
       		<?php 
       			echo time_elapsed_string('@'.$users_row['comment_on']);
       		?>
       		
       	</td>        
       	<td> 
        <a href="user_interaction.php?post_id=<?php echo $users_row['post_id'];?>" onclick="return confirm('Are you sure you want to delete this comment?');" class="btn btn-danger" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>
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