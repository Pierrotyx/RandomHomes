<div id="id01" class="modal">
  <div class="modal-content animate" >
    <div class="imgcontainer">
      <img src="img_avatar2.png" alt="Avatar" class="avatar">
    </div>

    <div style="color:red;" id="loginError"></div>
    <div style="color:green;" id="registerSuccess"></div>
    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>

      <label for="pwd"><b>Password</b></label>
      <i class="fa fa-eye" aria-hidden="true" onclick="togglePassword('pwd')"></i>
      <input type="password" placeholder="Enter Password" name="pwd" id="pwd" required>
        
      <button onclick="login()">Login</button>
      <br>
      <button onclick="getPageAjax( 'getRegister' )">Create New Account</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
  </div>
</div>