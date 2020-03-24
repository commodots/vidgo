<?php include("includes/header.php");

	require("includes/function.php");
	require("language/language.php");

	require_once("thumbnail_images.class.php");

  $id=$_GET['block_id'];
  $sql="SELECT * FROM tbl_spinner WHERE block_id='$id'";
  $res=mysqli_query($mysqli,$sql);
  $row=mysqli_fetch_assoc($res);
	 
	
	if(isset($_POST['submit']))
	{
	   
     extract($_POST);

     $block_points=addslashes(trim($block_points));
     $block_bg=addslashes(trim($block_bg));

     $sql="SELECT * FROM tbl_spinner WHERE block_bg = '$block_bg' AND block_id <> '$id'";
     $res=mysqli_query($mysqli, $sql);

     if(mysqli_num_rows($res) == 0){
        $data = array( 
          'block_points'  =>  $_POST['block_points'],
          'block_bg'  =>  $_POST['block_bg']
        );    

        $qry = Update('tbl_spinner', $data, "WHERE block_id = '".$id."'");
        
        $_SESSION['class']='alert-success';            
        $_SESSION['msg']="11";
     
        header( "Location:edit_block.php?block_id=$id");
        exit; 
     }else{

        $_SESSION['class']='alert-danger';
        $_SESSION['msg']="Background color is already exist !";
     
        header( "Location:edit_block.php?block_id=$id");
        exit;
     }
	   
    

		 
		
	}

?>
<div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Edit Spinner Block</div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
               	 <div class="alert <?=$_SESSION['class']?> alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                	<?php if(!empty($client_lang[$_SESSION['msg']])){ echo $client_lang[$_SESSION['msg']]; }else{ echo $_SESSION['msg']; } ?></a> </div>
                <?php unset($_SESSION['msg'], $_SESSION['class']);}?>	
              </div>
            </div>
          </div>
          <div class="card-body mrg_bottom"> 
            <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">

              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Block Points :-</label>
                    <div class="col-md-6">
                      <input type="text" name="block_points" id="block_points" class="form-control" required value="<?=$row['block_points']?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Block Background :-</label>
                    <div class="col-md-6">
                      <input value="<?=$row['block_bg']?>" name="block_bg" class="form-control jscolor {width:243, height:150, position:'right',
                      borderColor:'#000', insetColor:'#FFF', backgroundColor:'#ddd'}">
                    </div>
                  </div>
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


<script type="text/javascript" src="assets/js/jscolor.js"></script>    
