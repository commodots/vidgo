<?php include("includes/header.php");

	require("includes/function.php");
	require("language/language.php");
	 
	
	$qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);


	if(isset($_POST['rewards_points_submit']))
	{
         

        if($_POST['video_views_status'])
        {
            $video_views_status="true";
        }
        else
        {
            $video_views_status="false";
        }

        if($_POST['video_add_status'])
        {
            $video_add_status="true";
        }
        else
        {
            $video_add_status="false";
        }

        if($_POST['like_video_points_status'])
        {
            $like_video_points_status="true";
        }
        else
        {
            $like_video_points_status="false";
        }

        if($_POST['download_video_points_status'])
        {
            $download_video_points_status="true";
        }
        else
        {
            $download_video_points_status="false";
        }

        if($_POST['other_user_video_status'])
        {
            $other_user_video_status="true";
        }
        else
        {
            $other_user_video_status="false";
        }


		    $data = array(
          'redeem_points'  =>  $_POST['redeem_points'],
          'redeem_money'  =>  $_POST['redeem_money'],
          'redeem_currency'  =>  $_POST['redeem_currency'],
          'minimum_redeem_points'  =>  $_POST['minimum_redeem_points'],                

          'registration_reward'  =>  $_POST['registration_reward'],
          'app_refer_reward'  =>  $_POST['app_refer_reward'],
          'video_views'  =>  $_POST['video_views'],
          'video_add'  =>  $_POST['video_add'],
          'like_video_points'  =>  $_POST['like_video_points'],
          'download_video_points'  =>  $_POST['download_video_points'],
         
          'video_views_status'  =>  $video_views_status,
          'video_add_status'  =>  $video_add_status,
          'like_video_points_status'  =>  $like_video_points_status,
          'download_video_points_status'  =>  $download_video_points_status,
          'other_user_video_status'  =>  $other_user_video_status,
          'other_user_video_point'  =>  $_POST['other_user_video_point']
        );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
   
      $_SESSION['msg']="11";
      header( "Location:rewards_points.php");
      exit;
		 
	}


