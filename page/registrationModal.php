<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="registrationModalLabel">Registration Page</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body">
        <!-- Registration Form -->
        <form action="../php/generateOTP&Send.php" method="POST" id="registrationForm">
          <!-- Full Name Field -->
          <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
          </div>
          <!-- Gmail Account Field -->
          <div class="mb-3">
            <label for="emailRegistration" class="form-label">Gmail Account</label>
            <input type="email" class="form-control" id="emailRegistration" name="emailRegistration" required>
          </div>
          <!-- Password Field -->
          <div class="mb-3">
            <label for="passwordRegistration" class="form-label">Password</label>
            <input type="password" class="form-control" id="passwordRegistration" name="passwordRegistration" required>
          </div>
          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
