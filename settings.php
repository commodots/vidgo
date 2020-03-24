<?php 	
    include("includes/connection.php");
		include("includes/header.php");
		require("includes/function.php");
		require("language/language.php");
	 
	
    $qry="SELECT * FROM tbl_settings where id='1'";
    $result=mysqli_query($mysqli,$qry);
    $settings_row=mysqli_fetch_assoc($result);

    if(isset($_POST['submit']))
    {

      $img_res=mysqli_query($mysqli,"SELECT * FROM tbl_settings WHERE id='1'");
      $img_row=mysqli_fetch_assoc($img_res);
      

           if($_FILES['app_logo']['name']!="")
           {        

              unlink('images/'.$img_row['app_logo']);   

              $app_logo=$_FILES['app_logo']['name'];
              $pic1=$_FILES['app_logo']['tmp_name'];

              $tpath1='images/'.$app_logo;      
              copy($pic1,$tpath1);


                $data = array(      
                'email_from'  =>  $_POST['email_from'],
                'app_name'  =>  $_POST['app_name'],
                'app_logo'  =>  $app_logo,  
                'app_description'  => addslashes($_POST['app_description']),
                'app_version'  =>  $_POST['app_version'],
                'app_author'  =>  $_POST['app_author'],
                'app_contact'  =>  $_POST['app_contact'],
                'app_email'  =>  $_POST['app_email'],   
                'app_website'  =>  $_POST['app_website'],
                'app_developed_by'  =>  $_POST['app_developed_by']                     

                );

      }
      else
      {

                  $data = array(
                  'email_from'  =>  $_POST['email_from'],
                  'app_name'  =>  $_POST['app_name'],
                  'app_description'  => addslashes($_POST['app_description']),
                  'app_version'  =>  $_POST['app_version'],
                  'app_author'  =>  $_POST['app_author'],
                  'app_contact'  =>  $_POST['app_contact'],
                  'app_email'  =>  $_POST['app_email'],   
                  'app_website'  =>  $_POST['app_website'],
                  'app_developed_by'  =>  $_POST['app_developed_by']               

                    );

      } 

      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");


          $_SESSION['msg']="11";
          header( "Location:settings.php");
          exit;

    }

    
    if(isset($_POST['payment_submit']))
    {

        $data = array
        (
          'payment_method1' => $_POST['payment_method1'],
          'payment_method2' => $_POST['payment_method2'],
          'payment_method3' => $_POST['payment_method3'],
          'payment_method4' => $_POST['payment_method4'],
        );

        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;

    }

    if(isset($_POST['watermark_submit']))
    {
        if($_FILES['watermark_image']['name']!="")
        {         

            $watermark_image=$_FILES['watermark_image']['name'];
            $pic1=$_FILES['watermark_image']['tmp_name'];

            $tpath1='images/'.$watermark_image;      
            copy($pic1,$tpath1);

            $data = array
            (                 
              'watermark_on_off' => $_POST['watermark_on_off'],
              'watermark_image' => $watermark_image
            );
        }
        else
        {
            $data = array('watermark_on_off' => $_POST['watermark_on_off']);
        }


        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");


        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;

    }

    if(isset($_POST['admob_submit']))
    {

        if($_POST['banner_ad'])
        {
            $banner_ad="true";
        }
        else
        {
            $banner_ad="false";
        }

        if($_POST['interstital_ad'])
        {
            $interstital_ad="true";
        }
        else
        {
            $interstital_ad="false";
        }

        if($_POST['rewarded_video_ads'])
        {
            $rewarded_video_ads="true";
        }
        else
        {
            $rewarded_video_ads="false";
        }



        $data = array(
              'publisher_id'  =>  $_POST['publisher_id'],
              'interstital_ad'  =>  $interstital_ad,
              'interstital_ad_id'  =>  $_POST['interstital_ad_id'],
              'interstital_ad_click'  =>  $_POST['interstital_ad_click'],
              'banner_ad'  =>  $banner_ad,
              'banner_ad_id'  =>  $_POST['banner_ad_id'],
              'rewarded_video_ads'  => $rewarded_video_ads,
              'rewarded_video_ads_id'  =>  $_POST['rewarded_video_ads_id'],
              'rewarded_video_click'  =>  $_POST['rewarded_video_click']
        );


        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;

    }

    


    if(isset($_POST['api_submit']))
    {

        $data = array
        (
          'api_page_limit'  =>  trim($_POST['api_page_limit']),
          'api_latest_limit'  =>  trim($_POST['api_latest_limit']),
          'api_cat_order_by'  =>  trim($_POST['api_cat_order_by']),
          'api_cat_post_order_by'  =>  trim($_POST['api_cat_post_order_by']),
          'api_all_order_by'  =>  trim($_POST['api_all_order_by'])
        );
      
        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;   

    }


    if(isset($_POST['app_faq_submit']))
    {

        $data = array('app_faq'  =>  addslashes($_POST['app_faq']));

        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
      

    }

    if(isset($_POST['app_pri_poly']))
    {

        $data = array('app_privacy_policy'  =>  addslashes($_POST['app_privacy_policy']));

        $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
      

    }


