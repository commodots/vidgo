<?php include('includes/header.php'); 
	include("includes/connection.php");
	
    include("includes/function.php");
	include("language/language.php"); 

	if(isset($_GET['filter']))
   	{
      
		if($_GET['filter']=='verified'){
			$status="tbl_users.`is_verified`='1'";
		}
		else if($_GET['filter']=='not_verified'){
			$status="tbl_users.`is_verified`='0'";
		}
		else if($_GET['filter']=='active'){
			$status="tbl_users.`status`='1'";
		}
		else if($_GET['filter']=='suspend'){
			$status="tbl_users.`status`='0'";
		}

		$data_qry="SELECT * FROM tbl_users WHERE tbl_users.`id`!=0 AND $status ORDER BY tbl_users.`id` DESC";                 

		$users_result=mysqli_query($mysqli,$data_qry);
   	}
	else if(isset($_POST['user_search']))
	{

		$user_qry="SELECT * FROM tbl_users WHERE tbl_users.id!=0 AND tbl_users.name like '%".addslashes($_POST['search_value'])."%' OR tbl_users.email like '%".addslashes($_POST['search_value'])."%' OR tbl_users.device_id like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_users.id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	}
	else
	{
	 
		$tableName="tbl_users";		
		$targetpage = "manage_users.php"; 	
		$limit = 15; 

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


		$users_qry="SELECT * FROM tbl_users
			WHERE tbl_users.id!=0 ORDER BY tbl_users.id DESC LIMIT $start, $limit";  
		 
		$users_result=mysqli_query($mysqli,$users_qry);
							
	}
	if(isset($_GET['user_id']))
	{ 
		$id=trim($_GET['user_id']);
		 
		Delete('tbl_comments','user_id='.$id.''); 
		Delete('tbl_users_redeem','user_id='.$id.''); 
		Delete('tbl_users_rewards_activity','user_id='.$id.''); 
		Delete('tbl_like','device_id='.$id.''); 

		Delete('tbl_verify_user','user_id='.$id.''); 

		Delete('tbl_suspend_account','user_id='.$id.''); 
		
		$sql="SELECT user_id FROM tbl_follows WHERE follower_id='$id'";
		$res=mysqli_query($mysqli, $sql);

		while($row=mysqli_fetch_assoc($res)){

			$updateSql="UPDATE tbl_users SET total_followers= total_followers - 1  WHERE id = '".$row['user_id']."'";

			$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
		}


		Delete('tbl_follows','user_id='.$id.'');
		Delete('tbl_follows','follower_id='.$id.'');

		mysqli_free_result($res);

		$sql="SELECT * FROM tbl_video WHERE `user_id`='$id' AND `video_type`='local'";
		$res=mysqli_query($mysqli, $sql);
		while ($row = mysqli_fetch_assoc($res)) {
			if(file_exists('images/thumbs/'.$row['video_thumbnail'])){
				unlink('images/thumbs/'.$row['video_thumbnail']);
			}

			if(file_exists($row['video_url'])){
				unlink($row['video_url']);
			}
		}

		Delete('tbl_video','user_id='.$id.''); 

		Delete('tbl_users','id='.$_GET['user_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:manage_users.php");
		exit;
	}


	if(isset($_POST['delete_rec']))
	{

	    $checkbox = $_POST['post_ids'];
	    
	    for($i=0;$i<count($checkbox);$i++){
	      
	       	$del_id = $checkbox[$i]; 
	      
	         
			Delete('tbl_comments','user_id='.$del_id.''); 
			Delete('tbl_users_redeem','user_id='.$del_id.''); 
			Delete('tbl_users_rewards_activity','user_id='.$del_id.''); 

			Delete('tbl_verify_user','user_id='.$del_id.''); 

			Delete('tbl_suspend_account','user_id='.$del_id.''); 

	      	Delete('tbl_video','user_id='.$del_id.'');  
			Delete('tbl_comments','user_id='.$del_id.''); 
			Delete('tbl_users_redeem','user_id='.$del_id.''); 
			Delete('tbl_users_rewards_activity','user_id='.$del_id.''); 


			$sql="SELECT user_id FROM tbl_follows WHERE follower_id='$del_id'";
			$res=mysqli_query($mysqli, $sql);

			while($row=mysqli_fetch_assoc($res)){

				$updateSql="UPDATE tbl_users SET total_followers= total_followers - 1  WHERE del_id = '".$row['user_id']."'";

				$update=mysqli_query($mysqli,$updateSql) or die(mysqli_error($mysqli));
			}


			Delete('tbl_follows','user_id='.$del_id.'');
			Delete('tbl_follows','follower_id='.$del_id.'');

			mysqli_free_result($res);

			$sql="SELECT * FROM tbl_video WHERE `user_id`='$del_id' AND `video_type`='local'";
			$res=mysqli_query($mysqli, $sql);
			while ($row = mysqli_fetch_assoc($res)) {
				if(file_exists('images/thumbs/'.$row['video_thumbnail'])){
					unlink('images/thumbs/'.$row['video_thumbnail']);
				}

				if(file_exists($row['video_url'])){
					unlink($row['video_url']);
				}
			}

			Delete('tbl_video','user_id='.$del_id.''); 

			Delete('tbl_users','id='.$del_id.'');

	 
	    }

	    $_SESSION['msg']="12";
	    header( "Location:manage_users.php");
	    exit;
	}
	

	$sql_verify="SELECT varify_u.*, user.`name`, user.`email` FROM tbl_verify_user varify_u, tbl_users user WHERE varify_u.`user_id`=user.`id` AND varify_u.`status`='0' ORDER BY varify_u.`id` DESC";

	$res_verify=mysqli_query($mysqli, $sql_verify) or die(mysqli_error($mysqli));
	
	
?>


 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Manage Users</div>
            </div>
            <div class="col-md-7 col-xs-12">              
              <div class="search_list">
                <div class="search_block">

                  <form  method="post" action="">
                    <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" value="<?php if(isset($_POST['user_search'])){ echo $_POST['search_value']; } ?>" name="search_value" required>
                    <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                  </form>  
                </div>
                <div class="add_btn_primary"> <a href="add_user.php?add">Add User</a> </div>
              </div> 
            </div>
            <div class="col-md-8">
	          	<h4 style="float: left;">Filter: |</h4>
	          	<div class="search_list" style="padding: 0px 0px 5px;float: left;margin-left: 10px">
		            <select name="filter_status" class="form-control filter_status" required style="padding: 5px 10px;height: 40px;">
		                <option value="">All</option>
		                <option value="verified" <?php if(isset($_GET['filter']) && $_GET['filter']=='verified'){ echo 'selected';} ?>>Verified</option>
		                <option value="not_verified" <?php if(isset($_GET['filter']) && $_GET['filter']=='not_verified'){ echo 'selected';} ?>>Not verified</option>
		                <option value="active" <?php if(isset($_GET['filter']) && $_GET['filter']=='active'){ echo 'selected';} ?>>Active</option>
		                <option value="suspend" <?php if(isset($_GET['filter']) && $_GET['filter']=='suspend'){ echo 'selected';} ?>>Suspended</option>
		              </select>
		          </div>
		    </div>
		    <div class="col-md-4 col-xs-12 text-right" style="float: right;">
		    	<form method="post" action="">
		            <button type="submit" class="btn btn-danger btn_delete pull-right" style="margin-bottom:20px;" name="delete_rec" value="delete_post" onclick="return confirm('Are you sure you want to delete this users ?');"><i class="fa fa-trash"></i> Delete All</button>
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
          <div class="col-md-12 mrg-top manage_user_btn">
          	
		            <table class="table table-striped table-bordered table-hover">
		              <thead>
		                <tr>
		                  <th style="width:50px" nowrap="">
		                  	<div class="checkbox" style="margin-top: 0px;margin-bottom: 0px;">
						    	<input type="checkbox" name="checkall" id="checkall" value="">
						    	<label for="checkall"></label> 
						    </div>					
							
						  </th>		
						  <th style="width:110px">Device ID</th>
		                  <th style="width:110px">Name</th>						 
						  <th style="width:240px">Email</th>
						  <th style="width:30px">Points</th>				  
						  <th style="width:100px">Verify Status</th>
						  <th style="width:100px">Status</th>	 
		                  <th style="width:170px" class="text-center">Action</th>
		                </tr>
		              </thead>
		              <tbody>
		              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
								 
						?>
		                <tr <?php if($users_row['is_duplicate']==1){ echo 'style="background-color: rgba(255,0,0,0.1);"'; } ?> >
		                   <td> 
		        			<div>
						      <div class="checkbox">
						        <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['id']; ?>">
						        <label for="checkbox<?php echo $i;?>">
						        </label>
						      </div>
						    </div>
		      			   </td>
		      			   <td><?php echo $users_row['device_id'];?></td>
		                   <td><?php echo $users_row['name'];?></td>
				           <td><?php echo $users_row['email'];?></td>   
				           <td><?php echo $users_row['total_point'];?></td>		
				           <td>
				          	 <?php if($users_row['is_verified']=="1"){?>
				              <span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Verified</span></span>

				              <?php }else if($users_row['is_verified']=="0"){?>
				              <span class="badge badge-danger badge-icon"><i class="fa fa-exclamation" aria-hidden="true"></i><span>Not verified </span></span>
				              <?php }else if($users_row['is_verified']=="2"){?>
				              <span class="badge badge-danger badge-icon"><i class="fa fa-ban" aria-hidden="true"></i><span>Rejected </span></span>
				              <?php }?>
		                   </td>          
				           <td>
				          	  <?php if($users_row['status']!="2"){?>
				              	<a href="" class="btn_status" data-id="<?=$users_row['id']?>" data-action="suspend" title="Change Status"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Active</span></span></a>

				              <?php }else{?>
				              	<a href="" class="btn_status" data-id="<?=$users_row['id']?>" data-action="active" title="Change Status"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Suspended </span></span></a>
				              <?php }?>
		              		</td>
		                   <td nowrap="">
		                   	<a href="manage_user_history.php?user_id=<?php echo $users_row['id'];?>" class="btn btn-success" data-toggle="tooltip" data-tooltip="User History"><i class="fa fa-history"></i></a>
		                   	<a href="add_user.php?user_id=<?php echo $users_row['id'];?>" class="btn btn-primary" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a>
		                    <a href="manage_users.php?user_id=<?php echo $users_row['id'];?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-danger" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>
		                </tr>
		               <?php
								
								$i++;
								}
					   ?>
		              </tbody>
		            </table>
	        	</form>
	            <!-- Pagination -->

	            <div class="col-md-12 col-xs-12">
		            <div class="pagination_item_block">
		              <nav>
		              	<?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
		              </nav>
		            </div>
		        </div>

              </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div> 

   <div id="suspendModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="border-radius: 5px;overflow: hidden;">
          <div class="modal-header" style="padding-top: 15px;padding-bottom: 15px;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Suspend Account</h4>
          </div>
          <div class="modal-body">
          	<form id="suspendForm">
          		<div class="form-group">
          			<label style="font-weight: 500">Reason for Suspension:</label>
          			<textarea placeholder="E.g. Upload same video multiple times, Having multiple times accounts and so on.." class="form-control" name="suspend_reason" required=""></textarea>
          		</div>
          		<div class="form-group">
                  <button type="submit" name="submit" class="btn btn-primary">Save</button>
              	</div>
          	</form>
          </div>
        </div>

      </div>
    </div> 

<?php include('includes/footer.php');?> 


<script type="text/javascript">
	$(".btn_status").on("click",function(e){
		e.preventDefault();
		var _id=$(this).data("id");
		var _action=$(this).data("action");

		 swal({
          title: "Are you sure ?",
          text: "To change the status of account !",
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

          	if(_action=='suspend'){

          		$("#suspendModal").modal("show");
          		swal.close();

          		$("#suspendForm").submit(function(e){
          			e.preventDefault();

          			$.ajax({
		              type:'post',
		              url:'processData.php',
		              dataType:'json',
		              data : $("#suspendForm").serialize()+"&for_action="+_action+"&id="+_id+"&action=account_status",
		              success:function(res){
		                  console.log(res);
		                  if(res.status=='1'){
		                  	$("#suspendModal").modal("hide");
		                    swal({
		                        title: "Suspended", 
		                        text: "User's account is successfully suspend !!", 
		                        type: "success"
		                    },function() {
		                        location.reload();
		                    });
		                  }
		                  else if(res.status=='0'){
		                  	alert(res.message);
		                  	swal.close();
		                  }
		                }
		            });

          		});

          		
          	}else{
      			$.ajax({
	              type:'post',
	              url:'processData.php',
	              dataType:'json',
	              data:{for_action:_action,id:_id,'action':'account_status'},
	              success:function(res){
	                  console.log(res);
	                  if(res.status=='1'){
	                  	$("#suspendModal").modal("hide");
	                    swal({
	                        title: "Activated", 
	                        text: "User's account is successfully activated !!", 
	                        type: "success"
	                    },function() {
	                        location.reload();
	                    });
	                  }
	                  else if(res.status=='0'){
	                  	alert(res.message);
	                  	swal.close();
	                  }
	                }
	            });
          	}

          }
          else{
            swal.close();
          }

      	});



	});


	$(".filter_status").on("change",function(e){
    	var _val=$(this).val();
    	if(_val!=''){
      		window.location.href="manage_users.php?filter="+_val;
    	}else{
      		window.location.href="manage_users.php";
    	}
  });

</script>