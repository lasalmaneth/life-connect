// let pendingOrgan = { id: null, name: '', icon: '🫀' };
// let pendingUnselectOrgan = { id: null, name: '', icon: '🫀' };

// function openConsentModal(organId, organName, organIcon) {
//     pendingOrgan.id = organId;
//     pendingOrgan.name = organName;
//     pendingOrgan.icon = organIcon || '🫀';
//     document.getElementById('consentModal').classList.add('show');
// }

// function closeConsentModal() {
//     document.getElementById('consentModal').classList.remove('show');
// }

// function confirmConsent() {
//     if (!pendingOrgan.id) { 
//         closeConsentModal(); 
//         return; 
//     }
//     // Build and submit a simple POST to add selection
//     const form = document.createElement('form');
//     form.method = 'POST';
//     form.style.display = 'none';

//     const act = document.createElement('input');
//     act.type = 'hidden'; 
//     act.name = 'action'; 
//     act.value = 'select';
//     const oid = document.createElement('input');
//     oid.type = 'hidden'; 
//     oid.name = 'organ_id'; 
//     oid.value = String(pendingOrgan.id);

//     form.appendChild(act);
//     form.appendChild(oid);
//     document.body.appendChild(form);
//     form.submit();
// }

// function openUnselectModal(organId, organName, organIcon) {
//     // Open unselect modal
    
//     pendingUnselectOrgan.id = organId;
//     pendingUnselectOrgan.name = organName;
//     pendingUnselectOrgan.icon = organIcon || '🫀';
    
    
//     // Update modal content
//     const iconElement = document.getElementById('unselectOrganIcon');
//     const nameElement = document.getElementById('unselectOrganName');
    
//     if (iconElement) {
//         iconElement.textContent = organIcon || '🫀';
//     } else {
//         console.error('unselectOrganIcon element not found');
//     }
    
//     if (nameElement) {
//         nameElement.textContent = organName;
//     } else {
//         console.error('unselectOrganName element not found');
//     }
    
//     const modal = document.getElementById('unselectModal');
//     if (modal) {
//         modal.classList.add('show');
//     } else {
//         console.error('unselectModal element not found');
//     }
// }

// function closeUnselectModal() {
//     const modal = document.getElementById('unselectModal');
//     if (modal) {
//         modal.classList.remove('show');
//     } else {
//         console.error('unselectModal element not found');
//     }
// }

// function confirmUnselect() {
    
//     if (!pendingUnselectOrgan.id) { 
//         closeUnselectModal(); 
//         return; 
//     }
    
//     // Show confirmation dialog
//     if (confirm('Are you sure you want to unselect this organ? This action will remove it from your donation pledge.')) {
        
//         // Build and submit a simple POST to remove selection
//         const form = document.createElement('form');
//         form.method = 'POST';
//         form.style.display = 'none';

//         const act = document.createElement('input');
//         act.type = 'hidden'; 
//         act.name = 'action'; 
//         act.value = 'unselect';
//         const oid = document.createElement('input');
//         oid.type = 'hidden'; 
//         oid.name = 'organ_id'; 
//         oid.value = String(pendingUnselectOrgan.id);

//         form.appendChild(act);
//         form.appendChild(oid);
//         document.body.appendChild(form);
        
//         form.submit();
//     } else {
//     }
// }

// function confirmRemove(organId) {
    
//     if (confirm('Are you sure you want to remove this organ from your donation list?')) {
        
//         // Build and submit a simple POST to remove selection
//         const form = document.createElement('form');
//         form.method = 'POST';
//         form.style.display = 'none';

//         const act = document.createElement('input');
//         act.type = 'hidden'; 
//         act.name = 'action'; 
//         act.value = 'unselect';
//         const oid = document.createElement('input');
//         oid.type = 'hidden'; 
//         oid.name = 'organ_id'; 
//         oid.value = String(organId);

//         form.appendChild(act);
//         form.appendChild(oid);
//         document.body.appendChild(form);
        
//         form.submit();
//     } else {
//     }
// }

// function openUnselectWarning(organId, organName, organIcon) {
    
//     pendingUnselectOrgan.id = organId;
//     pendingUnselectOrgan.name = organName;
//     pendingUnselectOrgan.icon = organIcon || '🫀';
    
//     // Update warning modal content
//     document.getElementById('warningOrganIcon').textContent = organIcon || '🫀';
//     document.getElementById('warningOrganName').textContent = organName;
    
//     document.getElementById('unselectWarningModal').classList.add('show');
// }

// function closeUnselectWarningModal() {
//     document.getElementById('unselectWarningModal').classList.remove('show');
// }

