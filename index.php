<?php 
session_start();
//echo 'My $_SESSION-login_database data = ' .$_SESSION['login_database'];
// echo phpversion();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<script type="text/javascript" src="include/js/jquery-1.8.3.min.js"></script>
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
<link rel="icon" href="img/favicon.ico" type="image/x-icon">
<title>Softworld - Loan System</title>

<style>
*{
  /* A universal CSS reset */
  margin:0;
  padding:0;
}
body 
{
  background:url(img/login/bg.jpg) no-repeat;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  font-family: Arial, Helvetica, sans-serif;
  font-size:12px;
  color:#FF0000;
}
input {
  
  text-align:center;
}
.style1 {color: #FFFFFF}
#box{
  background:url(img/login/login-box.png);
  width:356px;
    height:399px;
    position: absolute;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

footer{
  font-size:11px;
  margin-left: 133px;
}
.style5 {color: #E7E7DE}
#loginBtn
{
  background:url(img/login/submit-btn.jpg);
  width:109px;
  height:30px;
  border:none;
}
#loginBtn:hover
{
  background:url(img/login/submit-btn-roll-over.jpg);
}

#loading-text, #expired-text, #rejected-text {
  font-size: 14px;
  text-align: center;
  position: relative; /* Set the container to relative positioning */
  margin-bottom: 25px;
}

#loading-text span:first-child {
  position: fixed; /* Make the first span fixed */
  left: 50%; /* Center horizontally */
  transform: translateX(-50%); /* Center horizontally */
}

#loading-text span:last-child {
  position: fixed; /* Make the second span fixed */
  left: calc(50% + 65px); /* Adjust the distance to the right of the first span */
}

</style>
</head>
<body>
<center>
  <table width="500" height="290"><tr><td style="text-align:center;"><h1 style="color:BLACK;">LOAN MANAGEMENT SYSTEM</h1></td></tr></table>
<div id="box"><br>
<form id="loginForm" action="action.php" method="post">
<table width="356" height="399">
  
  <tr>
    <td height="80">&nbsp;</td>
    </tr>
    <tr>
      <td height="104" align="center" valign="bottom"><?php 
    if(isset($_SESSION['error']))
    {
      echo $_SESSION['error'].'<br>';
      $_SESSION['error'] = '';
      session_unset();
    }else { echo '<br>'; }
  ?><br>
        <div id="loading-text" style="display: none;">
          <span>Waiting for approval</span><span id="dots"></span>
        </div>
        <div id="expired-text" style="display: none;">
          <span>Your request has expired. Please login again.</span>
        </div>
        <div id="rejected-text" style="display: none;">
          <span>Your request is rejected.</span>
        </div>
      </td>
    </tr>
    <tr>
    
  <td height="81" align="center">
      <table><tr><td>
        <input type="text" id="username" name="username" placeholder="username" style="height: 23px; width: 235px;"></td></tr>
          <tr><td><br>
        <input type="password" id="password" name="password" placeholder="password" style="height: 23px; width: 235px;"></td></tr>
        <tr><td><br>
        <!-- <select id='branch' name='branch' style="height: 25px; width: 240px; text-align: center;" required>
          <option value="" hidden>--SELECT--</option>
          <option value='MAJUSAMA'>MAJUSAMA</option>
          <option value='ANSENG'>ANSENG</option>
          <option value='KTL'>KTL</option>
			  </select>
        </td></tr> -->
        <input type="text" id="branch" name="branch" placeholder="branch" style="height: 23px; width: 235px; text-transform: uppercase;"></td></tr>
        <tr><td><br>
        <tr><td align="center"><br><input type="submit" name="login" value="" id="loginBtn" /></td></tr>
      </table>
  </td>
  
    </tr>
    <tr>
      <td height="43">&nbsp;</td>
    </tr>
    <tr>
      <td align="right"></td>
    </tr>
