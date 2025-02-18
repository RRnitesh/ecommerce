<!-- OTP Modal -->
<div class="modal fade" id="otpModalAdmin" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
<!-- data-bs-backdrop="static": Prevents closing the modal when clicking outside it. -->
<!-- data-bs-keyboard="false": Prevents closing the modal when pressing the ESC key. -->
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="otpModalLabel">OTP Verification</h5>
        <button type="button" class="btn-close closeModalButtonAdmin" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <!-- OTP Error Message -->
        <div id="otpErrorMessageAdmin" class="text-danger text-center" style="display: none;"></div>

        <!-- OTP Form -->
        <form action="../php/OTPverificationAdmin.php" method="POST" enctype="multipart/form-data" id="otpFormAdmin">
          <!-- OTP Field -->
          <div class="mb-3">
            <label for="otp" class="form-label">Enter OTP</label>
            <input 
              type="text" 
              class="form-control" 
              id="otpGivenbyUserAdmin" 
              name="userGivenOtpAdmin" 
              placeholder="Enter the OTP sent to your email" 
              required>
          </div>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" id="otpSubmitButtonAdmin" class="btn btn-primary">Verify OTP</button>
          </div>
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger closeModalButtonAdmin" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
