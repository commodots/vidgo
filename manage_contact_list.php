<?php  
	include("includes/header.php");
	include("includes/connection.php");
	require("includes/function.php");
	require("language/language.php");
	 

	error_reporting(E_ALL);
	 
	$tableName="tbl_contact_list";		
	$targetpage = "manage_contact_list.php"; 	
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
		
		
	
							
	 
	if(isset($_GET['contact_id']))
	{
		  
		 
		Delete('tbl_contact_list','id='.$_GET['contact_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:manage_contact_list.php");
		exit;
	}
	
	if(isset($_POST['delete_rec2']))
	{

		$checkbox = $_POST['post_ids'];

		for($i=0;$i<count($checkbox);$i++){

			$del_id = $checkbox[$i]; 

			Delete('tbl_contact_list','id='.$del_id.'');

		}

		$_SESSION['msg']="12";
		header( "Location:manage_contact_list.php");
		exit;
	} 


	if(isset($_GET['sub_id'])){
		Delete('tbl_contact_sub','id='.$_GET['sub_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:manage_contact_list.php");
		exit;
	}
	
	
?>

<div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Users Interaction</div>
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
          <div class="card-body mrg_bottom"> 

            <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 10px">
                <li role="presentation" class="active"><a href="#subject_list" aria-controls="comments" role="tab" data-toggle="tab"><i class="fa fa-comments"></i> Subjects List</a></li>
                <li role="presentation"><a href="#contact_list" aria-controls="contact_list" role="tab" data-toggle="tab"><i class="fa fa-envelope"></i> Contact Forms</a></li>
            </ul>

            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="subject_list">
              	<br/>
              	<div class="add_btn_primary"> <a href="contact_subject.php?add" class="btn_delete">Add Subject</a></div>
              	<div class="clearfix"></div>
              	<br/>
               	<table class="table table-striped table-bordered table-hover">
				    <thead>
				      <tr>
				      	<th width="100">Sr No.</th>
				        <th>Subject Title</th>
				        <th class="cat_action_list" style="width:60px">Action</th>
				      </tr>
				    </thead>
				    <tbody>
				    <?php 
				    	$sql="SELECT * FROM tbl_contact_sub ORDER BY id DESC";
				    	$res=mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
				    	$no=1;
				    	while ($row=mysqli_fetch_assoc($res)) {
				    	?>
				    	<tr>
				    		<td><?=$no++?></td>
				    		<td><?=$row['title']?></td>
				    		<td nowrap="">
		                      <a href="contact_subject.php?edit_id=<?php echo $row['id'];?>" class="btn btn-primary btn_edit"><i class="fa fa-edit"></i></a>
		                      <a href="?sub_id=<?php echo $row['id'];?>" class="btn btn-danger btn_delete" onclick="return confirm('Are you sure you want to delete this subject?');"><i class="fa fa-trash"></i></a>
		                  	</td>
				    	</tr>
				    	<?php
				    	}
				    ?>
				    </tbody>
				</table>

              </div>

              <!-- for contact list tab -->
              <div role="tabpanel" class="tab-pane" id="contact_list">
                <div class="col-md-12 mrg-top manage_comment_btn">
					<form method="post" action="">

				  	<button type="submit" class="btn btn-danger btn_delete" style="margin-bottom:20px;" name="delete_rec2" value="delete_post" onclick="return confirm('Are you sure you want to delete this items ?');"><i class="fa fa-trash"></i> Delete All</button>	
				  <table class="table table-striped table-bordered table-hover">
				    <thead>
				      <tr>
				        <th style="width:40px">
				        	<div class="checkbox" style="margin: 0px">
				            <input type="checkbox" name="checkall" id="checkall" value="">
				            <label for="checkall"></label>
				          </div>
				        </th>	
				        <th>Name</th>
				        <th>Email</th>		
				        <th>Subject</th>		
				        <th>Message</th>
				        <th>Date</th>
				        <th class="cat_action_list" style="width:60px">Action</th>
				      </tr>
				    </thead>
				    <tbody>
				    	<?php
				    	$users_qry="SELECT tbl_contact_list.*, sub.`title` FROM tbl_contact_list, tbl_contact_sub sub WHERE tbl_contact_list.`contact_subject`=sub.`id` ORDER BY tbl_contact_list.`id` DESC LIMIT $start, $limit";  
		 
						$users_result=mysqli_query($mysqli,$users_qry);
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
					 
						?>
				      <tr>
				         <td>  
							<div>
							    <div class="checkbox" id="checkbox_contact">
							      <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['id']; ?>">
							      <label for="checkbox<?php echo $i;?>">
							      </label>
							    </div>
							</div>
					   	</td>	
				       	<td><?php echo $users_row['contact_name'];?></td>
				       	<td><?php echo $users_row['contact_email'];?></td>
				       	<td><?php echo $users_row['title'];?></td>
				       	<td><?php echo $users_row['contact_msg'];?></td>
				       	<td><?php echo date('d-m-Y',$users_row['created_at']);?></td>
				        <td> 
				          <a href="manage_contact_list.php?contact_id=<?php echo $users_row['id'];?>" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-default" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>
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
				    	<?php include("pagination.php");?>                 
				    </nav>
				  </div>
				</div>
              </div>
            </div>
        </div>
    </div>
</div>
</div>


<?php include("includes/footer.php");?>       

<script type="text/javascript">
  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    localStorage.setItem('activeTab', $(e.target).attr('href'));

  });

  var activeTab = localStorage.getItem('activeTab');

  if(activeTab){
    $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
  }
</script>

			


 
