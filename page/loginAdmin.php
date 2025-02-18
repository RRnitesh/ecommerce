<!-- Admin Login Modal -->
<div class="modal fade" id="loginModalAdmin" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Admin Login</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Body -->.
      <div class="modal-body">
        <form id="loginFormAdmin" action="../php/adminLogin.php" method="POST">
          <!-- Error Message -->
          <div id="loginErrorMessageAdmin" class="text-danger text-center" style="display: none;"></div>

          <!-- Username -->
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="usernameAdmin" required autocomplete="off" autocapitalize="off" spellcheck="false">
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="passwordAdmin" required>
          </div>

          <!-- Submit -->
          <button type="submit" class="btn btn-primary" id="loginAdmin">Login</button>
        </form>
      </div>
      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
