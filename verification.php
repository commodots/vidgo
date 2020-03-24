<?php 	
    include("includes/connection.php");
		include("includes/header.php");
		require("includes/function.php");
		require("language/language.php");
	 
	
    $qry="SELECT * FROM tbl_settings where id='1'";
    $result=mysqli_query($mysqli,$qry);
    $settings_row=mysqli_fetch_assoc($result);

    if(isset($_POST['verify_purchase_submit']))
    {

        $envato_buyer= verify_envato_purchase_code(trim($_POST['envato_purchase_code']));
        if($_POST['envato_buyer_name']!='' AND $envato_buyer->buyer==$_POST['envato_buyer_name'] AND $envato_buyer->item->id=='22983826')
        {
              $data = array
              (
                'envato_buyer_name' => $_POST['envato_buyer_name'],
                'envato_purchase_code' => $_POST['envato_purchase_code'],
                'envato_buyer_email' => $_POST['envato_buyer_email'],
                'envato_purchased_status' => 1,
                'package_name' => $_POST['package_name']
              );
        
              $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

              $config_file_default    = "includes/app.default";
              $config_file_name       = "api.php";  
              $config_file_path       = $config_file_name;

              $config_file = file_get_contents($config_file_default);

              $f = @fopen($config_file_path, "w+");
              
              if(@fwrite($f, $config_file) > 0){

                echo "done";

              }

              $protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 

              $admin_url = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

              verify_data_on_server($envato_buyer->item->id,$envato_buyer->buyer,$_POST['envato_purchase_code'],1,$admin_url,$_POST['package_name'],$_POST['envato_buyer_email']);

              $_SESSION['msg']="21";
              header( "Location:verification.php");
              exit;

        }
        else
        {
              $data = array
              (
                'envato_buyer_name' => $_POST['envato_buyer_name'],
                'envato_purchase_code' => $_POST['envato_purchase_code'],
                'envato_buyer_email' => $_POST['envato_buyer_email'],
                'envato_purchased_status' => 0,
                'package_name' => $_POST['package_name']
              );
        
              $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");

              $_SESSION['msg']="20";
              header( "Location:verification.php");
              exit;
        }
    }


?>
 
	 <div class="row">
      <div class="col-md-12">
        <div class="card">
		      <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Verify Purchase</div>
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
          
              <form action="" name="verify_purchase" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                <input type="hidden" class="current_tab" name="current_tab">
                <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-4 control-label">Envato Username :-
                      <p class="control-label-help" style="margin-bottom: 5px">https://codecanyon.net/user/<u style="color: #e91e63">viaviwebtech</u></p>
                      <p class="control-label-help">(<u style="color: #e91e63">viaviwebtech</u> is username)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="envato_buyer_name" id="envato_buyer_name" value="<?php echo $settings_row['envato_buyer_name'];?>" class="form-control" placeholder="viaviwebtech">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Envato Purchase Code :-

                      <p class="control-label-help">(<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where Is My Purchase Code?</a>)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="envato_purchase_code" id="envato_purchase_code" value="<?php echo $settings_row['envato_purchase_code'];?>" class="form-control" placeholder="xxxx-xxxx-xxxx-xxxx-xxxx">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Envato Buyer Email :-
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="envato_buyer_email" id="envato_buyer_email" value="<?php echo $settings_row['envato_buyer_email'];?>" class="form-control" placeholder="info@viaviweb.in">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Android Package Name :-
                      <p class="control-label-help">(More info in Android Doc)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="package_name" id="package_name" value="<?php echo $settings_row['package_name'];?>" class="form-control" placeholder="com.example.myapp">
                    </div>
                  </div>
                   
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-4">
                    <button type="submit" name="verify_purchase_submit" class="btn btn-primary">Save</button>
                  </div>
                  </div>
                </div>
                </div>

              </form>
              <br/>
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
              <h4 id="oh-snap!-you-got-an-error!">Note:<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
                  <p style="margin-bottom: 10px"><i class="fa fa-hand-o-right"></i> Buyer name and purchase code should match and package name same in android project otherwise application not work</p>    
                  <p><i class="fa fa-hand-o-right"></i> Please check PHP document for Mail Configuration. If your domain name: www.example.com then Create mail id info@examaple.com same like your domain name and add.</p>    
              </div>
          </div>
        </div>
      </div>
    </div>

        
<?php include("includes/footer.php");?> 
