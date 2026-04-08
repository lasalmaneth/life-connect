</div>

<!-- OTP Verification Modal -->
<div class="modal-overlay" id="otpModal">
    <div class="modal-content text-center">
        <h3 class="modal-title">Verify Your Email</h3>
        <p class="modal-desc" style="margin-bottom:20px;">Please enter the 6-digit verification code sent to <br><strong id="otpDisplayEmail" style="color:var(--primary-color);"></strong></p>
        
        <div class="otp-inputs" style="display:flex; justify-content:center; gap:8px; margin-bottom:20px;">
            <input type="text" maxlength="1" id="otp_1" class="otp-box" onkeyup="moveOtpFocus(this, 1, event)">
            <input type="text" maxlength="1" id="otp_2" class="otp-box" onkeyup="moveOtpFocus(this, 2, event)">
            <input type="text" maxlength="1" id="otp_3" class="otp-box" onkeyup="moveOtpFocus(this, 3, event)">
            <input type="text" maxlength="1" id="otp_4" class="otp-box" onkeyup="moveOtpFocus(this, 4, event)">
            <input type="text" maxlength="1" id="otp_5" class="otp-box" onkeyup="moveOtpFocus(this, 5, event)">
            <input type="text" maxlength="1" id="otp_6" class="otp-box" onkeyup="moveOtpFocus(this, 6, event)">
        </div>
        
        <div id="otpModalErr" style="display:none; color:var(--danger); margin-bottom:15px; font-weight:500;"></div>
        
        <button type="button" class="btn btn-primary" style="width:100%; margin-bottom: 10px;" onclick="verifyOtpModal()" id="btnVerifyModal">Verify Code</button>
        <button type="button" class="btn btn-outline" style="width:100%; margin-bottom: 20px;" onclick="resendOtpModal()" id="btnResendModal">Resend OTP (60s)</button>
        
        <a href="#" onclick="event.preventDefault(); closeOtpModal();" style="color:var(--text-muted); font-size:0.9rem; text-decoration:underline;">Change Email Address</a>
    </div>
</div>

<!-- Status Check Modal -->
<div class="modal-overlay" id="statusModal">
    <div class="modal-content text-center">
        <button type="button" class="modal-close" onclick="closeStatusModal()" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--text-muted);"><i class="fas fa-times"></i></button>
        <h3 class="modal-title" style="margin-top:10px;">Check Application Status</h3>
        <p class="modal-desc" style="margin-bottom:20px;">Enter your Username, Email, or NIC number to view your current review status.</p>
        
        <div class="form-group" style="text-align:left;">
            <input type="text" id="statusModal_input" placeholder="e.g. 200012345678" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:6px; margin-bottom:15px;">
        </div>
        <div id="statusModalResult" style="margin-bottom:15px; font-weight:600; font-size:1.05rem; min-height:24px;"></div>
        <button type="button" class="btn btn-primary" style="width:100%;" onclick="checkRegStatusModal()">Check Status</button>
    </div>
</div>

<script src="<?= ROOT ?>/assets/js/registration-split.js?v=<?= time() ?>"></script>
</body>
</html>
