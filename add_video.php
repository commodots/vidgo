<?php 
  include("includes/header.php");
	include("includes/connection.php");

  include("includes/function.php");
	include("language/language.php"); 

  $protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

  $file_path_img = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

 
	$cat_qry="SELECT * FROM tbl_category ORDER BY category_name";
	$cat_result=mysqli_query($mysqli,$cat_qry); 
	
	if(isset($_POST['submit']))
	{

 
        if ($_POST['video_type']=='server_url')
        {
              $video_url=$_POST['video_url'];

              $video_thumbnail=rand(0,99999)."_".$_FILES['video_thumbnail']['name'];
       
              //Main Image
              $tpath1='images/'.$video_thumbnail;        
              $pic1=compress_image($_FILES["video_thumbnail"]["tmp_name"], $tpath1, 80);
         
              
              $video_id='';

        } 

        if ($_POST['video_type']=='local')
        {
              $file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/uploads/';
              
              
              $video_url=$file_path.$_POST['video_file_name'];

              $video_thumbnail=rand(0,99999)."_".$_FILES['video_thumbnail']['name'];
       
              $tpath1='images/'.$video_thumbnail;        
              $pic1=compress_image($_FILES["video_thumbnail"]["tmp_name"], $tpath1, 80);
         
              $video_id='';
        } 


          
        $data = array( 
			    'cat_id'  =>  $_POST['cat_id'],
			    'video_type'  =>  $_POST['video_type'],
			    'video_title'  =>  addslashes($_POST['video_title']),
          'video_url'  =>  $video_url,
          'video_id'  =>  $video_id,
          'video_layout'  =>  $_POST['video_layout'],
          'video_thumbnail'  =>  $video_thumbnail
			    );		

		 		$qry = Insert('tbl_video',$data);	
        
        $last_id = mysqli_insert_id($mysqli);
        
        if(isset($_POST['notify_user'])){

            $img_path=$file_path_img.'images/'.$video_thumbnail;

            $content = array(
              "en" => "New video is added by Admin",
            );

            $fields = array(
                      'app_id' => ONESIGNAL_APP_ID,
                      'included_segments' => array('All'),
                      'data' => array("foo" => "bar","video_id"=>$last_id),
                      'headings'=> array("en" => APP_NAME),
                      'contents' => $content,
                      'big_picture' =>$img_path                    
                  );

            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                       'Authorization: Basic '.ONESIGNAL_REST_KEY));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $notify_res = curl_exec($ch);
            curl_close($ch);
        }

        
 	    
		  $_SESSION['msg']="10";
 
		  header( "Location:add_video.php");
		  exit;	

		 
	}
	
	  
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
            $(function () {
                $('#btn').click(function () {
                    $('.myprogress').css('width', '0');
                    $('.msg').text('');
                    var video_local = $('#video_local').val();
                    if (video_local == '') {
                        alert('Please enter file name and select file');
                        return;
                    }
                    var formData = new FormData();
                    formData.append('video_local', $('#video_local')[0].files[0]);
                    $('#btn').attr('disabled', 'disabled');
                     $('.msg').text('Uploading in progress...');
                    $.ajax({
                        url: 'uploadscript.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        // this part is progress bar
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.myprogress').text(percentComplete + '%');
                                    $('.myprogress').css('width', percentComplete + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function (data) {
                         
                            $('#video_file_name').val(data);
                            $('.msg').text("File uploaded successfully!!");
                            $('#btn').removeAttr('disabled');
                        }
                    });
                });
            });
        </script> 
<script type="text/javascript">
$(document).ready(function(e) {
           $("#video_type").change(function(){
          
           var type=$("#video_type").val();
              
              if(type=="server_url")
              {
                 
                 $("#video_url_display").show();
                 $("#thumbnail").show();
                 $("#video_local_display").hide();
              }
              else
              {   
                     
                $("#video_url_display").hide();               
                $("#video_local_display").show();
                $("#thumbnail").show();

              }    
              
         });
        });
</script>
<script type="text/javascript">
  
  function fileValidation(){
    var fileInput = document.getElementById('video_local');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.mp4)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('Please upload file having extension .mp4 only.');
        fileInput.value = '';
        return false;
    }else{
        //Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').innerHTML = '<img src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

</script>

  <style type="text/css">
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 55px;
      height: 26px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 4px;
      bottom: 3px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #e91e63;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #e91e63;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
  </style>


<div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Add Video</div>
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
          <div class="card-body mrg_bottom"> 
            <form action="" name="add_form" method="post" class="form form-horizontal" enctype="multipart/form-data">
 
              <div class="section">
                <div class="section-body">
                   <div class="form-group">
                    <label class="col-md-3 control-label">Category :-</label>
                    <div class="col-md-6">
                      <select name="cat_id" id="cat_id" class="select2" required>
                        <option value="">--Select Category--</option>
          							<?php
          									while($cat_row=mysqli_fetch_array($cat_result))
          									{
          							?>          						 
          							<option value="<?php echo $cat_row['cid'];?>"><?php echo $cat_row['category_name'];?></option>	          							 
          							<?php
          								}
          							?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Video Title :-</label>
                    <div class="col-md-6">
                      <input type="text" name="video_title" id="video_title" value="" class="form-control" required>
                    </div>
                  </div>
                    
                  <div class="form-group">
                    <label class="col-md-3 control-label">Video Upload Option :-</label>
                    <div class="col-md-6">                       
                      <select name="video_type" id="video_type" style="width:280px; height:25px;" class="select2" required>
                            <option value="">--Select Option--</option>                            
                            <option value="server_url">Server URL</option>
                            <option value="local">Browse From Computer</option>
                      </select>
                    </div>
                  </div>
                  <div id="video_url_display" class="form-group">
                    <label class="col-md-3 control-label">Video URL :-</label>
                    <div class="col-md-6">
                      <input type="text" name="video_url" id="video_url" value="" class="form-control">
                    </div>
                  </div>
                  <div id="video_local_display" class="form-group" style="display:none;">
                    <label class="col-md-3 control-label">Video Upload :-
                      <p class="control-label-help">(Recommended : Naximum 5MB and 20 Second)</p>
                    </label>
                    <div class="col-md-6">
                    
                    <input type="hidden" name="video_file_name" id="video_file_name" value="" class="form-control">
                      <input type="file" name="video_local" id="video_local" value="" class="form-control" onchange="return fileValidation()">

                      <div class="progress">
                            <div class="progress-bar progress-bar-success myprogress" role="progressbar" style="width:0%">0%</div>
                        </div>

                        <div class="msg"></div>
                        <input type="button" id="btn" class="btn-success" value="Upload" />
                    </div>
                  </div><br>

                  <div class="form-group">
                    <label class="col-md-3 control-label">Video Layout :-</label>
                    <div class="col-md-6">                       
                      <select name="video_layout" id="video_layout" style="width:280px; height:25px;" class="select2" required>
                            <option value="Landscape">Landscape</option>
                            <option value="Portrait">Portrait</option>
                      </select>
                    </div>
                  </div>

                  <div id="thumbnail" class="form-group">
                    <label class="col-md-3 control-label">Thumbnail Image:-
                      <p class="control-label-help">(Recommended resolution: Landscape: 800x500,650x450  Portrait: 720X1280, 640X1136, 350x800)</p>
                    </label>

                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="video_thumbnail" value="" id="fileupload">
                       <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">Send notification:-</label>
                    <div class="col-md-6" style="padding-top: 10px">                       
                      <!-- Material switch -->
                      <label class="switch">
                        <input type="checkbox" name="notify_user" checked="">
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                  <br/>
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