// function proceedToUnselectConsent() {
//     alert('Opening consent form...'); // Test alert
    
//     // Close warning modal
//     closeUnselectWarningModal();
    
//     // Update consent modal content
//     const iconElement = document.getElementById('consentOrganIcon');
//     const nameElement = document.getElementById('consentOrganName');
    
//     if (iconElement) {
//         iconElement.textContent = pendingUnselectOrgan.icon || '🫀';
//     } else {
//         console.error('consentOrganIcon element not found');
//     }
    
//     if (nameElement) {
//         nameElement.textContent = pendingUnselectOrgan.name;
//     } else {
//         console.error('consentOrganName element not found');
//     }
    
//     // Show consent modal
//     const modal = document.getElementById('unselectConsentModal');
//     if (modal) {
//         modal.classList.add('show');
        
//         // Debug: Check if confirm button exists
//         const confirmButton = modal.querySelector('button[onclick="confirmUnselectConsent()"]');
//         if (confirmButton) {
            
//             // Make button more visible for testing
//             confirmButton.style.backgroundColor = '#ff0000';
//             confirmButton.style.color = '#ffffff';
//             confirmButton.style.border = '3px solid #000000';
//             confirmButton.style.fontSize = '16px';
//             confirmButton.style.fontWeight = 'bold';
//         } else {
//             console.error('Confirm button not found in modal');
//         }
        
//         // Debug: Check modal footer
//         const modalFooter = modal.querySelector('.modal-footer');
//         if (modalFooter) {
//         } else {
//             console.error('Modal footer not found');
//         }
//     } else {
//         console.error('unselectConsentModal element not found');
//     }
// }

// function closeUnselectConsentModal() {
//     document.getElementById('unselectConsentModal').classList.remove('show');
// }

// function confirmUnselectConsent() {
//     alert('Confirm button clicked!'); // Test alert
    
//     if (!pendingUnselectOrgan.id) { 
//         closeUnselectConsentModal(); 
//         return; 
//     }
    
    
//     // Build and submit a simple POST to remove selection
//     const form = document.createElement('form');
//     form.method = 'POST';
//     form.style.display = 'none';

//     const act = document.createElement('input');
//     act.type = 'hidden'; 
//     act.name = 'action'; 
//     act.value = 'unselect';
//     const oid = document.createElement('input');
//     oid.type = 'hidden'; 
//     oid.name = 'organ_id'; 
//     oid.value = String(pendingUnselectOrgan.id);

//     form.appendChild(act);
//     form.appendChild(oid);
//     document.body.appendChild(form);
    
//     form.submit();
// }

// function cancelRemove() {
    
//     // Add visual feedback that the action was cancelled
//     const buttons = document.querySelectorAll('.organ-actions .btn');
//     buttons.forEach(button => {
//         if (button.textContent === 'Cancel') {
//             button.style.backgroundColor = '#10b981';
//             button.textContent = 'Cancelled';
//             button.disabled = true;
            
//             // Reset after 2 seconds
//             setTimeout(() => {
//                 button.style.backgroundColor = '';
//                 button.textContent = 'Cancel';
//                 button.disabled = false;
//             }, 2000);
//         }
//     });
// }

// function quickUnselect(organId) {
    
//     if (confirm('Are you sure you want to remove this organ from your donation list?')) {
        
//         // Build and submit a simple POST to remove selection
//         const form = document.createElement('form');
//         form.method = 'POST';
//         form.style.display = 'none';

//         const act = document.createElement('input');
//         act.type = 'hidden'; 
//         act.name = 'action'; 
//         act.value = 'unselect';
//         const oid = document.createElement('input');
//         oid.type = 'hidden'; 
//         oid.name = 'organ_id'; 
//         oid.value = String(organId);

//         form.appendChild(act);
//         form.appendChild(oid);
//         document.body.appendChild(form);
        
//         form.submit();
//     } else {
//     }
// }

// // Close modals when clicking outside
// window.onclick = function(event) {
//     const consentModal = document.getElementById('consentModal');
//     const unselectModal = document.getElementById('unselectModal');
//     const unselectWarningModal = document.getElementById('unselectWarningModal');
//     const unselectConsentModal = document.getElementById('unselectConsentModal');
    
//     if (event.target === consentModal) {
//         closeConsentModal();
//     }
//     if (event.target === unselectModal) {
//         closeUnselectModal();
//     }
//     if (event.target === unselectWarningModal) {
//         closeUnselectWarningModal();
//     }
//     if (event.target === unselectConsentModal) {
//         closeUnselectConsentModal();
//     }
// }
