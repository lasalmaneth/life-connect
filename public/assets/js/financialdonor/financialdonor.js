
// function toggleNotifications() {
//     const panel = document.getElementById('notificationPanel');
//     const overlay = document.getElementById('notificationOverlay');
    
//     if (panel) {
//         panel.classList.toggle('active');
//         if (overlay) {
//             overlay.classList.toggle('active');
//         }
//     }
// }

// function closeNotifications() {
//     const panel = document.getElementById('notificationPanel');
//     const overlay = document.getElementById('notificationOverlay');
    
//     if (panel) {
//         panel.classList.remove('active');
//     }
//     if (overlay) {
//         overlay.classList.remove('active');
//     }
// }

// document.addEventListener('DOMContentLoaded', function() {
//     const currentPage = window.location.pathname.split('/').pop();
//     const menuItems = document.querySelectorAll('.menu-item');
    
//     menuItems.forEach(item => {
//         item.classList.remove('active');
//         const href = item.getAttribute('href');
//         if (href && currentPage.includes(href.split('/').pop())) {
//             item.classList.add('active');
//         }
//     });
    
//     // Initialize form validation
//     initializeFormValidation();
// });



// document.addEventListener('DOMContentLoaded', function() {
//         const currentPage = window.location.pathname.split('/').pop();
//         const menuItems = document.querySelectorAll('.menu-item');
        
//         menuItems.forEach(item => {
//             item.classList.remove('active');
//             const href = item.getAttribute('href');
//             if (href && currentPage.includes(href.split('/').pop())) {
//                 item.classList.add('active');
//             }
//         });
//     });

    

//         // Settings Modal
//         function openSettingsModal() {
//             document.getElementById('settingsModal').classList.add('active');
//         }

//         function closeSettingsModal() {
//             document.getElementById('settingsModal').classList.remove('active');
//         }

//         function saveSettings() {
//             document.getElementById('settingsForm').submit();
//         }

//         // Logout Modal
//         function openLogoutModal() {
//             document.getElementById('logoutModal').classList.add('active');
//         }

//         function closeLogoutModal() {
//             document.getElementById('logoutModal').classList.remove('active');
//         }

//         function confirmLogout() {
//             window.location.href = 'logout.php';
//         }

//         // Close modals when clicking outside
//         window.onclick = function(event) {
//             const settingsModal = document.getElementById('settingsModal');
//             const logoutModal = document.getElementById('logoutModal');
            
//             if (event.target === settingsModal) {
//                 closeSettingsModal();
//             }
//             if (event.target === logoutModal) {
//                 closeLogoutModal();
//             }
//         }
    


// FILE: public/assets/js/donor/donor.js
// Complete Fixed Version with Proper Logout

// ============================================
// NOTIFICATION FUNCTIONS
// ============================================

function toggleNotifications() {
    const panel = document.getElementById('notificationPanel');
    const overlay = document.getElementById('notificationOverlay');
    
    if (panel) {
        panel.classList.toggle('active');
        if (overlay) {
            overlay.classList.toggle('active');
        }
    }
}

function closeNotifications() {
    const panel = document.getElementById('notificationPanel');
    const overlay = document.getElementById('notificationOverlay');
    
    if (panel) {
        panel.classList.remove('active');
    }
    if (overlay) {
        overlay.classList.remove('active');
    }
}

// ============================================
// ACTIVE MENU HIGHLIGHTING
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        item.classList.remove('active');
        const href = item.getAttribute('href');
        if (href && currentPage.includes(href.split('/').pop())) {
            item.classList.add('active');
        }
    });
    
    // Initialize form validation
    initializeFormValidation();
    
    // Prevent back button after logout
    preventBackAfterLogout();
});

// ============================================
// PREVENT BACK BUTTON AFTER LOGOUT
// ============================================

function preventBackAfterLogout() {
    // Check if user is logged out (no session)
    // This works by detecting if we're on a protected page without proper session
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
    };
}

// ============================================
// POPUP NOTIFICATION FUNCTIONS
// ============================================

function showPopup(type, title, message) {
    const popup = document.getElementById('popupNotification');
    if (!popup) {
        console.error('Popup element not found');
        return;
    }
    
    const icon = popup.querySelector('.popup-icon');
    const titleEl = popup.querySelector('.popup-title');
    const messageEl = popup.querySelector('.popup-message');
    
    titleEl.textContent = title;
    messageEl.textContent = message;
    
    popup.className = 'popup-notification ' + type;
    
    if (type === 'success') {
        icon.textContent = '✓';
    } else if (type === 'error') {
        icon.textContent = '✕';
    }
    
    setTimeout(() => {
        popup.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        hidePopup();
    }, 3000);
}

function hidePopup() {
    const popup = document.getElementById('popupNotification');
    if (popup) {
        popup.classList.remove('show');
    }
}

// ============================================
// MODAL FUNCTIONS
// ============================================

