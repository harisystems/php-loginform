<?php
 session_cache_limiter(FALSE); 
 session_start();
 $cap = 'notEq';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['captcha'] == $_SESSION['cap_code']) {
        // Captcha verification is Correct. Do something here!
        $cap = 'Eq';
    } else         {
        // Captcha verification is wrong. Take other action
        $cap = '';		
    }
}
 ob_start();
 $statecd='2';

include 'includes/connection.php';
include 'includes/comman.php';
$_SESSION['user']="";
global $str_stat;
?>
<?php 

if(isset($_POST['login'])&& isset($_POST['password'])&&$_POST['login']!=""&&$_POST['password']!="")
{
$username=$_REQUEST['login'];
$pwd=$_REQUEST['password'];
$str1 = $_SESSION['str1'];

//$statecd=$_SESSION['id'];
$con = open($statecd); 
$sql3 = "select password,login.level_cd,login.role_cd,role_user.role_name,username,user_code,role_access.access_page from login as login inner join role_user on login.level_cd=role_user.level_cd and login.role_cd=role_user.role_cd inner join role_access on login.level_cd=role_access.level_cd and login.role_cd=role_access.role_cd where username='".$username."'";

$rs3=mysqli_query($con,$sql3); 
$no_of_rows=mysqli_num_rows($rs3);

if($no_of_rows==0)
{
$str_stat="<font color=red>Invalid User Name or Password</font>";
}
else
if($no_of_rows==1)
{  
  $row=mysqli_fetch_assoc($rs3);
$pass=$row['password'];

$pass1 = $pass.$str1; 
$spass = md5($pass1);
 session_destroy();
if($pwd == $spass)
{   
  
   session_start();
   $old_sessionid = session_id();
   session_regenerate_id();
   $new_sessionid = session_id();
  // echo "Old Session: $old_sessionid <br/>";
  // echo "New Session: $new_sessionid <br/>";
   //print_r($_SESSION);
  //$row=mysql_fetch_assoc($rs3);
  //$statecd=$_SESSION['id'];
  $state='2';
  $_SESSION['state_cd']=$state; 
  $level_cd=$row['level_cd']; 
  $_SESSION['levelcd']=$level_cd;
  $role_cd=$row['role_cd']; 
  $_SESSION['rolecd']=$role_cd; 
  $page=$row['access_page']; 
  $_SESSION['page']=$page;
  $usercode=$row['user_code']; 
  $_SESSION['usercode']=$usercode;  
  $_SESSION['username']=$username;
  $scheme_cd ='1';  
  $_SESSION ['login'] = $username;
  // $ssid = $dept_cd.$scheme_cd.$level_cd.$role_cd;  
  // $_SESSION['pageid'] = $ssid; 

if(date('m')>= 4)
 {
   	$yearcd  =  substr(date('Y'),2,2);
 }
else
{
   $yearcd  =  substr(date('Y'),2,2) -1;
}
  
    $_SESSION['year_cd']=$yearcd; 

 if($level_cd=='1' && $scheme_cd == '1')
  {
  $ssid='111';
  }
  else
  {
   $ssid = $scheme_cd.$level_cd.$role_cd;      
  }
 $_SESSION['pageid'] = $ssid;
// echo $_SESSION['pageid'];
  
  $_SESSION['start'] = time(); // taking now logged in time
  
$_SESSION['expire'] = $_SESSION['start'] + (30 * 60) ; // ending a session in 30 minutes from the starting time  
 // $dt = date("d-m-Y H:i:a");
 
  $ip = get_client_ip();
 
  $sid=md5($ip.$usercode);
  //echo $sid;
   $_SESSION['sid']=$sid;
   //echo $sid;
   // for audit table purpose  
   $len=8;
   $aid = substr(str_shuffle("12345678"), 0, $len); 
   $aid= md5($aid);
   $_SESSION['aid']=$aid;   
   // end of audit 
   $today = date("Y-m-d H:i:a");
   $con = open($statecd);
   $qry2="select u.name from user_reg as u inner join login as l on u.user_cd = l.user_code where u.user_cd='".$usercode."'"; 
   $query="insert into audit_trails(id,useid,ipadd,process,login_sts)values('$aid','$username','$ip','$page','success')";
 
   $rs=mysqli_query($con,$query);
   //$query="update audit_trails set useid='$username',datetime='$today',process='$page',ipadd='$ip'";

   $rs2=mysqli_query($con,$qry2); 
 
   $no_of_rows=mysqli_num_rows($rs2);
  // echo  $no_of_rows;
 if($no_of_rows==0)
{
$str_stat="<font color=red>User Data Not Available Contact Administrator or Update Userprofile</font>";
}
else
if($no_of_rows==1)
{ 
  //session_start();
  $row1=mysqli_fetch_assoc($rs2); 
 
  $name=$row1['name'];
  $_SESSION['name']=$name;
  
   $len=8;
   $user = substr(str_shuffle("12345678"), 0, $len); 
 $use=md5($user);
 setcookie("user",$use,0,"/","httponly");
 //setcookie("user",$use,"forever","/","httponly");
 // echo "Session Created";
 $phpid= substr(str_shuffle("12345678"), 0, $len);
 $_COOKIE["PHPSESSID"]=md5($phpid);
 
 $_SESSION['$cookid'] = $_COOKIE["PHPSESSID"];
?>

<!--<script>location.href="<?php // echo $page; ?>";</script>
--><?php
}
}
else
{
$str_stat="<font color=red>Invalid UserName/Password</font>";
}
}

}

