<?php 
  $page_title="Manage Transactions";
  include('includes/header.php'); 
  include('includes/function.php');
  include('language/language.php'); 

  // error_reporting(E_ALL);

  $qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);


  $qry_users_paid="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.status = 1";
  $total_paid = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_paid));
  $total_paid = $total_paid['num'];


  $qry_users_pending="SELECT SUM(redeem_price) AS num FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.status = 0";
  $total_pending = mysqli_fetch_array(mysqli_query($mysqli,$qry_users_pending));
  $total_pending = $total_pending['num'];

  if(isset($_POST['payment_status']))
   {
     
    if($_POST['payment_status']==2)
    {
      $user_qry="SELECT tbl_users_redeem.*,tbl_users.name,tbl_users.email FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  ORDER BY tbl_users_redeem.redeem_price DESC";  
    }
    else
    {
      $user_qry="SELECT tbl_users_redeem.*,tbl_users.name,tbl_users.email FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users_redeem.status = '".$_POST['payment_status']."' ORDER BY tbl_users.id DESC";  
    }    
               
      $users_result=mysqli_query($mysqli,$user_qry);
    
     
   }
   else if(isset($_POST['user_search']))
   {
     
    
    $user_qry="SELECT tbl_users_redeem.*,tbl_users.name,tbl_users.email FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id
                  WHERE tbl_users.name like '%".addslashes($_POST['search_value'])."%' or tbl_users.email like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_users.id DESC";  
               
    $users_result=mysqli_query($mysqli,$user_qry);
    
     
   }
   else
   {
   
      $tableName="tbl_users_redeem";    
      $targetpage = "manage_transaction.php";   
      $limit = 15; 
      
      $query = "SELECT COUNT(*) as num FROM $tableName LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id";
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
      
      
     $users_qry="SELECT tbl_users_redeem.*,tbl_users.name,tbl_users.email FROM tbl_users_redeem
                  LEFT JOIN tbl_users ON tbl_users_redeem.user_id= tbl_users.id 
                  ORDER BY tbl_users_redeem.id DESC LIMIT $start, $limit";
       
      $users_result=mysqli_query($mysqli,$users_qry);
              
   }
   
  //Active and Deactive status
  if(isset($_POST['pending_submit']))
  {

    if($_POST['user_id']!="")
    { 
      if($_POST['payment_msg'])
      {
          $content = array(
                         "en" => $_POST['payment_msg']
                          );
      }  
      else
      {
        $content = array(
                         "en" => "Payment status pending"
                          );
      }

      

      $fields = array(
              'app_id' => ONESIGNAL_APP_ID,
              'included_segments' => array('Subscribed Users'),                                            
              'data' => array("foo" => "bar","payment_withdraw" =>"true"),
              'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $_POST['user_id'])),
              'headings'=> array("en" => APP_NAME),
              'contents' => $content 
              );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        
        curl_close($ch); 

    }


    $data = array('status'  =>  '0');    
    $edit_status=Update('tbl_users_redeem', $data, "WHERE id = '".$_POST['transaction_id']."'");
    
     $_SESSION['msg']="18";
     header( "Location:manage_transaction.php");
     exit;
  }
  if(isset($_POST['paid_submit']))
  {

    if($_POST['user_id']!="")
    { 

      if($_POST['payment_msg'])
      {
          $content = array("en" => $_POST['payment_msg']);
      }  
      else
      {
        $content = array("en" => "Payment has been sent");
      }

      if($_FILES['payment_receipt']['name']!="")
      {   

        $ext = pathinfo($_FILES['payment_receipt']['name'], PATHINFO_EXTENSION);

        $path = "images/payment_receipt/"; //set your folder path
        $payment_receipt=date('dmYhis').'_'.rand(0,99999).".".$ext;
       
        $tpath1='images/payment_receipt/'.$payment_receipt;        
        if($ext!='png'){
          $pic1=compress_image($_FILES["payment_receipt"]["tmp_name"], $tpath1, 80);
        }else{
          move_uploaded_file($_FILES['payment_receipt']['tmp_name'], $tpath1);
        }

        $protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http';

        $file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/images/payment_receipt/'.$payment_receipt;

        $fields = array(
                'app_id' => ONESIGNAL_APP_ID,
                'included_segments' => array('Subscribed Users'),                                            
                'data' => array("foo" => "bar","payment_withdraw" =>"true"),
                'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $_POST['user_id'])),
                'headings'=> array("en" => APP_NAME),
                'contents' => $content,
                'big_picture' =>$file_path                    
                );

      }
      else{
          $fields = array(
              'app_id' => ONESIGNAL_APP_ID,
              'included_segments' => array('Subscribed Users'),                                            
              'data' => array("foo" => "bar","payment_withdraw" =>"true"),
              'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $_POST['user_id'])),
              'headings'=> array("en" => APP_NAME),
              'contents' => $content 
              );
      }

      

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

    }


    $data = array(
      'cust_message'  =>  trim($_POST['payment_msg']),
      'receipt_img'  =>  $payment_receipt,
      'responce_date'  =>  date('Y-m-d h:i:s A'),
      'status'  =>  '1'
    );
    
    $edit_status=Update('tbl_users_redeem', $data, "WHERE id = '".$_POST['transaction_id']."'");
    
    $_SESSION['msg']="17";
    header( "Location:manage_transaction.php");
    exit;
  }

  if(isset($_POST['reject_submit']))
  {
    if($_POST['user_id']!="")
    { 
      if($_POST['payment_msg'])
      {
          $content = array(
                         "en" => $_POST['payment_msg']
                          );
      }  
      else
      {
          $content = array(
                         "en" => "Payment has been reject"
                          );
      }
      

      $fields = array(
              'app_id' => ONESIGNAL_APP_ID,
              'included_segments' => array('Subscribed Users'),                                            
              'data' => array("foo" => "bar","payment_withdraw" =>"true"),
              'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $_POST['user_id'])),
              'headings'=> array("en" => APP_NAME),
              'contents' => $content 
              );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);       
        
        
        curl_close($ch); 

    }


    $data = array(
      'cust_message'  =>  trim($_POST['payment_msg']),
      'responce_date'  =>  date('Y-m-d h:i:s A'),
      'status'  =>  '2'
    );
    
    $edit_status=Update('tbl_users_redeem', $data, "WHERE id = '".$_POST['transaction_id']."'");
    
    $_SESSION['msg']="19";
     header( "Location:manage_transaction.php");
     exit;
  }
  
  if(isset($_GET['trans_id']))
  {
       
    Delete('tbl_users_redeem','id='.$_GET['trans_id'].'');
    
    $_SESSION['msg']="12";
    header( "Location:manage_transaction.php");
    exit;
  }


  if(isset($_POST['delete_rec']))
  {

    $checkbox = $_POST['post_ids'];
    
    for($i=0;$i<count($checkbox);$i++){
      
      $del_id = $checkbox[$i]; 
     
      Delete('tbl_users_redeem','id='.$del_id.'');
 
    }

    $_SESSION['msg']="12";
    header( "Location:manage_transaction.php");
    exit;
  }