</table>
</form>
</div>
</center>
<script>
  $(document).ready(function() {
    document.getElementById('username').focus();
  });

  // Prevent form submission on Enter key press
  $('#loginForm').on("submit", function(event) {
    event.preventDefault(); // Prevent the default form submission
    // You can add your custom logic here
    // console.log('pressed enter');

    var formData = new FormData(this);
      formData.append('login', '');
      $.ajax({
          url: $(this).attr('action'),
          type: $(this).attr('method'),
          data: formData,
          success: function(response) {
            // console.log(response);
            window.location.href = 'branch/';
          },
          cache: false,
          contentType: false,
          processData: false
      });
  });

  const ipAddress = getIPAddress();
  console.log('Your public IP address is: ', ipAddress);

  // Set up a timer to check approval status every second
  const timer = setInterval(function() {
   let username = $('#username').val();
   let branch = $('#branch').val();
   $.ajax({
       url: 'api/check_approval_status_ajax.php',
       data: {
         username: username,
         branch: branch
       },
       type: 'POST',
       dataType: 'text',
       success: function(response) {
         let status = response.trim();
         console.log(status);
         if (status == 'APPROVED' || status == 'REJECTED') {
           deleteExpiredLogin(username, branch);

           if (status == 'APPROVED') {
             $('#loginForm').submit();
           } else if (status == 'REJECTED') {
             // do nothing, because no need reject button, just keep this for flexibility
           }
         }
       },
       error: function(error) {
         console.log(error);
       }
   });
  }, 3000);

  // Prevent form submission on button click
  $('#loginBtn').on("click", function(event) {
    event.preventDefault(); // Prevent the default form submission
    // console.log('pressed button');

    let username = $('#username').val();
    let branch = $('#branch').val();

    $.ajax({
      url: 'api/login_action.php',
      type: 'POST',
      data: {
        username: username,
        password: $('#password').val(),
        branch: branch,
        ip_address: ipAddress
      },
      dataType: 'text',
      success: function(response) {
        // console.log(response);
        let str = response.trim();
        let arr = str.split(';');
        let status = arr[0];
        let sender_name = arr[1];
        let subscriptions = arr[2];
        console.log(status);

        if (status == 'staff-success') {
          // Waiting for approval
          showLoadingText();

          // Clear the timer after 2 minutes
          setTimeout(function() {
            clearInterval(timer);
            showExpiredText();
            deleteExpiredLogin(username, branch);
          }, 120000);

          $.ajax({
              url: 'https://linxpet.com/lms_pwa_server/api/send_notification.php',
              type: 'POST',
              data: {
                sender_name: sender_name,
                subscriptions: subscriptions
              },
              dataType: 'text',
              success: function(response) {
                console.log(response);
              }
          });
        } else {
          $('#loginForm').submit();
        }
      },
      error: function(xhr, status, error) {
        console.error("Error sending POST request:", error);
      }
    });
  });

  function getIPAddress() {
    let sharedIP = '<?php echo isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : ''; ?>';
    let proxyIP = '<?php echo isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ''; ?>';
    let remoteIP = '<?php echo isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''; ?>';
    
    if (sharedIP != '') {
      // if ip is from the shared internet
      return sharedIP;
    } else if (proxyIP != '') {
      // if ip is from the proxy  
      return proxyIP;
    } else {
      // if ip is from the remote address
      return remoteIP;
    }
  }

  function deleteExpiredLogin(username, branch) {
    $.ajax({
      url: 'api/delete_expired_login_ajax.php',
      data: {
        username: username,
        branch: branch
      },
      type: 'POST',
      dataType: 'text',
      success: function(response) {
        console.log(response);
      },
      error: function(error) {
        console.log(error);
      }
  });
  }

  function showLoadingText() {
    $('#expired-text').hide();
    $('#rejected-text').hide();
    $('#loading-text').show();
    const dotsElement = document.getElementById("dots");
    const dotAnimation = () => {
        setTimeout(() => {
            dotsElement.textContent = ".";
            setTimeout(() => {
                dotsElement.textContent = "..";
                setTimeout(() => {
                    dotsElement.textContent = "...";
                    dotAnimation(); // Repeat the animation
                }, 500); // Delay before showing the third dot
            }, 500); // Delay before showing the second dot
        }, 500); // Delay before showing the first dot
    };
    
    // Start the animation
    dotAnimation();
  }

  function showExpiredText() {
    $('#loading-text').hide();
    $('#rejected-text').hide();
    $('#expired-text').show();
  }

  function showRejectedText() {
    $('#loading-text').hide();
    $('#expired-text').hide();
    $('#rejected-text').show();
  }
</script>
</body>
</html>