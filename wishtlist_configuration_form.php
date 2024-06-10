<?php
session_start();
include 'includes/dbcon.php';
include 'includes/encrypt_func.php';
if ( $_SESSION[ 'id' ] ) {
  $id = $_SESSION[ 'id' ];
  $login_sql = mysqli_query( $conn, "select * from qr_users WHERE id=$id and status='1'" );
  $login_access = mysqli_fetch_array( $login_sql );
	if(isset($_REQUEST['edit'])){
		$encrypted_id1 = $_REQUEST['edit'];
		$key = "16_byte_secret_key";
		$cl_id = decrypt_id($encrypted_id1, $key);
		$evrole_sql = mysqli_query( $conn, "select * from cm_wislist WHERE id='$cl_id'" );
		$listrole_sql = mysqli_fetch_array( $evrole_sql );
		if($cl_id){$cl_id=decrypt_id($encrypted_id1, $key);}else{$cl_id='0';}
		if(isset($listrole_sql['id'])){$listId = $listrole_sql['id'];}else{$listId='';}
		$btn_sub = "roleupdate";
		$btn_txt = "Update";
	}
	else{
		$btn_sub = "rolesubmit";
		$btn_txt = "Save";
		$cl_id='1';
		$listId='1';
	}
	
} else {
  echo '<script type="text/javascript">
           window.location = "' . $domain_url . 'index.php"
     </script>';
  unset( $_SESSION[ 'id' ] );
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php include 'includes/head.php';?>


<body>
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
  <?php include('includes/header.php');?>
  <?php 
		include('logout-modal.php'); 
		include('includes/sidebar.php');
		?>
  
  <div class="page-wrapper">
    <div class="container-fluid"> 
      
      <!-- QR SCANNER ---> 
      <!---- <div class="col-lg-5 col-md-12" style="padding: 0px;margin: 0px;">
                       <iframe src="qr-scanner.php" height="620px" width="100%" title="QR Scanner" style="border:none;padding: 0px;margin: 0px;"scrolling="no"></iframe>
                    </div>
                </div> --> 
      
      <!-- multi-column ordering -->
      <div class="row">
		<?php if($cl_id == $listId){?>    
        <div class="col-4">
          <div class="card">
            <div class="card-body">
            <h4 class="card-title">Add Commission calculation</h4>
            <!-- <small class="form-text text-muted mb-3">*This table displays all scanned attendees with <b>VALID</b> QR codes</small><br>-->
            
            <form method="post" action="wishtlist_configuration.php" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12 mt-4" style="margin-bottom: 20px;">
                  <small id="label" class="form-text text-muted mb-2">Level</small>
					<select name="label" class="form-control" required>
						<option value="">Select Level</option>
						<option value="1" <?php if(isset($_REQUEST['edit'])){ if($listrole_sql['level']=='1'){ echo "selected";}} ?>>1</option>
						<option value="2" <?php if(isset($_REQUEST['edit'])){if($listrole_sql['level']=='2'){ echo "selected";}} ?>>2</option>
						<option value="3" <?php if(isset($_REQUEST['edit'])){if($listrole_sql['level']=='3'){ echo "selected";}} ?>>3</option>
						<option value="4" <?php if(isset($_REQUEST['edit'])){if($listrole_sql['level']=='4'){ echo "selected";}} ?>>4</option>
					</select>
                  </div>
				  
				 <div class="col-md-12 mt-3">
                  <small id="wishlist_n" class="form-text text-muted  mb-2">Wishlist*</small>
					<select name="wishlist" class="form-control" required>
						<option value="">Select Wislist</option>
						<option value="yes" <?php if(isset($_REQUEST['edit'])){if($listrole_sql['wishlist']=='yes'){ echo "selected";}} ?>>Yes</option>
						<option value="no" <?php if(isset($_REQUEST['edit'])){if($listrole_sql['wishlist']=='no'){ echo "selected";}} ?>>No</option>
						<option value="past" <?php if(isset($_REQUEST['edit'])){if($listrole_sql['wishlist']=='past'){ echo "selected";}} ?>>Past</option>
					 </select>
                  </div>
				  
				  <div class="col-md-12 mt-3">
                  <small id="wishlist_n" class="form-text text-muted  mb-2">Commission*</small>
					<input type="text" class="form-control" id="wishlist_n" name="commission" aria-describedby="From Wishlist (N)" value="<?php if(isset($_REQUEST['edit'])){ echo $listrole_sql['commission'];} ?>">
                  </div>
				  
				  
				  
				  
				  
				  
				  
				 
				  
				  
              </div>
              </div>
              <div class="modal-footer">
				  <input type="hidden" name="cl_id" value="<?php if(isset($_REQUEST['edit'])){ echo $listrole_sql['id'];}?>">
				  <a href="wishtlist_configuration.php" type="button" class="btn btn-light">Back</a>
                <button type="submit" name="<?php echo $btn_sub;?>" id="submit" class="btn btn-primary"><?php echo $btn_txt;?></button>
              </div>
            </form>
          </div>
        </div>
		   <?php } else{?>
			<div style="margin: auto; margin-top: 7%; text-align: center; width: 100%;">
			<h4 style="font-size: 5em; font-weight: bold; color: #333;">404</h4>
			<p style="font-size: 2em; color: #333;">Oops... Page not found.</p>
			<p>We don't know how you ended up here, but you should go away now.</p>
				</div>
			<?php }?>
		  
      </div>
    </div>
  </div>
  <?php include 'includes/footer.php';?>
</div>
</div>
<script src="assets/libs/jquery/dist/jquery.min.js"></script> 
<script src="assets/libs/popper.js/dist/umd/popper.min.js"></script> 
<script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> 
<script src="dist/js/app-style-switcher.js"></script> 
<script src="dist/js/feather.min.js"></script> 
<script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script> 
<script src="dist/js/sidebarmenu.js"></script> 
<!--Custom JavaScript --> 
<script src="dist/js/custom.min.js"></script> 

<!--Tables --> 
<script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script> 
<script src="dist/js/pages/datatable/datatable-basic.init.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

<script>
(function(){

    "use strict"

    
    // Plugin Constructor
    var TagsInput = function(opts){
        this.options = Object.assign(TagsInput.defaults , opts);
        this.init();
    }

    // Initialize the plugin
    TagsInput.prototype.init = function(opts){
        this.options = opts ? Object.assign(this.options, opts) : this.options;

        if(this.initialized)
            this.destroy();
            
        if(!(this.orignal_input = document.getElementById(this.options.selector)) ){
            console.error("tags-input couldn't find an element with the specified ID");
            return this;
        }

        this.arr = [];
        this.wrapper = document.createElement('div');
        this.input = document.createElement('input');
        init(this);
        initEvents(this);

        this.initialized =  true;
        return this;
    }

    // Add Tags
    TagsInput.prototype.addTag = function(string){

        if(this.anyErrors(string))
            return ;

        this.arr.push(string);
        var tagInput = this;

        var tag = document.createElement('span');
        tag.className = this.options.tagClass;
        tag.innerText = string;

        var closeIcon = document.createElement('a');
        closeIcon.innerHTML = '&times;';
        
        // delete the tag when icon is clicked
        closeIcon.addEventListener('click' , function(e){
            e.preventDefault();
            var tag = this.parentNode;

            for(var i =0 ;i < tagInput.wrapper.childNodes.length ; i++){
                if(tagInput.wrapper.childNodes[i] == tag)
                    tagInput.deleteTag(tag , i);
            }
        })


        tag.appendChild(closeIcon);
        this.wrapper.insertBefore(tag , this.input);
        this.orignal_input.value = this.arr.join(',');

        return this;
    }

    // Delete Tags
    TagsInput.prototype.deleteTag = function(tag , i){
        tag.remove();
        this.arr.splice( i , 1);
        this.orignal_input.value =  this.arr.join(',');
        return this;
    }

    // Make sure input string have no error with the plugin
    TagsInput.prototype.anyErrors = function(string){
        if( this.options.max != null && this.arr.length >= this.options.max ){
            console.log('max tags limit reached');
            return true;
        }
        
        if(!this.options.duplicate && this.arr.indexOf(string) != -1 ){
            console.log('duplicate found " '+string+' " ')
            return true;
        }

        return false;
    }

    // Add tags programmatically 
    TagsInput.prototype.addData = function(array){
        var plugin = this;
        
        array.forEach(function(string){
            plugin.addTag(string);
        })
        return this;
    }

    // Get the Input String
    TagsInput.prototype.getInputString = function(){
        return this.arr.join(',');
    }


    // destroy the plugin
    TagsInput.prototype.destroy = function(){
        this.orignal_input.removeAttribute('hidden');

        delete this.orignal_input;
        var self = this;
        
        Object.keys(this).forEach(function(key){
            if(self[key] instanceof HTMLElement)
                self[key].remove();
            
            if(key != 'options')
                delete self[key];
        });

        this.initialized = false;
    }

    // Private function to initialize the tag input plugin
    function init(tags){
        tags.wrapper.append(tags.input);
        tags.wrapper.classList.add(tags.options.wrapperClass);
        tags.orignal_input.setAttribute('hidden' , 'true');
        tags.orignal_input.parentNode.insertBefore(tags.wrapper , tags.orignal_input);
    }

    // initialize the Events
    function initEvents(tags){
        tags.wrapper.addEventListener('click' ,function(){
            tags.input.focus();           
        });
        

        tags.input.addEventListener('keydown' , function(e){
            var str = tags.input.value.trim(); 

            if( !!(~[9 , 13 , 188].indexOf( e.keyCode ))  )
            {
                e.preventDefault();
                tags.input.value = "";
                if(str != "")
                    tags.addTag(str);
            }

        });
    }


    // Set All the Default Values
    TagsInput.defaults = {
        selector : '',
        wrapperClass : 'tags-input-wrapper',
        tagClass : 'tag',
        max : null,
        duplicate: false
    }

    window.TagsInput = TagsInput;

})();

 var tagInput1 = new TagsInput({
            selector: 'tag-input1',
            duplicate : false,
            max : 10
        });
    	<?php if(isset($_REQUEST['cl_id'])){
				$data=$listrole_sql['job_title'];
				$names = explode(',', $data);
				$quotedNames = array_map(function($name) {
    				return "'" . $name . "'";
				}, $names);
	if($listrole_sql['job_title']){
				$result = implode(',', $quotedNames);
	}
	else{
		$result = '';
	}
	?>
						
							tagInput1.addData([<?php echo $result;?>])
						
							<?php } ?>
</script>
</body>
</html>