?>

<?php
if(isset($_REQUEST['changepwd'])&& $_REQUEST['changepwd']!="")
{
	$str_stat="<font color=red>Password has been Changed Successfully Please Relogin With New Password..</font>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Chathusruthi Music Academy | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">


  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="login.php"><b>Chathusruthi Music Academy</b></a>
  </div>

<div class="login-box">
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form name="frm" method="post" action="" onSubmit="return ValidateloginForm();" autocomplete="off">              
    <div class="form-group has-feedback">
        <input  name="login" type="text" class="form-control" id="u" size="30" placeholder="Username" onClick="return isChar()" />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
	  
	  <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" id="p" size="30" placeholder="Password" onChange="return convertme()" />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
	  <?php
	  $length = 8;
	  $str = substr(str_shuffle("12345678"), 0, $length);
	  $_SESSION['str1'] = $str; 
	  ?>	
	<input name="hdnchkstr" type="Hidden" value="<?php echo $str;?>" />
	  <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <img src="captcha.php"/>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="text" name="captcha" id="captcha" maxlength="6" size="20" class="form-control" placeholder="Enter Captcha.." />
        </div>
        <!-- /.col -->
      </div>
	  
	   <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
		  <button name="submit" type="submit" class="btn btn-primary btn-block btn-flat" value="Login">Sign In</button>
        </div>
        <!-- /.col -->
      </div>          
        <?php  if($str_stat!="") 
   			{
   		?>
             <tr bgcolor="#333333">
                  <td height="21%" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF"><?php echo $str_stat; ?></td>
			</tr>
                <?php 
		}
		?>
    </form>
	 <a href="#">I forgot my password</a><br>
	  <a href="reg/user_reg.php">Register for New User</a><br>
	
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
 <script type="text/javascript">
            $(document).ready(function(){
                $('#submit').click(function(){
				var name = $('#u').val();
                    var msg = $('#p').val();
                    var captcha = $('#captcha').val();
                   if( name.length == 0){
                        $('#u').addClass('error');
                    }
                    else{
                        $('#u').removeClass('error');
                    }

                    if( msg.length == 0){
                        $('#p').addClass('error');
                    }
                    else{
                        $('#p').removeClass('error');
                    }

                    if( captcha.length == 0){
                        $('#captcha').addClass('error');
                    }
                    else{
                        $('#captcha').removeClass('error');
                    }
                    
                    if(u.length != 0 && p.length != 0 && captcha.length != 0){
                        return true;
                    }
                    return false;
                });

                var capch = '<?php echo $cap; ?>';
				//alert('capch');
                if(capch != 'notEq'){
                    if(capch == 'Eq')
					{
					 location.href="<?php echo $page; ?>"
                    }
					else
					{
                        $('.cap_status').html("verification Wrong!").addClass('cap_status_error').fadeIn('slow');
                    }
                }                                
            });
        </script>
<script src="dist/js/md5.js" language="javascript"></script>
<script src="dist/js/sha1.js" language="javascript"></script>	
<script type="text/javascript">
 function convertme()
        {
		    var temp=document.frm.hdnchkstr.value;			
							   
            var value1 = hex_sha1(document.getElementById('p').value);
			var c = value1 + temp;
			
			var value2 = hex_md5(c);
			
            document.getElementById('p').value = value2;
        }
 function isChar(e)
    {
        var c;
        if(!e)
            e=window.event
        if(e.keyCode)
            c=e.keyCode;
        if(e.which)
            c=e.which;
        ch=String.fromCharCode (c);
        if((ch >='0' && ch <='9' || ch == '<' || ch == '>' || ch == '%'))
        { 
            alert("Please Enter Charecters Only");
            return false;
        }
            
        else
        {
            return true;
        }
    }
function ValidateloginForm()
{
	if (document.frm.login.value == "")
	{
		alert("Please enter login name")
		document.frm.login.focus()
		return false
	}
	if (document.frm.password.value == "")
	{
		alert("Please enter valid password")
		document.frm.password.focus()
		return false
	}
	if (document.frm.captcha.value == "")
	{
		alert("Please Enter Verification Code")
		document.frm.captcha.focus()
		return false
	}
	return true
}
//-->
</script>	
</body>
</html>