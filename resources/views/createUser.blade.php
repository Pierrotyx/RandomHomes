<div id="register" class="modal">
  <div class="modal-content animate" >
    <div style="color:red;" id="loginError">
    </div>
    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>
	  
	  <label for="email"><b>Email</b></label>
      <input type="email" placeholder="jane@doe@email.com" name="email" required>
	  
	  <label for="bday"><b>Date of Birth</b></label>
      <input type="date" name="bday" required>
	  <br>
	  <br>
      <label for="pwd"><b>Password</b></label>
	  <i class="fa fa-eye" aria-hidden="true" onclick="togglePassword('pwd')"></i>
      <input type="password" placeholder="Enter Password" name="pwd" id="pwd" required />
	  
	  <label for="Check"><b>Re-enter Password</b></label>
	  <i class="fa fa-eye" aria-hidden="true" onclick="togglePassword('check')"></i>
      <input type="password" name="check" id="check" required />
        
      <button onclick="register()">Create Account</button>
	  <button onclick="getPage( 'getProfile', this )" style="width:20%; background-color:red;">Go back</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
  </div>
</div>