?>
 
	 <div class="row">
      <div class="col-md-12">
        <div class="card">
		      <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Settings</div>
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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab">App Settings</a></li>
                <li role="presentation"><a href="#admob_settings" aria-controls="admob_settings" role="tab" data-toggle="tab">Admob Settings</a></li> 
                <li role="presentation"><a href="#payment_settings" aria-controls="payment_settings" role="tab" data-toggle="tab">Payment Mode</a></li> 
                <li role="presentation"><a href="#watermark_settings" aria-controls="watermark_settings" role="tab" data-toggle="tab">Watermark </a></li>
                <li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API</a></li>
                <li role="presentation"><a href="#api_faq" aria-controls="api_faq" role="tab" data-toggle="tab">App FAQ</a></li>
                <li role="presentation"><a href="#api_privacy_policy" aria-controls="api_privacy_policy" role="tab" data-toggle="tab"> Privacy Policy</a></li>
                <!-- <li role="presentation"><a href="#suspend_account" aria-controls="suspend_account" role="tab" data-toggle="tab"> Suspend Account</a></li> -->
            </ul>
          
           <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="app_settings">	  
              <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
              
              <div class="section">
                <div class="section-body">
                  <div class="form-group" style="">
                    <label class="col-md-4 control-label">Host Email <span style="color: red">*</span>:-
                      <p class="control-label-help" style="color: red">(<strong>Note:</strong> This email required otherwise forgot password and OTP email feature will not be work. e.g.info@example.com)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="email_from" id="email_from" value="<?php echo $settings_row['email_from'];?>" class="form-control">
                    </div>
                  </div> 
                  <div class="form-group" style="">
                    <label class="col-md-4 control-label">Email <span style="color: red">*</span>:-
                      <p class="control-label-help" style="color: red">(<strong>Note:</strong> This email is required when user want to contact you.)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="app_email" id="app_email" value="<?php echo $settings_row['app_email'];?>" class="form-control">
                    </div>
                  </div>                   
                  <div class="form-group">
                    <label class="col-md-4 control-label">App Name :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">App Logo :-</label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="app_logo" id="fileupload">
                         
                        	<?php if($settings_row['app_logo']!="") {?>
                        	  <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['app_logo'];?>" alt="image" style="width: 100px;height: 100px;" /></div>
                        	<?php } else {?>
                        	  <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="image" /></div>
                        	<?php }?>
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">App Description :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_description" id="app_description" class="form-control"><?php echo $settings_row['app_description'];?></textarea>

                      <script>CKEDITOR.replace( 'app_description' );</script>
                    </div>
                  </div>
                  <div class="form-group">&nbsp;</div>                 


                  <div class="form-group">
                    <label class="col-md-4 control-label">App Version :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Author :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Contact :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact'];?>" class="form-control">
                    </div>
                  </div>     
                                  
                   <div class="form-group">
                    <label class="col-md-4 control-label">Website :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Developed By :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by'];?>" class="form-control">
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-4">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div>
              
              <div role="tabpanel" class="tab-pane" id="admob_settings">
                <form action="" name="admob_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
                 <div class="section">
                  <div class="section-body">
                    <div class="form-group">
                    <label class="col-md-2 control-label"><strong>Publisher ID</strong> <a href="#target-content5"></a>:-</label>
                    <div class="col-md-10">
                      <input type="text" name="publisher_id" id="publisher_id" value="<?php echo $settings_row['publisher_id'];?>" class="form-control">
                    </div>
                    </div>
                    <div id="target-content5">  
                      <div id="target-inner">
                      <a href="#publisher_id" class="close">X</a>
                      <img src="images/publisher_id.png" alt="publisher_id" />   
                      </div>
                    </div>
                    <hr/>
                    <div class="row">
                    <div class="form-group">
                      <div class="col-md-12">
                        <div class="col-md-4">
                        <div class="banner_ads_block">
                          <div class="banner_ad_item">
                            <label class="control-label">Banner Ads:-</label>
                            <div class="row toggle_btn">
                              <input type="checkbox" id="checked1" name="banner_ad" value="true" class="cbx hidden" <?php if($settings_row['banner_ad']=='true'){?>checked <?php }?> />
                              <label for="checked1" class="lbl"></label>
                            </div>
                          </div>              
                          <div class="col-md-12">             
                            <div class="form-group" id="#admob_banner_id">                              
                                <p class="field_lable">Banner Ad ID :-
                                <span>
                                <a id="button" href="#target-content1" class="lable_tooltip">(?)
                                  <span class="tooltip_text">Banner Ad ID</span>
                                </a>
                                <div id="target-content1">  
                                  <div id="target-inner">
                                  <a href="#admob_banner_id" class="close">X</a>
                                  <img src="images/admob_banner_id.png" alt="admob_banner_id" />   
                                  </div>
                                </div>
                                </span>
                              </p>
                              <div class="col-md-12">
                                <input type="text" name="banner_ad_id" id="banner_ad_id" value="<?php echo $settings_row['banner_ad_id'];?>" class="form-control">
                              </div>
                            </div>                            
                          </div>
                        </div>
                      </div>
            
                      <div class="col-md-4">
                        <div class="interstital_ads_block">
                          <div class="interstital_ad_item">
                            <label class="control-label">Interstitial Ads:-</label>
                            <div class="row toggle_btn">
                              <input type="checkbox" id="checked2" name="interstital_ad" value="true" class="cbx hidden" <?php if($settings_row['interstital_ad']=='true'){?>checked <?php }?>/>
                              <label for="checked2" class="lbl"></label>
                            </div>
                          </div>
                          <div class="col-md-12">             
                            <div class="form-group" id="interstital_ad_id">                              
                                <p class="field_lable">Interstitial Ad ID :-
                                <span>
                                <a id="button" href="#target-content2" class="lable_tooltip">(?)
                                  <span class="tooltip_text">Interstitial Ad ID</span>
                                </a>
                                <div id="target-content2">  
                                  <div id="target-inner">
                                  <a href="#interstital_ad_id" class="close">X</a>
                                  <img src="images/admob_banner_id.png" alt="admob_banner_id" />   
                                  </div>
                                </div>
                                </span>
                              </p>
                              <div class="col-md-12">
                                <input type="text" name="interstital_ad_id" id="interstital_ad_id" value="<?php echo $settings_row['interstital_ad_id'];?>" class="form-control">
                              </div>
                            </div>
                              <p class="field_lable">Interstitial Ad Clicks :-</p>
                              <div class="col-md-12"> 
                                  <input type="text" name="interstital_ad_click" id="interstital_ad_click" value="<?php echo $settings_row['interstital_ad_click'];?>" class="form-control ads_click">                                 
                              </div>
                          </div>
                        </div>
                      </div>
            
                       <div class="col-md-4">
                        <div class="banner_ads_block">
                          <div class="banner_ad_item">
                            <label class="control-label">Rewarded Video Ads:-</label>
                            <div class="row toggle_btn">
                              <input type="checkbox" id="checked3" name="rewarded_video_ads" value="true" class="cbx hidden" <?php if($settings_row['rewarded_video_ads']=='true'){?>checked <?php }?>/>
                              <label for="checked3" class="lbl"></label>
                            </div>
                          </div>
                          <div class="col-md-12">             
                            <div class="form-group" id="rewarded_video_ad_id">                              
                                <p class="field_lable">Rewarded Video Ad ID :-
                                <span>
                                <a id="button" href="#target-content3" class="lable_tooltip">(?)
                                  <span class="tooltip_text">Rewarded Video Ad ID</span>
                                </a>
                                <div id="target-content3">  
                                  <div id="target-inner">
                                  <a href="#rewarded_video_ad_id" class="close">X</a>
                                  <img src="images/admob_banner_id.png" alt="admob_banner_id" />   
                                  </div>
                                </div>
                                </span>
                              </p>
                              <div class="col-md-12">
                                <input type="text" name="rewarded_video_ads_id" id="rewarded_video_ads_id" value="<?php echo $settings_row['rewarded_video_ads_id'];?>" class="form-control">
                              </div>
                              <p class="field_lable">Rewarded Ad After Activity Clicks :-</p>
                              <div class="col-md-12">                                 
                                  <input type="text" name="rewarded_video_click" id="rewarded_video_click" value="<?php echo $settings_row['rewarded_video_click'];?>" class="form-control ads_click">                                 
                              </div>
                            </div>                            
                          </div>
                        </div>
                      </div> 
                      </div>
                    </div>
                    </div>                        
                    <div class="form-group">
                      <div class="col-md-9">
                      <button type="submit" name="admob_submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                    </div>
                  </div>
                  </form>
              </div>

               <div role="tabpanel" class="tab-pane" id="payment_settings">
              <form action="" name="payment_settings" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Payment Mode 1 :-</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method1" id="payment_method1" value="<?php echo $settings_row['payment_method1'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Payment Mode 2 :-</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method2" id="payment_method2" value="<?php echo $settings_row['payment_method2'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Payment Mode 3 :-</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method3" id="payment_method3" value="<?php echo $settings_row['payment_method3'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Payment Mode 4 :-</label>
                    <div class="col-md-6">
                      <input type="text" name="payment_method4" id="payment_method4" value="<?php echo $settings_row['payment_method4'];?>" class="form-control">
                    </div>
                  </div>
                  <br/>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-6">
                       <b>Note:</b> 
                    </div>
                  </div>
                        
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="payment_submit" class="btn btn-primary">Save</button>
                  </div>
                  </div>
                  <br/>
                  <div class="alert alert-danger alert-dismissible fade in" role="alert">
                  <h4 id="oh-snap!-you-got-an-error!">Note:<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
                      <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> Buyer name and If any payment mode empty means inactive and not display in app side</p> 
                  </div>
                </div>
                </div>
              </form>
            </div> 
            <div role="tabpanel" class="tab-pane" id="watermark_settings">
              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Watermark:-</label>
                    <div class="col-md-6">
                        <select name="watermark_on_off" id="watermark_on_off" class="select2">
                          <option value="true" <?php if($settings_row['watermark_on_off']=='true'){?>selected<?php }?>>ON</option>
                          <option value="false" <?php if($settings_row['watermark_on_off']=='false'){?>selected<?php }?>>OFF</option>              
                        </select>                        
                    </div>                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Watermark Image :-
                      <p class="control-label-help">(Recommended resolution: 100x100, 80x80)</p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="watermark_image" id="fileupload">
                         
                          <?php if($settings_row['watermark_image']!="") {?>
                            <div class="fileupload_img"><img type="image" src="images/<?php echo $settings_row['watermark_image'];?>" alt="image" style="width: 100px;height: 100px;" /></div>
                          <?php } else {?>
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="image" /></div>
                          <?php }?>
                        
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="watermark_submit" class="btn btn-primary">Save</button>
                  </div>
                  </div>
                </div>
                </div>
              </form>
            </div> 

                        
              <div role="tabpanel" class="tab-pane" id="api_settings">   
                <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data">
                
              <div class="section">
                <div class="section-body">
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Pagination Limit:-</label>
                    <div class="col-md-6">
                       
                      <input type="number" name="api_page_limit" id="api_page_limit" value="<?php echo $settings_row['api_page_limit'];?>" class="form-control"> 
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Latest Limit:-</label>
                    <div class="col-md-6">
                       
                      <input type="number" name="api_latest_limit" id="api_latest_limit" value="<?php echo $settings_row['api_latest_limit'];?>" class="form-control"> 
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category List Order By:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                          <option value="cid" <?php if($settings_row['api_cat_order_by']=='cid'){?>selected<?php }?>>ID</option>
                          <option value="category_name" <?php if($settings_row['api_cat_order_by']=='category_name'){?>selected<?php }?>>Name</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category Video Order:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_cat_post_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_cat_post_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">All Video Order:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_all_order_by" id="api_all_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_all_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_all_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  
                
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="api_submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 
              <div role="tabpanel" class="tab-pane" id="api_faq">   
              <form action="" name="api_faq" method="post" class="form form-horizontal" enctype="multipart/form-data">
                
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">App FAQ :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_faq" id="app_faq" class="form-control"><?php echo stripslashes($settings_row['app_faq']);?></textarea>

                      <script>CKEDITOR.replace( 'app_faq' );</script>
                    </div>
                  </div>
                  
                  <br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="app_faq_submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 
              <div role="tabpanel" class="tab-pane" id="api_privacy_policy">   
                <form action="" name="api_privacy_policy" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Privacy Policy :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_privacy_policy" id="privacy_policy" class="form-control"><?php echo stripslashes($settings_row['app_privacy_policy']);?></textarea>

                      <script>CKEDITOR.replace( 'privacy_policy' );</script>
                    </div>
                  </div>
                  
                  <br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="app_pri_poly" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 


              <!-- for suspend_account tab -->
              <!-- <div role="tabpanel" class="tab-pane" id="suspend_account">   
                <form action="" name="suspend_account" method="post" class="form form-horizontal" enctype="multipart/form-data">

                  <div class="section">
                    <div class="section-body">
                      <div class="form-group">
                        <label class="col-md-3 control-label">Nos of days for susspend :-</label>
                        <div class="col-md-6">
                          <input type="text" name="payment_method1" id="payment_method1" value="<?php echo $settings_row['payment_method1'];?>" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div> -->
              <!-- End -->

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