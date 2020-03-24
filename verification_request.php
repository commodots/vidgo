<?php include('includes/header.php'); 
	include("includes/connection.php");
	
    include("includes/function.php");
	include("language/language.php"); 

	if(isset($_POST['user_search']))
	 {
		 
		$search=addslashes(trim($_POST['search_value']));
		
		$sql_verify="SELECT varify_u.*, user.`name`, user.`email` FROM tbl_verify_user varify_u, tbl_users user WHERE varify_u.`user_id`=user.`id` AND varify_u.`status`='0' AND (user.`name` LIKE '%$search%') ORDER BY varify_u.`id` DESC";

		$res_verify=mysqli_query($mysqli, $sql_verify) or die(mysqli_error($mysqli));
		
		 
	 }
	 else
	 {
	 
		$tableName="tbl_verify_user";		
		$targetpage = "verification_request.php"; 	
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


		$sql_verify="SELECT varify_u.*, user.`name`, user.`email` FROM tbl_verify_user varify_u, tbl_users user WHERE varify_u.`user_id`=user.`id` AND varify_u.`status`='0' ORDER BY varify_u.`id` DESC";

		$res_verify=mysqli_query($mysqli, $sql_verify) or die(mysqli_error($mysqli));
							
	}
	
	
?>


<div class="row">
  <div class="col-xs-12">
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title">Verification Requests</div>
        </div>
        <div class="col-md-7 col-xs-12">              
          <div class="search_list">
            <div class="search_block">
              <form  method="post" action="">
                <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>  
            </div>
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
      <div class="col-md-12 mrg-top manage_user_btn">
      	<div>
          <div class="form-group col-md-4">
  				<div class="row toggle_btn" style="margin-top:0;margin-left:0;">
  					<p style="float:left;font-weight: 600;color: #222">Auto Approve Videos Of Verified Users:&nbsp;&nbsp;</p>
  				  <input type="checkbox" id="checked07" class="cbx hidden" name="auto_approve" value="true" <?php if($settings_details['auto_approve']=='on'){?>checked <?php }?>/>
  				  <label for="checked07" class="lbl" style="top:2px;float:left"></label>
  				</div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-12">
            <br/>
            <p style="color: red"><strong>Note: </strong>Enable to automatically approve uploaded videos by verified users. If disabled then it will not be automatically approved. This feature is for only verified users.</p>
          </div>
          
      	</div>
      	
        <div class="clearfix"></div>
        <hr/>
      	<table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>	
                  <th style="width:110px">Name</th>						 
				  <th style="width:240px">Email</th>			
				  <th style="width:240px">Full Name</th>			
				  <th style="width:120px" nowrap="">Requested On</th>	 
                  <th style="width:170px" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
              	<?php
				$i=0;
				while($row=mysqli_fetch_array($res_verify))
				{		 
				?>
                <tr>
                   <td><?php echo $row['name'];?></td>
		           <td><?php echo $row['email'];?></td> 
		           <td><?php echo $row['full_name'];?></td>   
		           <td><?php echo date('d M, Y',$row['created_at']);?></td> 
                   <td nowrap="">
                   		<a href="" class="btn btn-success btn_delete btn_verify" data-id="<?=$row['id']?>" data-toggle="tooltip" data-tooltip="User verification"><i class="fa fa-check"></i> Verify</a>
                    	<a href="verification_request.php?user_id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-danger" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a>
                	</td>
                </tr>
               <?php	
					$i++;
				}
			   ?>
              </tbody>
        </table>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div> 

    

<?php include('includes/footer.php');?>                  

<script type="text/javascript">

	$("li.dropdown-header").nextAll("li").remove();
	$.ajax({
      type:'post',
      url:'processData.php',
      dataType:'json',
      data:{'action':'openAllNotify'},
      success:function(data){
          console.log(data.content[0]);
	      $(".notify_count").html(data.count);
	      $.each(data.content, function(index, item) {
	      	$(".dropdown-header").after(item);
	      });
	    }
	});


	$("input[name='auto_approve']").click(function(e){

		var _val=$("input[name='auto_approve']:checked").val();

		$.ajax({
	      type:'post',
	      url:'processData.php',
	      dataType:'json',
	      data:{'action':'auto_approve','auto_approve':_val},
	      success:function(res){
		  }
		});
	});

</script>