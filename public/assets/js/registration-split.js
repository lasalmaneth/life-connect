(function () {
    var STORAGE_KEY = "lifeconnect_registration";

    function readState() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {};
        } catch (err) {
            return {};
        }
    }

    function writeState(state) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        return state;
    }

    function mergeState(patch) {
        var current = readState();
        var next = Object.assign({}, current, patch);
        return writeState(next);
    }

    function mergeSection(key, patch) {
        var current = readState();
        var nextSection = Object.assign({}, current[key] || {}, patch);
        current[key] = nextSection;
        return writeState(current);
    }

    function setRole(role) {
        writeState({ role: role, donor: {}, donation: [], institution: {}, submittedAt: null });
    }

    function getRole() {
        return (readState().role || "").toLowerCase();
    }

    function setProgress(step, total) {
        var fill = step <= 1 ? 0 : Math.round((step - 1) / (total - 1) * 100);
        var fillEl = document.getElementById("pFill");
        if (fillEl) {
            fillEl.style.width = fill + "%";
        }
        for (var i = 1; i <= 4; i += 1) {
            var el = document.getElementById("ps" + i);
            if (!el) {
                continue;
            }
            el.classList.remove("active", "done");
            if (i < step) {
                el.classList.add("done");
            } else if (i === step) {
                el.classList.add("active");
            }
        }
    }

    function adjustProgressForRole() {
        var step = parseInt(document.body.getAttribute("data-step"), 10) || 1;
        var role = getRole();
        var total = role === "institution" ? 3 : 4;
        var step3Label = document.getElementById("ps3lbl");
        var step4 = document.getElementById("ps4");

        if (role === "institution" && step > 3) {
            step = 3;
        }

        if (step3Label) {
            step3Label.textContent = role === "institution" ? "Review" : "Donation";
        }
        if (step4) {
            step4.style.visibility = role === "institution" ? "hidden" : "visible";
        }

        setProgress(step, total);
    }

    function parseNicValue(value) {
        var raw = (value || "").trim().toUpperCase();
        var isNew10 = /^\d{10}$/.test(raw);
        var isNew12 = /^\d{12}$/.test(raw);
        var isOld = /^\d{8,9}[VX]$/.test(raw);
        
        if (!isNew10 && !isNew12 && !isOld) {
            return null;
        }
        
        var birthYear;
        var dayOfYear;
        
        if (isNew12) {
            // 12-digit format: YYYYDDDXXXXXX
            birthYear = parseInt(raw.substring(0, 4), 10);
            dayOfYear = parseInt(raw.substring(4, 7), 10);
        } else if (isNew10) {
            // 10-digit format: YYYYDDDXXX
            birthYear = parseInt(raw.substring(0, 4), 10);
            dayOfYear = parseInt(raw.substring(4, 7), 10);
        } else {
            // Old format: YYDDXXXXVX or YDDXXXXVX (8-9 digits + V/X)
            var yearPart = raw.substring(0, raw.length - 1);
            var yearDigits = yearPart.substring(0, 2);
            birthYear = 1900 + parseInt(yearDigits, 10);
            dayOfYear = parseInt(yearPart.substring(2, 5), 10);
        }

        var gender = "Male";
        if (dayOfYear > 500) {
            dayOfYear -= 500;
            gender = "Female";
        }
        
        if (dayOfYear < 1 || dayOfYear > 366) {
            return null;
        }
        
        // Sri Lankan NIC system always assumes 29 days for February for index calculation
        var monthDays = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        var month = 0;
        var remaining = dayOfYear;
        
        while (remaining > monthDays[month]) {
            remaining -= monthDays[month];
            month += 1;
            if (month > 11) {
                return null;
            }
        }
        
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var display = ("0" + remaining).slice(-2) + " " + monthNames[month] + " " + birthYear;
        var isoMonth = ("0" + (month + 1)).slice(-2);
        var isoDay = ("0" + remaining).slice(-2);
        var iso = birthYear + "-" + isoMonth + "-" + isoDay;

        return { display: display, iso: iso, gender: gender };
    }

    function goTo(url) {
        window.location.href = url;
    }

    function populateDonorFromState() {
        var state = readState();
        var donor = state.donor || {};
        var fields = {
            d_fname: donor.firstName,
            d_lname: donor.lastName,
            d_user: donor.username,
            d_nic: donor.nic,
            d_phone: donor.phone,
            d_email: donor.email
        };
        Object.keys(fields).forEach(function (key) {
            var el = document.getElementById(key);
            if (el && fields[key]) {
                el.value = fields[key];
            }
        });
        if (donor.dobDisplay) {
            var dob = document.getElementById("d_dob");
            var dobHint = document.getElementById("d_dobH");
            if (dob) {
                dob.value = donor.dobDisplay;
                dob.classList.add("ok");
            }
            if (dobHint) {
                dobHint.style.display = "block";
            }
        }
        if (donor.gender) {
            var gender = document.getElementById("d_gender");
            var genderHint = document.getElementById("d_genderH");
            if (gender) {
                gender.value = donor.gender;
                gender.classList.add("ok");
            }
            if (genderHint) {
                genderHint.style.display = "block";
            }
            if (genderHint) {
                genderHint.style.display = "block";
            }
        }
        // Force re-parse if NIC exists to ensure UI is consistent
        if (donor.nic) {
            window.onNIC();
            window.parseNIC();
        }
    }

    function populateDonationFromState() {
        // No longer interactive in registration.
        // We ensure state is clean for new donors.
        mergeState({ donation: [], pledgeType: 'NONE' });
    }

    function populateInstitutionFromState() {
        var state = readState();
        var inst = state.institution || {};
        if (inst.type) {
            pickInstType(inst.type);
        }
        var fields = {
            inst_name: inst.name,
            inst_user: inst.username,
            inst_reg: inst.reg,
            inst_transplant: inst.transplant,
            inst_email: inst.email,
            inst_phone: inst.phone,
            inst_addr: inst.address
        };
        Object.keys(fields).forEach(function (key) {
            var el = document.getElementById(key);
            if (el && fields[key]) {
                el.value = fields[key];
            }
        });
    }

    function buildReviewFromState() {
        var state = readState();
        var donor = state.donor || {};
        var inst = state.institution || {};
        var donation = state.donation || [];
        var role = getRole();
        var isInstitution = role === "institution" || (!role && inst && inst.name);

        var sumDonor = document.getElementById("sumDonor");
        var sumInst = document.getElementById("sumInst");

        if (isInstitution) {
            if (sumDonor) {
                sumDonor.classList.add("hidden");
            }
            if (sumInst) {
                sumInst.classList.remove("hidden");
            }
            var instTypeLabel = inst.type === "school" ? "Medical School" : inst.type === "hospital" ? "Hospital / Transplant Center" : "—";
            setText("sv_iname", inst.name);
            setText("sv_iuser", inst.username);
            setText("sv_itype", instTypeLabel);
            setText("sv_ireg", inst.reg);
            setText("sv_itransplant", inst.transplant);
            var transplantRow = document.getElementById("svTransplantRow");
            if (transplantRow) {
                transplantRow.style.display = inst.type === "hospital" ? "flex" : "none";
            }
            setText("sv_iemail", inst.email);
            setText("sv_iphone", inst.phone);
            setText("sv_iaddr", inst.address);
            return;
        }

        if (sumDonor) {
            sumDonor.classList.remove("hidden");
        }
        if (sumInst) {
            sumInst.classList.add("hidden");
        }
        setText("sv_fname", donor.firstName);
        setText("sv_lname", donor.lastName);
        setText("sv_user", donor.username);
        setText("sv_nic", donor.nic);
        setText("sv_dob", donor.dobDisplay);
        setText("sv_gender", donor.gender);
        setText("sv_phone", donor.phone);
        setText("sv_email", donor.email);
        var labels = [];
        // For the simplified informational flow, we display a placeholder
        setText("sv_don", "To be configured in profile dashboard");
    }

    function buildPendingFromState() {
        var state = readState();
        var role = getRole();
        var donor = state.donor || {};
        var inst = state.institution || {};
        var donation = state.donation || [];
        var isInstitution = role === "institution" || (!role && inst && inst.name);
        var email = isInstitution ? inst.email : donor.email;
        var submittedAt = state.submittedAt ? new Date(state.submittedAt) : new Date();

        setText("tDate", submittedAt.toLocaleDateString("en-GB"));
        setText("pendEmail", email || "—");

        var cells = [];
        if (isInstitution) {
            cells = [
                ["Institution", inst.name],
                ["Username", inst.username],
                ["Type", inst.type === "school" ? "Medical School" : "Hospital / Transplant Center"],
                ["Reg. Number", inst.reg]
            ];
            if (inst.type === "hospital") {
                cells.push(["Transplant Auth.", inst.transplant]);
            }
            cells = cells.concat([
                ["Email", inst.email],
                ["Phone", inst.phone],
                ["Address", inst.address],
                ["Account Type", "Medical Institution"],
                ["Submitted", submittedAt.toLocaleDateString("en-GB")]
            ]);
        } else {
            var labels = [];
            if (donation.indexOf("willing") > -1) {
                labels.push("Willing to Donate");
            }
            if (donation.indexOf("nondonor") > -1) {
                labels.push("Non-Donor (Opt-Out)");
            }
            if (donation.indexOf("financial") > -1) {
                labels.push("Financial Donor");
            }
            cells = [
                ["Full Name", (donor.firstName || "") + " " + (donor.lastName || "")],
                ["Username", donor.username],
                ["NIC Number", donor.nic],
                ["Date of Birth", donor.dobDisplay || "Not available"],
                ["Gender", donor.gender || "Not available"],
                ["Phone", donor.phone],
                ["Email", donor.email],
                ["Account Type", "Individual (Donor)"],
                ["Submitted", submittedAt.toLocaleDateString("en-GB")]
            ];
            if (labels.length) {
                cells.push(["__span", "Donation Intention: " + labels.join(" · ")]);
            }
        }

        var html = "";
        cells.forEach(function (cell) {
            if (cell[0] === "__span") {
                html += '<div class="i-cell span2"><div class="i-key">Info</div><div class="i-val">' + cell[1] + "</div></div>";
            } else {
                html += '<div class="i-cell"><div class="i-key">' + (cell[0] || "—") + "</div><div class=\"i-val\">" + (cell[1] || "—") + "</div></div>";
            }
        });
        var grid = document.getElementById("pendGrid");
        if (grid) {
            grid.innerHTML = html;
        }
    }

    function setText(id, value) {
        var el = document.getElementById(id);
        if (el) {
            el.textContent = value || "—";
        }
    }

    window.goRole = function () {
        goTo("/life-connect/public/registration");
    };

    window.goDonorBasic = function () {
        goTo("/life-connect/public/registration/donor");
    };

    window.goToDonor = function () {
        setRole("donor");
        goTo("/life-connect/public/registration/donor");
    };

    window.goToInst = function () {
        setRole("institution");
        goTo("/life-connect/public/registration/institution");
    };

    window.showReview = function () {
        goTo("/life-connect/public/registration/review");
    };

    window.reviewBack = function () {
        var role = getRole();
        if (role === "institution") {
            goTo("/life-connect/public/registration/institution");
        } else {
            goTo("/life-connect/public/registration/donation");
        }
    };

    window.lv = function (fid, hid, emsg) {
        var el = document.getElementById(fid);
        var h = document.getElementById(hid);
        if (!el || !h) {
            return;
        }
        var value = el.value.trim();
        if (!value) {
            el.classList.add("err");
            el.classList.remove("ok");
            h.textContent = "Required: " + emsg;
            h.className = "hint err";
        } else {
            el.classList.remove("err");
            el.classList.add("ok");
            h.textContent = "Looks good";
            h.className = "hint ok";
        }
    };

    var checkTimeouts = {};

    function checkAvailability(type, value, fid, hid) {
        if (checkTimeouts[fid]) clearTimeout(checkTimeouts[fid]);

        var el = document.getElementById(fid);
        var h = document.getElementById(hid);

        checkTimeouts[fid] = setTimeout(function () {
            h.textContent = "Checking availability...";
            h.className = "hint";

            fetch(window.ROOT + '/registration/check-availability?type=' + type + '&value=' + encodeURIComponent(value))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.success) {
                        if (data.available) {
                            el.classList.add("ok");
                            el.classList.remove("err");
                            h.textContent = data.message;
                            h.className = "hint ok";
                        } else {
                            el.classList.remove("ok");
                            el.classList.add("err");
                            h.textContent = data.message;
                            h.className = "hint err";
                        }
                    }
                })
                .catch(function () {
                    console.error("Availability check failed");
                });
        }, 500);
    }

    window.onUsername = function (fid, hid) {
        var el = document.getElementById(fid);
        var h = document.getElementById(hid);
        if (!el || !h) {
            return;
        }
        var value = el.value.trim();
        if (!value) {
            el.classList.remove("err", "ok");
            h.textContent = "Letters, numbers and underscores only";
            h.className = "hint";
            return;
        }
        if (/^[a-zA-Z0-9_]{3,30}$/.test(value)) {
            checkAvailability('username', value, fid, hid);
        } else {
            el.classList.add("err");
            el.classList.remove("ok");
            h.textContent = "3–30 characters, letters/numbers/underscores only";
            h.className = "hint err";
        }
    };

    window.onPhone = function (fid, hid) {
        var el = document.getElementById(fid);
        var h = document.getElementById(hid);
        if (!el || !h) {
            return;
        }
        var value = el.value.trim();
        if (!value) {
            el.classList.remove("err", "ok");
            h.textContent = "10 digits, starting with 0";
            h.className = "hint";
            return;
        }
        if (/^0\d{9}$/.test(value)) {
            el.classList.add("ok");
            el.classList.remove("err");
            h.textContent = "Valid phone number";
            h.className = "hint ok";
        } else {
            el.classList.add("err");
            el.classList.remove("ok");
            h.textContent = "Must be 10 digits starting with 0";
            h.className = "hint err";
        }
    };

    window.onEmail = function (fid, hid) {
        var el = document.getElementById(fid);
        var h = document.getElementById(hid);
        if (!el || !h) {
            return;
        }
        var value = el.value.trim();
        
        // Reset OTP state when email changes
        var flowType = fid === 'd_email' ? 'donor' : 'institution';
        window.otpState[flowType] = false;
        
        if (!value) {
            el.classList.remove("err", "ok");
            h.textContent = "Required";
            h.className = "hint";
            return;
        }
        if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            checkAvailability('email', value, fid, hid);
        } else {
            el.classList.add("err");
            el.classList.remove("ok");
            h.textContent = "Enter a valid email address";
            h.className = "hint err";
        }
    };

    window.otpState = { donor: false, institution: false };

    window.currentOtpFlow = '';
    window.currentOtpEmail = '';
    window.otpResendIntv = null;

    window.openOtpModal = function(type, email) {
        window.currentOtpFlow = type;
        // Normalize email to ensure consistency with backend checks
        email = (email || '').trim().toLowerCase();
        window.currentOtpEmail = email;
        document.getElementById('otpDisplayEmail').textContent = email.replace(/(.{2})(.*)(?=@)/,
            function(gp1, gp2, gp3) { return gp2 + gp3.replace(/./g, '*'); });
            
        // Reset old boxes
        for(let i=1; i<=6; i++) {
            let b = document.getElementById('otp_' + i);
            b.value = '';
            b.classList.remove('err');
        }
        document.getElementById('otpModalErr').style.display = 'none';
        document.getElementById('otpModal').classList.add('show');
        document.getElementById('otp_1').focus();
        
        window.resendOtpModal(true); // Auto dispatch initial Email internally
    };

    window.closeOtpModal = function() {
        document.getElementById('otpModal').classList.remove('show');
        if(window.otpResendIntv) clearInterval(window.otpResendIntv);
    };

    window.escapeOtpHtml = function (str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    window.resendOtpModal = function(isInitial) {
        var btn = document.getElementById('btnResendModal');
        if (btn.disabled && !isInitial) return;

        btn.disabled = true;
        btn.textContent = 'Sending...';

        var errBox = document.getElementById('otpModalErr');
        errBox.style.display = 'none';
        errBox.innerHTML = '';

        fetch(window.ROOT + '/registrationData/sendOtp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: window.currentOtpEmail })
        })
        .then(function(res) {
            return res.text().then(function(text) {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("PHP Error: " + text.substring(0, 200));
                }
            });
        })
        .then(function(data) {
            console.log("--- OTP Verification Debug Info ---");
            if (data.debug && Array.isArray(data.debug)) {
                data.debug.forEach(function(logLine) {
                    console.log(logLine);
                });
            } else {
                console.log("No debug info available from PHPMailer or mail sent cleanly without debug logs.");
            }
            console.log("Response Data:", data);
            console.log("----------------------------------");

            var isSuccess = !!data.success;
            var isDevFallback = !!data.dev_mode && !!data.dev_otp;
            var mailFailed = !!data.mail_failed;

            if (isSuccess) {
                errBox.textContent = data.message || 'Verification code sent to your email.';
                errBox.style.color = 'var(--success)';
                errBox.style.display = 'block';
            } else if (isDevFallback || mailFailed) {
                var html = '<div>' + escapeOtpHtml(data.message || 'OTP generated, but email could not be sent.') + '</div>';

                if (data.error) {
                    html += '<div style="margin-top:6px; font-size:0.9rem; color:var(--text-muted);">' +
                        escapeOtpHtml(data.error) +
                        '</div>';
                }

                if (data.dev_otp) {
                    html += '<div style="margin-top:10px; font-weight:700; color:var(--primary-color);">DEV OTP: ' +
                        escapeOtpHtml(data.dev_otp) +
                        '</div>';
                }

                errBox.innerHTML = html;
                errBox.style.color = 'var(--warning, #d97706)';
                errBox.style.display = 'block';
            } else {
                errBox.textContent = data.message || 'Failed to send OTP.';
                errBox.style.color = 'var(--danger)';
                errBox.style.display = 'block';
            }

            if (isSuccess || isDevFallback || mailFailed) {
                var cd = 60;
                btn.textContent = 'Resend in ' + cd + 's';

                window.otpResendIntv = setInterval(function() {
                    cd--;
                    btn.textContent = 'Resend in ' + cd + 's';

                    if (cd <= 0) {
                        clearInterval(window.otpResendIntv);
                        btn.disabled = false;
                        btn.textContent = 'Resend OTP';
                    }
                }, 1000);
            } else {
                btn.disabled = false;
                btn.textContent = 'Resend OTP';
            }
        })
        .catch(function(err) {
            errBox.textContent = err.message || 'Server error dispatching email.';
            errBox.style.color = 'var(--danger)';
            errBox.style.display = 'block';

            btn.disabled = false;
            btn.textContent = 'Resend OTP';
        });
    };

    window.verifyOtpModal = function() {
        var code = '';
        var boxes = [];
        for(let i=1; i<=6; i++) {
            var b = document.getElementById('otp_'+i);
            code += b.value;
            boxes.push(b);
        }
        
        var errBox = document.getElementById('otpModalErr');
        if (code.length < 6) {
            errBox.textContent = 'Please enter the full 6-digit code.';
            errBox.style.color = 'var(--danger)';
            errBox.style.display = 'block';
            boxes.forEach(function(el){ el.classList.add('err') });
            return;
        }
        
        var btn = document.getElementById('btnVerifyModal');
        btn.textContent = "Verifying...";
        btn.disabled = true;
        
        fetch(window.ROOT + '/registrationData/verifyOtp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: window.currentOtpEmail, otp: code })
        })
        .then(function(res) { 
            return res.text().then(function(text) {
                try { return JSON.parse(text); } 
                catch(e) { throw new Error("PHP Error: " + text.substring(0, 100)); }
            });
        })
        .then(function(data) {
            btn.textContent = "Verify Code";
            btn.disabled = false;
            if (data.success) {
                window.otpState[window.currentOtpFlow] = true;
                window.closeOtpModal();
                
                // If the triggering view specified a direct navigation callback, execute it immediately
                if (typeof window.onOtpVerified === 'function') {
                    var callback = window.onOtpVerified;
                    window.onOtpVerified = null; // Clear it to prevent looping
                    callback();
                    return;
                }
                
                // Proceed automatically to final submission natively!
                var revForm = document.getElementById('reviewForm');
                if (revForm) {
                    revForm.dataset.otpVerified = "true";
                    // Populate state BEFORE submitting — readState() may be empty if called later in onsubmit
                    var stateInput = document.getElementById('fullStateInput');
                    if (stateInput && typeof readState === 'function') {
                        var currentState = readState();
                        stateInput.value = JSON.stringify(currentState);
                    }
                    setTimeout(function() {
                        if (typeof revForm.requestSubmit === 'function') {
                            revForm.requestSubmit();
                        } else {
                            revForm.submit();
                        }
                    }, 50);
                } else {
                    // Fallback for non-review pages (if any were remaining)
                    if(window.currentOtpFlow === 'donor' && typeof window.donorNext === 'function') window.donorNext();
                    if(window.currentOtpFlow === 'institution' && typeof window.instNext === 'function') window.instNext();
                }
            } else {
                errBox.textContent = data.message || 'Invalid OTP. Please try again.';
                errBox.style.color = 'var(--danger)';
                errBox.style.display = 'block';
                boxes.forEach(function(el){ el.classList.add('err') });
            }
        })
        .catch(function(err) {
            btn.textContent = "Verify Code";
            btn.disabled = false;
            errBox.textContent = err.message || 'Server communication error.';
            errBox.style.color = 'var(--danger)';
            errBox.style.display = 'block';
        });
    };

    window.moveOtpFocus = function(el, idx, event) {
        el.classList.remove('err');
        document.getElementById('otpModalErr').style.display = 'none';

        if (event.key >= '0' && event.key <= '9') {
            el.value = event.key;
            if (idx < 6) document.getElementById('otp_' + (idx + 1)).focus();
            event.preventDefault();
        } else if (event.key === 'Backspace') {
            el.value = '';
            if (idx > 1) document.getElementById('otp_' + (idx - 1)).focus();
        } else if (event.key === 'ArrowLeft' && idx > 1) {
            document.getElementById('otp_' + (idx - 1)).focus();
        } else if (event.key === 'ArrowRight' && idx < 6) {
            document.getElementById('otp_' + (idx + 1)).focus();
        }
        
        if (el.value.length > 1) {
             el.value = el.value.charAt(0);
        }
    };

    window.onNIC = function () {
        var el = document.getElementById("d_nic");
        var h = document.getElementById("d_nicH");
        if (!el || !h) {
            return;
        }
        var value = el.value.trim();
        if (!value) {
            el.classList.remove("err", "ok");
            h.textContent = "New 12-digit format or old 9-digit + V / X";
            h.className = "hint";
            clearNICFields();
            return;
        }
        // Updated: Accept 10 digits, 8-9 digits + V (old format)
        if (/^(\d{10}|\d{8,9}[VXvx])$/.test(value)) {
            // Extract date of birth and gender immediately
            window.parseNIC();
            // Then check availability
            checkAvailability('nic', value, 'd_nic', 'd_nicH');
        } else {
            el.classList.add("err");
            el.classList.remove("ok");
            h.textContent = "Enter a valid NIC (10 digits or 8-9 digits + V/X)";
            h.className = "hint err";
            clearNICFields();
        }
    };

    function clearNICFields() {
        var dob = document.getElementById("d_dob");
        var gender = document.getElementById("d_gender");
        var dobH = document.getElementById("d_dobH");
        var genderH = document.getElementById("d_genderH");
        if (dob) {
            dob.value = "";
            dob.classList.remove("ok");
        }
        if (gender) {
            gender.value = "";
            gender.classList.remove("ok");
        }
        if (dobH) {
            dobH.style.display = "none";
        }
        if (genderH) {
            genderH.style.display = "none";
        }
    }

    window.parseNIC = function () {
        var nic = document.getElementById("d_nic");
        var nicH = document.getElementById("d_nicH");
        if (!nic) {
            return;
        }
        var val = nic.value.trim();
        // Only run logic if format is valid (10, 12 digits or 8-9 digits + V)
        if (!/^(\d{10}|\d{12}|\d{8,9}[VXvx])$/.test(val)) {
            // Let onNIC handle strict regex errors
            return;
        }

        var parsed = parseNicValue(val);

        if (!parsed) {
            // Regex passed but Logic failed (e.g. invalid date sequence)
            nic.classList.add("err");
            nic.classList.remove("ok");
            if (nicH) {
                nicH.textContent = "Invalid NIC sequence or illegal date";
                nicH.className = "hint err";
            }
            clearNICFields();
            return;
        }

        // If success, ensure NIC field is green
        nic.classList.add("ok");
        nic.classList.remove("err");
        if (nicH) {
            nicH.textContent = "Valid NIC format";
            nicH.className = "hint ok";
        }

        var dob = document.getElementById("d_dob");
        var gender = document.getElementById("d_gender");
        var dobH = document.getElementById("d_dobH");
        var genderH = document.getElementById("d_genderH");
        if (dob) {
            dob.value = parsed.display;
            dob.classList.add("ok");
        }

        if (gender) {
            gender.value = parsed.gender;
            gender.classList.add("ok");
        }

        if (dobH) {
            dobH.style.display = "block";
        }
        if (genderH) {
            genderH.style.display = "block";
        }
    };

    window.onPw = function () {
        var pw = document.getElementById("d_pw");
        var hint = document.getElementById("d_pwH");
        if (!pw || !hint) {
            return;
        }
        var value = pw.value;
        var bars = ["sb1", "sb2", "sb3", "sb4"].map(function (id) { return document.getElementById(id); });
        bars.forEach(function (bar) {
            if (bar) {
                bar.className = "sbar";
            }
        });
        
        if (!value) {
            hint.textContent = "Must include uppercase, lowercase, number and special character";
            hint.className = "hint";
            pw.classList.remove("err", "ok");
            return;
        }
        
        var missing = [];
        if (value.length < 8) missing.push("8+ chars");
        if (!/[A-Z]/.test(value) || !/[a-z]/.test(value)) missing.push("upper & lowercase");
        if (!/\d/.test(value)) missing.push("number");
        if (!/[^A-Za-z0-9]/.test(value)) missing.push("symbol");
        
        var score = 4 - missing.length;
        var cls = score <= 1 ? "weak" : score <= 2 ? "medium" : "strong";
        
        for (var i = 0; i < score; i += 1) {
            if (bars[i]) {
                bars[i].classList.add(cls);
            }
        }
        
        if (score === 4) {
            hint.textContent = "Strong password";
            hint.className = "hint ok";
            pw.classList.remove("err");
            pw.classList.add("ok");
        } else {
            hint.textContent = "Missing: " + missing.join(", ");
            hint.className = "hint err";
            pw.classList.remove("ok");
            pw.classList.add("err");
        }
        
        // Re-evaluate confirm password if it has been typed into
        if (document.getElementById("d_cpw") && document.getElementById("d_cpw").value) {
            window.onCpw();
        }
    };

    // Institution password validation
    window.onInstPw = function () {
        var pw = document.getElementById("inst_pw");
        var hint = document.getElementById("inst_pwH");
        if (!pw || !hint) {
            return;
        }
        var value = pw.value;
        var bars = ["isb1", "isb2", "isb3", "isb4"].map(function (id) { return document.getElementById(id); });
        bars.forEach(function (bar) {
            if (bar) {
                bar.className = "sbar";
            }
        });
        
        if (!value) {
            hint.textContent = "Must include uppercase, lowercase, number and special character";
            hint.className = "hint";
            pw.classList.remove("err", "ok");
            return;
        }
        
        var missing = [];
        if (value.length < 8) missing.push("8+ chars");
        if (!/[A-Z]/.test(value) || !/[a-z]/.test(value)) missing.push("upper & lowercase");
        if (!/\d/.test(value)) missing.push("number");
        if (!/[^A-Za-z0-9]/.test(value)) missing.push("symbol");
        
        var score = 4 - missing.length;
        var cls = score <= 1 ? "weak" : score <= 2 ? "medium" : "strong";
        
        for (var i = 0; i < score; i += 1) {
            if (bars[i]) {
                bars[i].classList.add(cls);
            }
        }
        
        if (score === 4) {
            hint.textContent = "Strong password";
            hint.className = "hint ok";
            pw.classList.remove("err");
            pw.classList.add("ok");
        } else {
            hint.textContent = "Missing: " + missing.join(", ");
            hint.className = "hint err";
            pw.classList.remove("ok");
            pw.classList.add("err");
        }
    };

    window.onCpw = function () {
        var pw = document.getElementById("d_pw");
        var cpw = document.getElementById("d_cpw");
        var hint = document.getElementById("d_cpwH");
        if (!pw || !cpw || !hint) {
            return;
        }
        if (!cpw.value) {
            cpw.classList.remove("err", "ok");
            hint.textContent = "Must match the password above";
            hint.className = "hint";
            return;
        }
        if (pw.value === cpw.value) {
            cpw.classList.add("ok");
            cpw.classList.remove("err");
            hint.textContent = "Passwords match";
            hint.className = "hint ok";
        } else {
            cpw.classList.add("err");
            cpw.classList.remove("ok");
            hint.textContent = "Passwords do not match";
            hint.className = "hint err";
        }
    };

    window.tPw = function (id, btn) {
        var field = document.getElementById(id);
        if (!field || !btn) {
            return;
        }
        var icon = btn.querySelector("i");
        if (field.type === "password") {
            field.type = "text";
            if (icon) {
                icon.classList.replace("fa-eye", "fa-eye-slash");
            }
        } else {
            field.type = "password";
            if (icon) {
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    };

    window.tToggle = function (cbId, rowId, hId, skipToggle) {
        var cb = document.getElementById(cbId);
        var row = document.getElementById(rowId);
        var h = document.getElementById(hId);
        if (!cb || !row || !h) {
            return;
        }
        if (!skipToggle) {
            cb.checked = !cb.checked;
        }
        row.classList.toggle("checked", cb.checked);
        h.textContent = cb.checked ? "Terms accepted" : "You must accept the terms to continue";
        h.className = cb.checked ? "hint ok" : "hint";
    };

    window.donorNext = function () {
        var errs = [];
        var fname = getValue("d_fname");
        var lname = getValue("d_lname");
        var user = getValue("d_user");
        var nic = getValue("d_nic");
        var phone = getValue("d_phone");
        var email = getValue("d_email");
        var pw = getValue("d_pw");
        var cpw = getValue("d_cpw");
        var terms = document.getElementById("d_terms");

        if (!fname) errs.push("First name is required");
        if (!lname) errs.push("Last name is required");
        if (!user || !/^[a-zA-Z0-9_]{3,30}$/.test(user)) errs.push("A valid username is required (3–30 chars, letters/numbers/underscores)");
        if (!nic || !(/^\d{9}[VXvx]$/.test(nic) || /^\d{12}$/.test(nic))) errs.push("A valid NIC number is required");
        if (!phone || !/^0\d{9}$/.test(phone)) errs.push("A valid 10-digit phone number is required");
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errs.push("A valid email address is required");
        if (!pw || pw.length < 8) errs.push("Password must be at least 8 characters");
        else {
            var pwMissing = [];
            if (!/[A-Z]/.test(pw)) pwMissing.push("uppercase");
            if (!/[a-z]/.test(pw)) pwMissing.push("lowercase");
            if (!/[0-9]/.test(pw)) pwMissing.push("number");
            if (!/[^A-Za-z0-9]/.test(pw)) pwMissing.push("special character");
            if (pwMissing.length) errs.push("Password missing: " + pwMissing.join(", "));
        }
        if (pw !== cpw) errs.push("Passwords do not match");
        if (!terms || !terms.checked) errs.push("You must accept the Terms & Conditions");

        if (document.getElementById("d_user") && document.getElementById("d_user").classList.contains("err")) {
            var msg = document.getElementById("d_userH") ? document.getElementById("d_userH").textContent : "Username is not available or invalid.";
            errs.push("Username: " + msg);
        }
        if (document.getElementById("d_nic") && document.getElementById("d_nic").classList.contains("err")) {
            var msg = document.getElementById("d_nicH") ? document.getElementById("d_nicH").textContent : "NIC is not available or invalid.";
            errs.push("NIC: " + msg);
        }
        if (document.getElementById("d_email") && document.getElementById("d_email").classList.contains("err")) {
            var msg = document.getElementById("d_emailH") ? document.getElementById("d_emailH").textContent : "Email is not available or invalid.";
            errs.push("Email: " + msg);
        }

        if (errs.length) {
            var ul = document.getElementById("donorErrList");
            if (ul) {
                ul.innerHTML = "";
                errs.forEach(function (err) {
                    var li = document.createElement("li");
                    li.textContent = err;
                    ul.appendChild(li);
                });
            }
            var box = document.getElementById("donorErr");
            if (box) {
                box.classList.add("show");
            }
            window.scrollTo({ top: 0, behavior: "smooth" });
            return;
        }

        var parsed = parseNicValue(nic);
        mergeState({ role: "donor" });
        mergeSection("donor", {
            firstName: fname,
            lastName: lname,
            username: user,
            nic: nic,
            dobDisplay: parsed ? parsed.display : "",
            dobIso: parsed ? parsed.iso : "",
            gender: parsed ? parsed.gender : getValue("d_gender"),
            phone: phone,
            email: email
        });

        var boxHidden = document.getElementById("donorErr");
        if (boxHidden) {
            boxHidden.classList.remove("show");
        }
        // Submit the form to PHP to save in Session (securely)
        document.getElementById("donorForm").submit();
    };

    window.pickInstType = function (type) {
        instType = type;
        var school = document.getElementById("it_school");
        var hospital = document.getElementById("it_hospital");
        if (school) {
            school.classList.toggle("sel", type === "school");
        }
        if (hospital) {
            hospital.classList.toggle("sel", type === "hospital");
        }
        var transplantRow = document.getElementById("transplantRow");
        if (transplantRow) {
            transplantRow.style.display = type === "hospital" ? "flex" : "none";
        }
    };

    window.instNext = function () {
        var errs = [];
        var name = getValue("inst_name");
        var user = getValue("inst_user");
        var reg = getValue("inst_reg");
        var transplant = getValue("inst_transplant");
        var email = getValue("inst_email");
        var phone = getValue("inst_phone");
        var address = getValue("inst_addr");
        var pw = getValue("inst_pw");
        var terms = document.getElementById("inst_terms");

        if (!instType) errs.push("Please select an institution type");
        if (!name) errs.push("Institution name is required");
        if (!user || !/^[a-zA-Z0-9_]{3,30}$/.test(user)) errs.push("A valid username is required");
        if (!reg) errs.push("Registration number is required");
        if (instType === "hospital" && !transplant) errs.push("Transplant Authorization ID is required");
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errs.push("A valid official email address is required");
        if (!phone || !/^0\d{9}$/.test(phone)) errs.push("A valid 10-digit contact number is required");
        if (!address) errs.push("Address is required");
        if (!pw || pw.length < 8) errs.push("Password must be at least 8 characters");
        else {
            var pwMissing = [];
            if (!/[A-Z]/.test(pw)) pwMissing.push("uppercase");
            if (!/[a-z]/.test(pw)) pwMissing.push("lowercase");
            if (!/[0-9]/.test(pw)) pwMissing.push("number");
            if (!/[^A-Za-z0-9]/.test(pw)) pwMissing.push("special character");
            if (pwMissing.length) errs.push("Password missing: " + pwMissing.join(", "));
        }
        if (!terms || !terms.checked) errs.push("You must accept the Terms & Conditions");

        if (document.getElementById("inst_user") && document.getElementById("inst_user").classList.contains("err")) {
            var msg = document.getElementById("inst_userH") ? document.getElementById("inst_userH").textContent : "Username is not available or invalid.";
            errs.push("Username: " + msg);
        }
        if (document.getElementById("inst_email") && document.getElementById("inst_email").classList.contains("err")) {
            var msg = document.getElementById("inst_emailH") ? document.getElementById("inst_emailH").textContent : "Email is not available or invalid.";
            errs.push("Email: " + msg);
        }

        if (errs.length) {
            var ul = document.getElementById("instErrList");
            if (ul) {
                ul.innerHTML = "";
                errs.forEach(function (err) {
                    var li = document.createElement("li");
                    li.textContent = err;
                    ul.appendChild(li);
                });
            }
            var box = document.getElementById("instErr");
            if (box) {
                box.classList.add("show");
            }
            window.scrollTo({ top: 0, behavior: "smooth" });
            return;
        }

        // OTP Check removed from here. Moved to final review step.

        mergeState({ role: "institution" });
        mergeSection("institution", {
            type: instType,
            name: name,
            username: user,
            reg: reg,
            transplant: transplant,
            email: email,
            phone: phone,
            address: address
        });

        var hideBox = document.getElementById("instErr");
        if (hideBox) {
            hideBox.classList.remove("show");
        }
        // Submit form to PHP
        document.getElementById("instForm").submit();
        // goTo("/life-connect/public/registration/review");
    };

    window.toggleDon = function () {
        // No longer interactive in registration flow
    };

    window.setPT = function () {
        // No longer interactive in registration flow
    };

    window.buildDonorSum = function () {
        mergeState({ donation: dons.slice(0) });
    };

    window.submitReg = function () {
        mergeState({ submittedAt: new Date().toISOString() });
        document.getElementById("reviewForm").submit();
    };

    window.openStatusModal = function() {
        document.getElementById('statusModalResult').textContent = '';
        document.getElementById('statusModal_input').value = '';
        document.getElementById('statusModal').classList.add('show');
    };

    window.closeStatusModal = function() {
        document.getElementById('statusModal').classList.remove('show');
    };

    window.checkRegStatusModal = function() {
        var iden = document.getElementById('statusModal_input').value.trim();
        var resEl = document.getElementById('statusModalResult');
        if(!iden) {
            resEl.textContent = 'Please enter an identifier first.';
            resEl.style.color = 'var(--danger)';
            return;
        }
        resEl.textContent = 'Checking...';
        resEl.style.color = '#64748b';
        
        fetch('/life-connect/public/registrationData/checkStatus', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ identifier: iden })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if(d.success) {
                var colors = { 'PENDING': '#d97706', 'ACTIVE': '#16a34a', 'REJECTED': '#dc2626' };
                var msg = "Status: " + d.status;
                if(d.status === 'REJECTED' && d.review_message) {
                    msg += " (Reason: " + d.review_message + ")";
                }
                resEl.textContent = msg;
                resEl.style.color = colors[d.status] || '#333';
            } else {
                resEl.textContent = d.message;
                resEl.style.color = '#64748b';
            }
        }).catch(function(e) {
            resEl.textContent = 'Error checking status.';
            resEl.style.color = 'var(--danger)';
        });
    };

    function getValue(id) {
        var el = document.getElementById(id);
        return el ? el.value.trim() : "";
    }

    var role = "";
    var instType = "";
    var dons = [];

    document.addEventListener("DOMContentLoaded", function () {
        role = getRole();
        dons = (readState().donation || []).slice(0);
        var page = document.body.getAttribute("data-page") || "";

        if (page === "role") {
            var links = document.querySelectorAll("[data-role-link]");
            links.forEach(function (link) {
                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    var nextRole = link.getAttribute("data-role");
                    var href = link.getAttribute("href");
                    if (nextRole) {
                        setRole(nextRole);
                    }
                    if (href) {
                        goTo(href);
                    }
                });
            });
        }

        if (page === "donor") {
            mergeState({ role: "donor" });
            populateDonorFromState();
        }

        if (page === "donation") {
            mergeState({ role: "donor" });
            populateDonationFromState();
        }

        if (page === "institution") {
            mergeState({ role: "institution" });
            populateInstitutionFromState();
        }

        if (page === "review") {
            buildReviewFromState();
        }

        if (page === "pending") {
            buildPendingFromState();
        }

        if (page === "pending") {
            buildPendingFromState();
        }

        var nicInput = document.getElementById("d_nic");
        if (nicInput) {

            nicInput.addEventListener("input", function () {
                var val = this.value.trim();
                // console.log("NIC Input: " + val);

                if (window.onNIC) window.onNIC();

                // Auto-parse on valid length
                if ((val.length === 10 || val.length === 12) && window.parseNIC) {

                    window.parseNIC();
                }
            });

            // Focusout listener
            nicInput.addEventListener("focusout", function () {

                if (window.parseNIC) window.parseNIC();
            });
        } else {

        }

        adjustProgressForRole();
    });

    // Expose for Bridge
    window.mergeState = mergeState;
    window.readState = readState;
    window.mergeSection = mergeSection;
    window.populateDonationFromState = populateDonationFromState;
    window.buildReviewFromState = buildReviewFromState; // Expose review renderer
    if (typeof populateInstitutionFromState === 'function') {
        window.populateInstitutionFromState = populateInstitutionFromState;
    }
})();
