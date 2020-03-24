<?php 
	include("includes/header.php");
	require("includes/function.php");
	require("language/language.php");
	
	if(isset($_GET['edit_id'])){
		$id=$_GET['edit_id'];
		$sql="SELECT * FROM tbl_contact_sub WHERE id='$id'";
		$row=mysqli_fetch_assoc(mysqli_query($mysqli, $sql));
	}
	
	if(isset($_POST['submit']))
	{
		foreach($_POST['title'] as $key => $value) {
	       $data = array(
	            'title'  =>  $value
	        );  

	        if(isset($_GET['edit_id'])){
	        	$edit=Update('tbl_contact_sub', $data, "WHERE id = '$id'");
	        }
	        else{
	        	$qry = Insert('tbl_contact_sub',$data); 	
	        }

	      	
	    }
	    
	    if(isset($_GET['edit_id'])){
        	$_SESSION['msg']="11";
	    	header( "Location:contact_subject.php?edit_id=".$id);
        }
        else{
        	$_SESSION['msg']="10";
	    	header( "Location:contact_subject.php");
        }
	    
	    exit; 	
	}

?>
<div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $_GET['edit_id'] ? 'Edit' : 'Add' ?> Contact Subject</div>
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
            <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">

              <div class="section">
                <div class="section-body">
	                <div class="input-container">
	                  <div class="form-group">
	                    <label class="col-md-3 control-label">Title :-</label>
	                    <div class="col-md-6">
	                      <input type="text" name="title[]" placeholder="Enter subject title" class="form-control" value="<?php if(isset($_GET['edit_id'])){ echo $row['title']; } ?>" required>
	                      <a href="" class="btn_remove" style="float: right;color: red;font-weight: 600;opacity: 0">&times; Remove</a>
	                    </div>
	                  </div>
	              	</div>
	              	<?php 

	              		if(!isset($_GET['edit_id']))
	              		{
	              			?>
	              			<div id="dynamicInput"></div>
			                <div class="form-group">
			                    <div class="col-md-9 col-md-offset-3">                      
			                      <button type="button" class="btn btn-success btn-xs add_more">Add More Subject</button>
			                    </div>
			                  </div>
			                <br/>
	              			<?php
	              		}
	              	?>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
        
<?php include("includes/footer.php");?>   

<script type="text/javascript">

  $(".btn_remove:eq(0)").hide();

  $(".add_more").click(function(e){

    var _html=$(".input-container").html();
      
    $("#dynamicInput").append(_html);

    $(".btn_remove:not(:eq(0))").css("opacity","1").show();

    $(".btn_remove").click(function(e){
      e.preventDefault();
      $(this).parents(".form-group").remove();
    });
  });

  $(".btn_remove").click(function(e){
    e.preventDefault();
    $(this).parents(".form-group").remove();
  });
</script>
 
  