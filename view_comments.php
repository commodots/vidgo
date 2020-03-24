<?php 

	include('includes/header.php'); 
	include('includes/function.php');
	include('language/language.php');

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



	function total_comments($post_id)
	{
		global $mysqli;

		$query="SELECT COUNT(*) AS total_comments FROM tbl_comments WHERE `post_id`='$post_id'";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());
		$row=mysqli_fetch_assoc($sql);
		return stripslashes($row['total_comments']);
	}

	function get_thumb($filename,$thumb_size)
	{	
		$protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

		$file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

		return $thumb_path=$file_path.'thumb.php?src='.$filename.'&size='.$thumb_size;
	}


 	$id=trim($_GET['post_id']);

	$sql="SELECT * FROM tbl_video
	LEFT JOIN tbl_category
	ON tbl_video.cat_id=tbl_category.cid 
	WHERE tbl_video.status='1' AND tbl_video.id='$id'";

	$res=mysqli_query($mysqli,$sql);
	$row=mysqli_fetch_assoc($res);


 	$sql1="SELECT tbl_comments.*, tbl_users.`name`, tbl_users.`user_image` FROM tbl_comments, tbl_users WHERE tbl_comments.`post_id`='$id' and tbl_users.`id`=tbl_comments.`user_id` ORDER BY tbl_comments.`id` DESC";
	$res_comment=mysqli_query($mysqli, $sql1) or die(mysqli_error($mysqli));
	$arr_dates=array();
	$i=0;
	while($comment=mysqli_fetch_assoc($res_comment)){
		$dates=date('d M Y',$comment['comment_on']);
		$arr_dates[$dates][$i++]=$comment;
	}


	// $dates=date('d M Y',$comment['comment_on']);

	// $arr_dates=array();


	// $output = json_encode(array($dates => $arr_dates));

	// print_r($output);

?>
<div class="app-messaging-container">
	<div class="app-messaging" id="collapseMessaging">
		<div class="chat-group">
		<div class="heading" style="font-size: 16px">Video Description</div>
			<ul class="group full-height">
				<li class="message">
					<a href="javascript:void(0)">
					<div class="message">
					<i class="fa fa-tags"></i>
					<div class="content">
					<div class="title">&nbsp;&nbsp;<?=$row['category_name']?></div>
					</div>
					</div>
					</a>
				</li>
				<li class="message">
					<a href="javascript:void(0)">
					<div class="message">
					<i class="fa fa-eye"></i>
					<div class="content">
					<div class="title">&nbsp;&nbsp;<?=$row['totel_viewer']?> Views</div>
					</div>
					</div>
					</a>
				</li>
				<li class="message">
					<a href="javascript:void(0)">
					<div class="message">
					<i class="fa fa-comments-o"></i>
					<div class="content">
					<div class="title">&nbsp;&nbsp;<span class="total_comments"><?=total_comments($id)?></span> Comments</div>
					</div>
					</div>
					</a>
				</li>
			</ul>
		</div>
	<div class="messaging">
		<div class="heading">
			<div class="title" style="font-size: 16px">
				<a class="btn-back" href="user_interaction.php">
					<i class="fa fa-angle-left" aria-hidden="true"></i>
				</a>
				<strong style="font-weight: 600">Title: </strong>&nbsp;&nbsp;<?=$row['video_title']?>
			</div>
			<div class="action"></div>
		</div>
		<ul class="chat" style="flex: unset;height: 500px;">
		<?php 
		if(!empty($arr_dates))
		{
			foreach ($arr_dates as $key => $val) {
			?>
			<li class="line">
				<div class="title"><?=$key?></div>
			</li>
			<?php 
			foreach ($val as $key1 => $value) {

				$img='';
				if(!file_exists('images/'.$value['user_image']) || $value['user_image']==''){
					$img='user-icons.jpg';
				}else{
					$img=$value['user_image'];
				}
			?>
			<li class="<?=$value['id']?>" style="padding-right: 20px">

			<div class="message" style="padding: 5px 10px 15px 5px;min-height: 60px">
			<img src="<?=get_thumb('images/'.$img,'50x50')?>" style="float: left;margin-right: 10px;border-radius: 50%;box-shadow: 0px 0px 2px 1px #ccc">
			<span style="color: #000;font-weight: 600"><a href="manage_user_history.php?user_id=<?php echo $value['user_id'];?>"><?=$value['name']?></a></span>
			<br/>
			<span>
			<?=$value['comment_text']?>	
			</span>
			</div>
			<div class="info" style="clear: both;">
			<div class="datetime">
			<?php 
       			echo time_elapsed_string('@'.$value['comment_on']);
       		?>
			<a href="" class="btn_delete" data-id="<?=$value['id']?>" data-post="<?=$id?>" style="color: red;text-decoration: none;"><i class="fa fa-trash"></i> Delete</a>
			</div>
			</div>
			</li>
			<?php } // end of inner foreach
			}	// end of main foreach
		}	// end of if
		else{
		?>
		<div class="jumbotron" style="width: 100%; text-align: center;">
		<h3>Sorry !</h3> 
		<p>No comments available</p> 
		</div>
		<?php
		} 
		?>
		</ul>
	</div>
</div>
</div>


<?php 
include('includes/footer.php');
?> 

<script type="text/javascript">
	$(".btn_delete").click(function(e){
		e.preventDefault();
		var _id=$(this).data("id");
		var _post=$(this).data("post");
		if(confirm('Are you sure you want to delete this comment ?')){
			var _element=$(this).parents("ul").find("."+_id);	

			$.ajax({
				type:'post',
				url:'processData.php',
				dataType:'json',
				data:{id:_id,post_id:_post,'action':'removeComment'},
				success:function(res){
					console.log(res);
					if(res.status=='1'){
						_element.remove();
						$(".total_comments").text(res.total_comments);
						
					}
				}
			});

		}

	});
</script>