<?php include("includes/header.php");

	require("includes/function.php");
	require("language/language.php");

	
	//Get all spinner blocks 
	$qry="SELECT * FROM tbl_spinner";
	$result=mysqli_query($mysqli,$qry);
	
	if(isset($_GET['block_id']))
	{
    
		Delete('tbl_spinner','block_id='.$_GET['block_id'].'');

      
		$_SESSION['msg']="12";
		header( "Location:spinner.php");
		exit;
		
	}	

  if(isset($_POST['btn_spinner_otn'])){

      $spinner_opt='';
      if(isset($_POST['spinner_opt'])){
        $spinner_opt='true';
      }else{
        $spinner_opt='false';
      }

      $data = array
      (
        'spinner_opt' => $spinner_opt,
        'spinner_limit' => $_POST['spinner_limit'],
      );

      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

      $_SESSION['msg']="11";
      header( "Location:spinner.php");
      exit;

  }

  $qry="SELECT * FROM tbl_settings where id='1'";
  $result1=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result1);

	 
?>

<style type="text/css">
  span.select2{
    margin-bottom: 0px;
  }
</style>
                
    <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Lucky Wheel</div>
            </div>
            <div class="col-md-7 col-xs-12">
              <div class="search_list">
                <div class="add_btn_primary"> <a href="add_block.php">Add Lucky Wheel Block</a> </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
               	 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                	<?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?>	
              </div>
            </div>
          </div>
          <div class="col-md-12 mrg-top">
            <form class="form-inline" action="" method="post">
              <div class="form-group col-md-3">
      				<div class="row toggle_btn" style="margin-top:0;margin-left:0;">
      					<p style="float:left;">Enable/Disable in App:&nbsp;&nbsp;</p>
      				  <input type="checkbox" id="checked07" class="cbx hidden" name="spinner_opt" value="true" <?php if($settings_row['spinner_opt']=='true'){?>checked <?php }?>/>
      				  <label for="checked07" class="lbl" style="top:2px;float:left"></label>
      				</div>
              </div>
              <div class="form-group col-md-3">
                <label for="spinner_limit">Users Per day Limit:&nbsp;&nbsp;</label>
                <input type="text" class="form-control" id="spinner_limit" name="spinner_limit" style="padding-top: 8px;padding-bottom: 8px;padding-left: 15px;padding-right: 15px;width: 80px;margin-bottom: 0px" value="<?=$settings_row['spinner_limit']?>">
              </div>
              <button type="submit" name="btn_spinner_otn" class="btn btn-primary" style="margin-bottom: 0px !important;padding-top: 8px;padding-bottom: 8px;padding-left: 15px;padding-right: 15px;">Save</button>
            </form>
            <div class="clearfix"></div>
            <hr/>
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>                  
                  <th>Block Point</th>
                  <th>Background Color</th>
                  <th class="cat_action_list">Action</th>
                </tr>
              </thead>
              <tbody>
              	<?php	
						$i=0;
						while($row=mysqli_fetch_array($result))
						{					
				?>
                <tr>                 
                  <td><?php echo $row['block_points'];?></td>
                  <td>
                    <div style="width: 100px;height: 35px;border-radius: 5px;background: #<?php echo $row['block_bg'];?>"></div>
                  </td>
                  <td>
                      <a href="edit_block.php?block_id=<?php echo $row['block_id'];?>" class="btn btn-primary btn_edit"><i class="fa fa-edit"></i></a>
                      <a href="?block_id=<?php echo $row['block_id'];?>" class="btn btn-danger btn_delete" onclick="return confirm('Are you sure you want to delete this block?');"><i class="fa fa-trash"></i></a>
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

<?php include("includes/footer.php");?>  

<script type="text/javascript">
  $("#spinner_limit").keyup(function(e){
    if($(this).val()<=0){
      $(this).val('1');
    }
  });
</script>     