?>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
  
$(function() {
    $('#payment_status').change(function() {
        this.form.submit();
    });
});

</script>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Manage Transaction</div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="" enctype="multipart/form-data">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                     
                  </div>
                  <form method="POST" name="myform" action="" enctype="multipart/form-data">
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp;</label>
                    <div class="col-md-6">
                      <select name="payment_status" id="payment_status" class="select2" required>
                        <option value="">--Filter--</option>
                        <option value="0" <?php if(isset($_POST['payment_status']) AND $_POST['payment_status']==0){?>selected<?php }?>>Pending</option>
                        <option value="1" <?php if(isset($_POST['payment_status']) AND $_POST['payment_status']==1){?>selected<?php }?>>Paid</option>

                        <option value="2" <?php if(isset($_POST['payment_status']) AND $_POST['payment_status']==2){?>selected<?php }?>>Most Earned</option>

                          
                      </select>
                    </div>
                  </div>
                  </form>                 
            </div>
			
			<div class="col-md-12 mrg_bottom">
				<span class="badge badge-success badge-icon"><i class="fa fa-check fa-2x" aria-hidden="true"></i><span style="font-size: 18px;"><?php echo $total_paid ? $total_paid : '0';?> <?php echo $settings_row['redeem_currency'];?> Paid</span></span>
                <span class="badge badge-danger badge-icon"><i class="fa fa-clock-o fa-2x" aria-hidden="true"></i><span style="font-size: 18px;"> <?php echo $total_pending ? $total_pending : '0';?> <?php echo $settings_row['redeem_currency'];?> Pending</span></span>
			</div>

          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
                 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?> 
              </div>
            </div>
          </div>
          <div class="col-md-12 mrg-top manage_transaction_btn">

           <form method="post" action="" enctype="multipart/form-data">

            <button type="submit" class="btn btn-danger btn_delete" style="margin-bottom:20px;" name="delete_rec" value="delete_post" onclick="return confirm('Are you sure you want to delete this transaction ?');"><i class="fa fa-trash"></i> Delete All</button>

            <table class="table table-striped table-bordered table-hover manage_transaction_table">
              <thead>
                <tr>
                  <th>
                    <div class="checkbox">
                    <input type="checkbox" name="checkall" id="checkall" value="">
                    <label for="checkall"></label>
                    </div>
					All
                  </th> 
                  <th style="width:120px;">Name</th>            
                  <th style="width:220px;">Email</th>                  
                  <th style="width:100px;">Payment Details</th>
                  <th>Points</th>
                  <th style="width:120px;">Amount Pay</th>
				          <th style="width:100px;">Date</th>
                  <th style="width:100px;">Current Status</th>
                  <th style="width:60px;">Action</th>  
                 </tr>
              </thead>
              <tbody>
                <?php
            $i=0;
            while($users_row=mysqli_fetch_array($users_result))
            {
             
        ?>
                <tr>
               <td> 
                 
                  <div>
                  <div class="checkbox">
                    <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['id']; ?>">
                    <label for="checkbox<?php echo $i;?>">
                    </label>
                  </div>
                  
                </div>
               </td>       
               <td><?php echo $users_row['name'];?></td>
               <td><?php echo $users_row['email'];?></td>
               <td align="center">
                <a href="javascript::();" class="btn btn-success" data-toggle="modal" data-target="#paymentdetailsModal<?php echo $users_row['id'];?>" data-toggle="tooltip" data-tooltip="Payment Details"><i class="fa fa-eye"></i></a></td>   

               <div class="modal fade" id="paymentdetailsModal<?php echo $users_row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Payment details of <?php echo $users_row['name'];?></h4>
                    </div>         
                            
                    <div class="modal-body">
                      Payment Mode : <?php echo $users_row['payment_mode'];?> <br/>
                      Payment Details : <?php echo $users_row['bank_details'];?> 
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                      
                    </div>
                  </div>
                </div>
              </div>

              <td><?php echo $users_row['user_points'];?></td>
              <td align="center" nowrap="">
                <?php echo $users_row['redeem_price'];?> <?php echo $settings_row['redeem_currency'];?> 
               </td>
              <td><?php echo date('m-d-Y',strtotime($users_row['request_date']));?></td>
              <td>
                  
                  <div class="btn-group">
                        <button type="button" class="btn <?php if($users_row['status']=="1"){?>btn-success<?php }else if($users_row['status']=="0"){?> btn-warning <?php }else{?>btn-danger<?php }?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php if($users_row['status']=="1"){?>Paid<?php }else if($users_row['status']=="0"){?> Pending <?php }else{?>Reject<?php }?> <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript::();" data-toggle="modal" data-target="#paidModal<?php echo $users_row['id'];?>">Paid</a></li>
                            <li><a href="javascript::();" data-toggle="modal" data-target="#pendingModal<?php echo $users_row['id'];?>">Pending</a></li>                     
                            <li><a href="javascript::();" class="btn_reject" data-target="#rejectModal<?php echo $users_row['id'];?>">Reject</a>  </li>                          
                            </ul>
                  </div>

                  <div class="modal fade" id="paidModal<?php echo $users_row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Send payment reply to <?php echo $users_row['name'];?></h4>
                        </div>                        
                        <form action="" name="paid_comments" method="post" class="" enctype="multipart/form-data">
                        <input type="hidden" name="transaction_id" value="<?php echo $users_row['id']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $users_row['user_id']; ?>">                        
                        <div class="modal-body">
                          <div class="form-group">
                            <label>Custom Message</label>
                            <textarea name="payment_msg" id="payment_msg" class="form-control" placeholder="Set msg"></textarea>
                          </div>
                          <div class="form-group">
                            <label>Upload Payment Receipt</label>
                            <input type="file" name="payment_receipt" class="form-control">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" name="paid_submit" class="btn btn-sm btn-success">Send</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>


                  <div class="modal fade" id="pendingModal<?php echo $users_row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Payment reply to <?php echo $users_row['name'];?></h4>
                        </div>                        
                        <form action="" name="pending_comments" method="post" class="" enctype="multipart/form-data">
                        <input type="hidden" name="transaction_id" value="<?php echo $users_row['id']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $users_row['user_id']; ?>">                        
                        <div class="modal-body">
                          <textarea name="payment_msg" id="payment_msg" class="form-control" placeholder="Set msg"></textarea> 
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" name="pending_submit" class="btn btn-sm btn-success">Send Reply</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>


                  <div class="modal fade" id="rejectModal<?php echo $users_row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Reject payment reply to <?php echo $users_row['name'];?></h4>
                        </div>                        
                        <form action="" name="reject_comments" method="post" class="" enctype="multipart/form-data">
                        <input type="hidden" name="transaction_id" value="<?php echo $users_row['id']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $users_row['user_id']; ?>">                        
                        <div class="modal-body">
                          <textarea name="payment_msg" id="payment_msg" class="form-control" placeholder="Set msg"></textarea> 
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" name="reject_submit" class="btn btn-sm btn-success">Reject</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>


               </td>
                  <td nowrap=""> 
                    <a href="manage_user_history_total_points.php?user_id=<?php echo $users_row['user_id'];?>" class="btn btn-success" data-toggle="tooltip" data-tooltip="User History"><i class="fa fa-history"></i></a>
                    <a href="manage_transaction.php?trans_id=<?php echo $users_row['id'];?>" onclick="return confirm('Are you sure you want to delete this transaction ?');" class="btn btn-danger"data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></td>  
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
                <?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
              </nav>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>     



<?php include('includes/footer.php');?>                  

<script type="text/javascript">
  $(".btn_reject").click(function(e){
    if(confirm('Are you sure you want to reject this transaction ?')){
      var _target=$(this).data("target");
      $(_target).modal("show");
    }
  });
</script>