?>
 
	 <div class="row">
    <div class="col-md-12">
      <div class="card">
    <div class="card-body">
       
    </div>
        <div class="clearfix"></div>

        <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
                 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?> 
              </div>
        <div class="card-body pt_top">
              
      <div class="rewards_point_page_title">
        <div class="col-md-12 col-xs-12">
          <div class="page_title" style="font-size: 20px;color: #424242;">
            <h3>Rewards System</h3>
          </div>
        </div>              
      </div>      
          <form action="" name="admob_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
            <div class="col-md-12">
              <div class="form-group reward_point_block">
                <div class="col-md-12">
                  <div class="col-md-6 col-sm-8">
                    <div class="form-group">
                      <div class="col-md-7 col-sm-5 points_block mrg_right">
                          <div class="col-md-5">
                            <label class="control-label">Points</label>
                            <input type="text" name="redeem_points" id="redeem_points" value="<?php echo $settings_row['redeem_points'];?>" class="form-control">
                          </div>
                          <div class="col-md-2">
                            <label class="col-md-2 control-label point_count">=</label>
                          </div>
                          <div class="col-md-5">
                            <label class="control-label">Amount</label>
                            <input type="text" name="redeem_money" id="redeem_money" value="<?php echo $settings_row['redeem_money'];?>" class="form-control">
                          </div>  
                      </div>                      
            <div class="col-md-4 col-sm-6 points_block points_amount">
            <label class="control-label">Currency Code</label>
                        <input type="text" name="redeem_currency" id="redeem_currency" value="<?php echo $settings_row['redeem_currency'];?>" class="form-control">
                      </div>                    
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-4 redeem_point_section">                    
                    <div class="col-md-12 points_block minimum_redeem_point">
            <label class="control-label">Minimum Redeem Points</label>  
                      <input type="text" name="minimum_redeem_points" id="minimum_redeem_points" value="<?php echo $settings_row['minimum_redeem_points'];?>" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mrg-top manage_user_btn manage_rewards_point_block">
         
        <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
            <th style="width:300px">Activity Name</th>
            <th style="width:50px">Points</th>
            <th style="width:50px">Enable/Disable</th>             
          </tr>
          </thead>
          <tbody>
          <tr>            
            <td>App Registration Points:-</td>
            <td><input type="text" name="registration_reward" id="registration_reward" value="<?php echo $settings_row['registration_reward'];?>" class="form-control"></td>
             <td>
              &nbsp;
            </td>
          </tr>
          <tr>            
            <td>App Refer Points:-</td>
            <td><input type="text" name="app_refer_reward" id="app_refer_reward" value="<?php echo $settings_row['app_refer_reward'];?>" class="form-control"></td>
             <td>
              &nbsp;
            </td> 
          </tr>

          <tr>            
            <td>Video Add Points:-</td>
            <td><input type="text" name="video_add" id="video_add" value="<?php echo $settings_row['video_add'];?>" class="form-control"></td>
             <td>
              <div class="row toggle_btn">
                <p style="float: right;">&nbsp;&nbsp;Rewarded Ads Status</p>
              <input type="checkbox" id="checked05" class="cbx hidden" name="video_add_status" value="true" <?php if($settings_row['video_add_status']=='true'){?>checked <?php }?>/>
              <label for="checked05" class="lbl" style="float: left"></label>
            </div>
            </td>
          </tr>

          <tr>            
            <td>Video View Points :-</td>
            <td><input type="text" name="video_views" id="video_views" value="<?php echo $settings_row['video_views'];?>" class="form-control"></td>
             <td>
              <div class="row toggle_btn">
                <p style="float: right;">&nbsp;&nbsp;Rewarded Ads Status</p>
              <input type="checkbox" id="checked04" class="cbx hidden" name="video_views_status" value="true" <?php if($settings_row['video_views_status']=='true'){?>checked <?php }?>/>
              <label for="checked04" class="lbl" style="float: left"></label>
            </div>
            </td>
          </tr>

          <tr>            
            <td>Video Points Viewed By Others:-</td>
            <td><input type="text" name="other_user_video_point" id="other_user_video_point" value="<?php echo $settings_row['other_user_video_point'];?>" class="form-control"></td>
             <td>
              <div class="row toggle_btn">
                <p style="float: right;">&nbsp;&nbsp;Enable/Disable</p>
              <input type="checkbox" id="checked09" class="cbx hidden" name="other_user_video_status" value="true" <?php if($settings_row['other_user_video_status']=='true'){?>checked <?php }?>/>
              <label for="checked09" class="lbl" style="float: left"></label>
            </div>
            </td>
          </tr>

          

          <tr>            
            <td>Video Like Points:-</td>
            <td><input type="text" name="like_video_points" id="like_video_points" value="<?php echo $settings_row['like_video_points'];?>" class="form-control"></td>
             <td>
              <div class="row toggle_btn">
                <p style="float: right;">&nbsp;&nbsp;Rewarded Ads Status</p>
              <input type="checkbox" id="checked06" class="cbx hidden" name="like_video_points_status" value="true" <?php if($settings_row['like_video_points_status']=='true'){?>checked <?php }?>/>
              <label for="checked06" class="lbl" style="float: left"></label>
            </div>
            </td>
          </tr>

          <tr>            
            <td>Video Download Points:-</td>
            <td><input type="text" name="download_video_points" id="download_video_points" value="<?php echo $settings_row['download_video_points'];?>" class="form-control"></td>
             <td>
              <div class="row toggle_btn">
                <p style="float: right;">&nbsp;&nbsp;Rewarded Ads Status</p>
              <input type="checkbox" id="checked07" class="cbx hidden" name="download_video_points_status" value="true" <?php if($settings_row['download_video_points_status']=='true'){?>checked <?php }?>/>
              <label for="checked07" class="lbl" style="float: left"></label>
            </div>
            </td>
          </tr>
                 
          </tbody>
        </table>
       
      </div>
          <div align="center" class="form-group">
              <div class="col-md-12">
                <button type="submit" name="rewards_points_submit" class="btn btn-primary ">Save</button>
              </div>
            </div>
      </form>

        <b>Note:</b>   <p style="color: red;">If you want to show rewards video ads on listed activity you have to ENABLE <strong>Rewarded Ads Status</strong>.</p>

        <b>Note for Video Points Viewed By Others</b>   
        <p style="color: red;padding-left: 20px">
          <strong>On -</strong> Video owner gets points when other user views video
          <br/>
          <strong>Off -</strong> Video owner will not get points when other user views video
        </p>

        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    
  </div>

        
<?php include("includes/footer.php");?>    

<script type="text/javascript">
  $("#other_user_video_point").keyup(function(e){
    if($(this).val()==''){
      $(this).val('0');
    }
  });
</script>   
