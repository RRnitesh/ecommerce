<!-- login modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- header -->
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Login Page</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- body -->
      <div class="modal-body">
        <!-- action -->
        <form action="../php/login.php" method="POST" enctype="multipart/form-data">
          <!-- OTP Error Message -->

          <div id="loginErrorMessage" class="text-danger text-center" style="display: none;"></div>

          <!-- usernme -->
          <div class="mb-3">
            <label for="usernameLogin" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="usernameLogin" required>
          </div>
          <!-- password -->
          <div class="mb-3">
            <label for="passwordLogin" class="form-label">Password</label>
            <input type="password" class="form-control" id="passwordLogin" name="passwordLogin" required>
          </div>
          <!-- subit -->
          <button type="submit" class="btn btn-primary" name="login" id="loginbutton">Login</button>
        </form>
      </div>
      <!-- footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


