<footer class="app-footer">
      <div class="row">
        <div class="col-xs-12">
          <div class="footer-copyright">Copyright © <?php echo date('Y');?> <a href="http://www.viaviweb.com" target="_blank">Viaviweb.com</a>. All Rights Reserved.</div>
        </div>
      </div>
    </footer>
  </div>
</div>

<div class="modal fade" id="verifyUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">User Verification</h4>
      </div>
      <form method="post" id="verifyUserForm">
	      <div class="modal-body">
	      </div>
	      <div class="modal-footer">
	        <button type="submit" name="btn_reject" value="reject" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reject</button>
	        <button type="submit" name="btn_approve" value="approve" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Approve</button>
	      </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript" src="assets/js/vendor.js"></script> 
<script type="text/javascript" src="assets/js/app.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="assets/sweetalert/sweetalert.min.js"></script>    

<script type="text/javascript">
	$(document).ready(function(e){

		var old_count = 0;
		var i = 0;

		$.ajax({
	      type:'post',
	      url:'processData.php',
	      dataType:'json',
	      data:{'action':'notify'},
	      success:function(data){
	          console.log(data.content[0]);
		      $(".notify_count").html(data.count);
		      $.each(data.content, function(index, item) {
		      	$(".dropdown-header").after(item);
		      });

		       $(".btn_verify").on("click",function(event){
					event.preventDefault();
					var _id=$(this).data("id");
					$("#verifyUser .modal-body").load("verification_page.php?id="+_id);
					$("#verifyUser").modal("show");
					$("li.dropdown-header").nextAll("li").remove();
					$.ajax({
				      type:'post',
				      url:'processData.php',
				      dataType:'json',
				      data:{'action':'openNotify',id:_id},
				      success:function(data){
				          console.log(data.content[0]);
					      $(".notify_count").html(data.count);
					      $.each(data.content, function(index, item) {
					      	$(".dropdown-header").after(item);
					      });
					    }
					});

				});

		    }
		});

		
		setInterval(function(){    
		$.ajax({
	      type:'post',
	      url:'processData.php',
	      dataType:'json',
	      data:{'action':'notify'},
	      success:function(data){
	          console.log(data.count);
	          	
		        if (data.count > old_count)
		        { 
		        	$("li.dropdown-header").nextAll("li").remove();
		        	if (i == 0)
		        	{
		        		old_count = data.count;
		        		$(".notify_count").html(old_count);
						$.each(data.content, function(index, item) {
							$("li.dropdown-header").after(item);
						});
		        	} 
		            else
		            {
		            	old_count = data.count;
		            	$(".notify_count").html(data.count);
						$.each(data.content, function(index, item) {
							$("li.dropdown-header").after(item);
						});
		            }
		        }
		        else{
		        	$("li.dropdown-header").nextAll("li").remove();
		        	old_count = data.count;
	            	$(".notify_count").html(data.count);
					$.each(data.content, function(index, item) {
						$("li.dropdown-header").after(item);
					});
		        } 

		        i=1;
		    }
		});
		},20000);
		

		$("#verifyUserForm button").click(function(e){
			e.preventDefault();
			var perform = $(this).val();

			if(perform=='approve'){
				if(confirm("Are you sure you want to "+perform+"?")){
					$("#verifyUserForm button[name='btn_approve']").attr("disabled", true);
					$("#verifyUserForm button[name='btn_reject']").attr("disabled", true);
					
					$.ajax({
				      type:'post',
				      url:'processData.php',
				      data : $("#verifyUserForm").serialize()+"&perform="+perform,
				      dataType:'json',
				      success:function(res){
				      	if(res.status=='1'){
			                location.reload();
			            }
				      }
				    });
				}
			}
			else if(perform=='reject'){
				$(".rejectReason").show();
				$("#verifyUserForm button[name='btn_approve']").attr("disabled", true);
				$("#verifyUserForm button[name='btn_reject']").attr("disabled", true);

			}

			

		});

	});
</script>

<script>
$("#checkall").click(function () {
	$('input:checkbox').not(this).prop('checked', this.checked);
});
</script> 
	
<script>
$(function() {
	$( ".datepicker" ).datepicker({
		dateFormat:'dd-mm-yy',
		showAnim:'clip',
		setDate: new Date(),
		minDate: 0
	});

	
});
</script>

</body>
</html>