function openSettingsModal() {
    document.getElementById('settingsModal').classList.add('active');
}

function closeSettingsModal() {
    document.getElementById('settingsModal').classList.remove('active');
}

function saveSettings() {
    if (validateForm()) {
        document.getElementById('settingsForm').submit();
    }
}

function openLogoutModal() {
    document.getElementById('logoutModal').classList.add('active');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.remove('active');
}

function confirmLogout() {
    // Clear any stored data
    if (typeof(Storage) !== "undefined") {
        sessionStorage.clear();
        localStorage.removeItem('donor_session');
    }
    
    // Redirect to logout.php which will handle session destruction
    // Use donor logout handler in app/views/donor
    window.location.replace('/life-connect/app/views/donor/logout.php');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const settingsModal = document.getElementById('settingsModal');
    const logoutModal = document.getElementById('logoutModal');
    
    if (event.target === settingsModal) {
        closeSettingsModal();
    }
    if (event.target === logoutModal) {
        closeLogoutModal();
    }
}

// ============================================
// FORM VALIDATION INITIALIZATION
// ============================================

function initializeFormValidation() {
    const form = document.getElementById('settingsForm');
    if (!form) return;
    
    // Phone number validation
    const phoneInput = form.querySelector('input[name="contact_number"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
            
            validatePhone(this);
        });
        
        phoneInput.addEventListener('blur', function() {
            validatePhone(this);
        });
    }
    
    // Email validation
    const emailInput = form.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            validateEmail(this);
        });
        
        emailInput.addEventListener('input', function() {
            // Remove validation state while typing
            this.classList.remove('invalid', 'valid');
            const errorDiv = this.parentElement.querySelector('.error-message');
            if (errorDiv) {
                errorDiv.remove();
            }
        });
    }
}

// ============================================
// PHONE NUMBER VALIDATION
// ============================================

function validatePhone(input) {
    const value = input.value.trim();
    const errorDiv = input.parentElement.querySelector('.error-message');
    
    // Remove existing error message
    if (errorDiv) {
        errorDiv.remove();
    }
    
    // Reset classes
    input.classList.remove('invalid', 'valid');
    
    // If empty, don't show error but mark as invalid
    if (value.length === 0) {
        return false;
    }
    
    // Validate: must be exactly 10 digits
    if (value.length !== 10) {
        input.classList.add('invalid');
        showFieldError(input, 'Phone number must be exactly 10 digits');
        return false;
    }
    
    // Check if starts with 0
    if (!value.startsWith('0')) {
        input.classList.add('invalid');
        showFieldError(input, 'Phone number must start with 0');
        return false;
    }
    
    // Valid phone number
    input.classList.remove('invalid');
    input.classList.add('valid');
    return true;
}

// ============================================
// EMAIL VALIDATION
// ============================================

function validateEmail(input) {
    const value = input.value.trim();
    const errorDiv = input.parentElement.querySelector('.error-message');
    
    // Remove existing error message
    if (errorDiv) {
        errorDiv.remove();
    }
    
    // Reset classes
    input.classList.remove('invalid', 'valid');
    
    // If empty, don't show error but mark as invalid
    if (value.length === 0) {
        return false;
    }
    
    // Email regex pattern
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!emailPattern.test(value)) {
        input.classList.add('invalid');
        showFieldError(input, 'Please enter a valid email address (e.g., example@domain.com)');
        return false;
    }
    
    // Valid email
    input.classList.remove('invalid');
    input.classList.add('valid');
    return true;
}

// ============================================
// HELPER FUNCTIONS
// ============================================

function showFieldError(input, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    input.parentElement.appendChild(errorDiv);
}

function validateForm() {
    const form = document.getElementById('settingsForm');
    if (!form) return false;
    
    let isValid = true;
    let firstInvalidField = null;
    
    // Validate phone number
    const phoneInput = form.querySelector('input[name="contact_number"]');
    if (phoneInput && phoneInput.hasAttribute('required')) {
        if (!validatePhone(phoneInput)) {
            isValid = false;
            if (!firstInvalidField) firstInvalidField = phoneInput;
        }
    }
    
    // Validate email
    const emailInput = form.querySelector('input[name="email"]');
    if (emailInput && emailInput.hasAttribute('required')) {
        if (!validateEmail(emailInput)) {
            isValid = false;
            if (!firstInvalidField) firstInvalidField = emailInput;
        }
    }
    
    // Check all required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (field.type === 'text' || field.type === 'email' || field.type === 'tel' || field.tagName === 'TEXTAREA' || field.tagName === 'SELECT') {
            if (!field.value.trim()) {
                field.classList.add('invalid');
                const existingError = field.parentElement.querySelector('.error-message');
                if (!existingError) {
                    showFieldError(field, 'This field is required');
                }
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            }
        }
    });
    
    // Show popup if validation failed
    if (!isValid) {
        showPopup('error', 'Validation Error', 'Please fix the errors in the form before submitting.');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
    }
    
    return isValid;
}