<?php

route('home', 'Home@index');
route('login', 'Login@index');
route('login/verify', 'Login@verify');
route('logout', 'Login@logout');

// Aftercare Patient Portal
route('aftercare', 'Aftercare@index');
route('aftercare/login', 'Aftercare@login');
route('aftercare/verify', 'Aftercare@verify');
route('aftercare/change-password', 'Aftercare@changePassword');
route('aftercare/update-password', 'Aftercare@updatePassword');
route('aftercare/logout', 'Aftercare@logout');
route('aftercare/create-appointment', 'Aftercare@createAppointment');
route('aftercare/submit-support-request', 'Aftercare@submitSupportRequest');

route('forgot-password', 'ForgotPassword@index');
route('forgot-password/sendOtp', 'ForgotPassword@sendOtp');
route('forgot-password/verifyOtp', 'ForgotPassword@verifyOtp');
route('forgot-password/reset', 'ForgotPassword@reset');

route('user-admin', 'admin\UserAdmin@index');
route('donor', 'Donor@index');
route('donor/approved-labs', 'Donor@approvedLabs');
route('donor/donations', 'Donor@donations');
route('donor/consent-history', 'Donor@consentHistory');
route('donor/test-results', 'Donor@testResults');
route('donor/family-custodians', 'Donor@familyCustodians');
route('donor/aftercare', 'Donor@aftercare');
route('donor/create-appointment', 'Donor@createAppointment');
route('donor/submit-support-request', 'Donor@submitSupportRequest');
route('donor/documents', 'Donor@documents');
route('donor/download-pdf', 'Donor@downloadPdf');
route('donor/download-donor-card', 'Donor@viewDigitalCard');
route('donor/update-roles', 'Donor@updateRoles');
route('donor/update_profile', 'Donor@update_profile');
route('donor/set-portal-mode', 'Donor@setPortalMode');
route('donor/appointments', 'Donor@appointments');
route('donor/appointment-action', 'Donor@appointmentAction');
route('donor/aftercare', 'Donor@aftercare');
route('donor/financial-history', 'Donor@financialHistory');
route('donor/financial-donate', 'Donor@financialDonate');
route('donor/process-financial-donation', 'Donor@processFinancialDonation');
route('donor/withdraw-consent', 'Donor@withdrawConsent');
route('donor/get-pledge-details', 'Donor@getPledgeDetails');
route('donor/notifications', 'Donor@notifications');
route('donor/markNotificationRead', 'Donor@markNotificationRead');
route('donor/deleteNotification', 'Donor@deleteNotification');
route('donor/respondMatch', 'Donor@respondMatch');
route('donor/check_withdrawal_eligibility', 'Donor@checkWithdrawalEligibility');
route('donor/withdraw_account', 'Donor@withdraw_account');
route('donor/cancel_withdraw_account', 'Donor@cancel_withdraw_account');
route('custodian', 'Custodian@index');
route('custodian/dashboard-data', 'Custodian@getDashboardData');
route('custodian/declare-death', 'Custodian@declareDeath');
route('custodian/consent', 'Custodian@getConsent');
route('custodian/profile', 'Custodian@getProfile');
route('custodian/custodians', 'Custodian@getCustodians');
route('custodian/update-contact', 'Custodian@updateContact');
route('custodian/legal-action', 'Custodian@submitLegalAction');
route('custodian/available-institutions', 'Custodian@getAvailableInstitutions');
route('custodian/select-institution', 'Custodian@selectInstitution');
route('custodian/upload-document', 'Custodian@uploadDocument');
route('custodian/documents', 'Custodian@getDocuments');
route('custodian/submit-institution', 'Custodian@submitToInstitution');
route('custodian/check-username', 'Custodian@checkUsername');
route('custodian/cadaver-sheet', 'Custodian@saveCadaverSheet');
route('custodian/cadaver-sheet-data', 'Custodian@getCadaverSheet');
route('custodian/security-setup', 'Custodian@securitySetup');
route('custodian/update-security', 'Custodian@updateSecurity');
route('custodian/coordination', 'Custodian@getCoordination');
route('custodian/timeline', 'Custodian@getTimeline');
// ─── Custodian Portal — Page Routes ────────────────────────────────────────
route('custodian',                      'Custodian@index');
route('custodian/dashboard',            'Custodian@dashboard');
route('custodian/donor-profile',        'Custodian@donorProfile');
route('custodian/profile',              'Custodian@profile');
route('custodian/report-death',         'Custodian@reportDeath');
route('custodian/consent',              'Custodian@consent');
route('custodian/institution-requests', 'Custodian@institutionRequests');
route('custodian/co-custodian',         'Custodian@coCustodian');
route('custodian/legal-response',       'Custodian@legalResponse');
route('custodian/cadaver-data-sheet',   'Custodian@cadaverDataSheet');
route('custodian/coordination',         'Custodian@coordinationPage');
route('custodian/timeline',             'Custodian@timelinePage');
route('custodian/authority-limits',     'Custodian@authorityLimits');
route('custodian/documents',            'Custodian@documentsPage');
route('custodian/document-form',        'Custodian@documentForm');
route('custodian/save-document-form',   'Custodian@saveDocumentForm');
route('custodian/print-document',       'Custodian@printDocument');
route('custodian/submit-bundle',        'Custodian@submitBundle');
route('custodian/certificates',         'Custodian@certificates');
route('custodian/activity-history', 'Custodian@activityHistory');

// ─── Custodian Portal — JSON API Routes (/api/custodian/*) ─────────────────
route('api/custodian/dashboard-data',       'Custodian@getDashboardData');
route('api/custodian/declare-death',        'Custodian@declareDeath');
route('api/custodian/update-contact',       'Custodian@updateContact');
route('api/custodian/submit-action',        'Custodian@submitLegalAction');
route('api/custodian/submit-approval',      'Custodian@submitCustodianApproval');
route('api/custodian/available-institutions','Custodian@getAvailableInstitutions');
route('api/custodian/select-institution',   'Custodian@selectInstitution');
route('api/custodian/upload-document',      'Custodian@uploadDocument');
route('api/custodian/submit-institution',   'Custodian@submitToInstitution');


route('hospital', 'Hospital@index');
route('hospital/notifications', 'Hospital@notifications');
route('hospital/markAllNotificationsRead', 'Hospital@markAllNotificationsRead');
route('hospital/markNotificationRead', 'Hospital@markNotificationRead');
route('hospital/deleteNotification', 'Hospital@deleteNotification');
// Hospital dashboard sub-routes (clean URLs, same output as index)
route('hospital/organ-requests', 'Hospital@index');
route('hospital/consents', 'Hospital@index');
route('hospital/organ-testing', 'Hospital@index');
route('hospital/matching', 'Hospital@index');
route('hospital/surgery-prep', 'Hospital@index');
route('hospital/aftercare', 'Hospital@index');
route('hospital/deceased-requests', 'Hospital@index');
route('hospital/deceased-documents', 'Hospital@index');
route('hospital/deceased-final-flow', 'Hospital@index');

// Deceased Organ Management AJAX & Actions
route('hospital/deceased-requests/view', 'Hospital@viewDeceasedRequest');
route('hospital/deceased-requests/accept', 'Hospital@acceptDeceasedRequest');
route('hospital/deceased-requests/reject', 'Hospital@rejectDeceasedRequest');
route('hospital/deceased-submissions/view', 'Hospital@viewDeceasedSubmission');
route('hospital/deceased-submissions/accept', 'Hospital@acceptDeceasedSubmission');
route('hospital/deceased-final-flow/view', 'Hospital@viewDeceasedFinalFlow');
route('hospital/deceased-final-flow/accept', 'Hospital@acceptDeceasedFinalFlow');
// Legacy alias (avoid .view.php in the URL)
route('hospital/organ_request.view.php', 'Hospital@organRequestsLegacy');
// Donor-style route for Upcoming Appointments
route('hospital/appointments', 'Hospital@index');
// Clean section routes (render the same dashboard and open the correct section)
route('hospital/eligibility', 'Hospital@index'); // Keep for backward compatibility
route('hospital/aftercare-recipients', 'Hospital@aftercareRecipients');
route('hospital/stories', 'Hospital@index');
route('hospital/test-results', 'Hospital@index');
// Backward-compatible path (Upcoming Appointments section)
route('hospital/lab-reports', 'Hospital@index');
route('hospital/search-donors', 'Hospital@searchDonors');
route('hospital/addpatient', 'Hospital@addpatient');
route('hospital/addpatient/recipient', 'Hospital@addpatientRecipient');
route('hospital/addpatient/donor', 'Hospital@addpatientDonor');
route('hospital/fetch-donor-details', 'Hospital@fetchDonorDetails');
route('hospital/get-pledge-details', 'Hospital@getPledgeDetails');
route('hospital/get-surgery-match-details', 'Hospital@getSurgeryMatchDetails');
route('hospital/handle-match-action', 'Hospital@handleMatchAction');
route('hospital/view-donation-certificate', 'Hospital@viewDonationCertificate');
route('hospital/view-appreciation-letter', 'Hospital@viewAppreciationLetter');
route('medical-school', 'MedicalSchool@index');
route('medical-school', 'MedicalSchool@dashboard');
route('medical-school/dashboard', 'MedicalSchool@dashboard');

// Stage A: Consents
route('medical-school/consents', 'MedicalSchool@consents');
route('medical-school/consents/view', 'MedicalSchool@viewConsent');
route('medical-school/consents/flag', 'MedicalSchool@flagConsent');

// Stage B: Withdrawals
route('medical-school/withdrawals', 'MedicalSchool@withdrawals');
route('medical-school/withdrawals/view', 'MedicalSchool@viewWithdrawal');

// Stage C: Body Submission Requests
route('medical-school/submission-requests', 'MedicalSchool@submissionRequests');
route('medical-school/submission-requests/view', 'MedicalSchool@viewSubmissionRequest');
route('medical-school/submission-requests/accept', 'MedicalSchool@acceptSubmissionRequest');
route('medical-school/submission-requests/reject', 'MedicalSchool@rejectSubmissionRequest');

// Stage D: Custodian Declines
route('medical-school/custodian-declines', 'MedicalSchool@custodianDeclines');
route('medical-school/custodian-declines/view', 'MedicalSchool@viewCustodianDecline');

// Stages E & F: Body Submissions
route('medical-school/submissions', 'MedicalSchool@submissions');
route('medical-school/submissions/view', 'MedicalSchool@viewSubmission');
route('medical-school/submissions/accept', 'MedicalSchool@acceptSubmission');
route('medical-school/submissions/reject', 'MedicalSchool@rejectSubmission');
route('medical-school/submissions/request-documents', 'MedicalSchool@requestAdditionalDocuments');

// Stage G: Final Body Examination
route('medical-school/final-examinations', 'MedicalSchool@finalExaminations');
route('medical-school/final-examinations/view', 'MedicalSchool@viewFinalExamination');
route('medical-school/final-examinations/accept', 'MedicalSchool@acceptFinalBody');
route('medical-school/final-examinations/reject', 'MedicalSchool@rejectFinalBody');

// Extended Modules
route('medical-school/appreciation', 'MedicalSchool@appreciation');
route('medical-school/appreciation/generate', 'MedicalSchool@generateAppreciationLetter');
route('medical-school/appreciation/view', 'MedicalSchool@viewAppreciationLetter');

route('medical-school/certificates', 'MedicalSchool@certificates');
route('medical-school/certificates/generate', 'MedicalSchool@generateDonationCertificate');
route('medical-school/certificates/view', 'MedicalSchool@viewCertificate');

route('medical-school/stories', 'MedicalSchool@stories');
route('medical-school/createStory', 'MedicalSchool@createStory');
route('medical-school/deleteStory', 'MedicalSchool@deleteStory');
route('medical-school/stories/edit', 'MedicalSchool@editStory');

route('medical-school/usage-logs', 'MedicalSchool@usageLogs');
route('medical-school/usage-logs/submit', 'MedicalSchool@submitUsage');
route('medical-school/issue-appreciation-letter', 'MedicalSchool@issueAppreciationLetter');
route('medical-school/debug/reset-donor', 'MedicalSchool@resetDonor');
route('medical-school/view-inventory-detail', 'MedicalSchool@viewInventoryDetail');
route('medical-school/reports', 'MedicalSchool@reports');

// New Registration Flow
route('signup', 'RegistrationNew@index');
route('registration/donor', 'RegistrationDonor@index');
route('registration/donation', 'RegistrationDonation@index');
route('registration/institution', 'RegistrationInstitution@index');
route('registration/review', 'RegistrationReview@index');
route('registration/submit', 'RegistrationReview@submit');
route('registration/pending', 'RegistrationPending@index');
route('registration/check-availability', 'RegistrationData@checkAvailability');
route('registrationData/sendOtp', 'RegistrationData@sendOtp');
route('registrationData/verifyOtp', 'RegistrationData@verifyOtp');
route('registrationData/checkStatus', 'RegistrationData@checkStatus');

// Admin & Verification
route('user-admin/getPendingDocuments', 'admin\UserAdmin@getPendingDocuments');
route('user-admin/updateEntityVerification', 'admin\UserAdmin@updateEntityVerification');
route('user-admin/getDashboardStats', 'admin\UserAdmin@getDashboardStats');
route('user-admin/getUsers', 'admin\UserAdmin@getUsers');
route('user-admin/updateUserStatus', 'admin\UserAdmin@updateUserStatus');
route('user-admin/getNotifications', 'admin\UserAdmin@getNotifications');
route('user-admin/getAuditLogs', 'admin\UserAdmin@getAuditLogs');
route('user-admin/sendNotification', 'admin\UserAdmin@sendNotification');
route('user-admin/getUser', 'admin\UserAdmin@getUser');
route('user-admin/getDetailedUser', 'admin\UserAdmin@getDetailedUser');
route('user-admin/updateUser', 'admin\UserAdmin@updateUser');
route('user-admin/reviewUser', 'admin\UserAdmin@reviewUser');
route('user-admin/profile', 'admin\UserAdmin@profile');
route('user-admin/checkUsername', 'admin\UserAdmin@checkUsername');
route('user-admin/getFeedbacks', 'admin\UserAdmin@getFeedbacks');
route('user-admin/updateFeedbackStatus', 'admin\UserAdmin@updateFeedbackStatus');
route('user-admin/deleteFeedback', 'admin\UserAdmin@deleteFeedback');
route('user-admin/bulkUpdateEntityVerification', 'admin\UserAdmin@bulkUpdateEntityVerification');

// Donation Admin Routes
route('donation-admin', 'admin\DonationAdminController@index');
route('donation-admin/getDashboardStats', 'admin\DonationAdminController@getDashboardStats');
route('donation-admin/getOrganDetails', 'admin\DonationAdminController@getOrganDetails');
route('donation-admin/updateOrganStatus', 'admin\DonationAdminController@updateOrganStatus');
route('donation-admin/getPledges', 'admin\DonationAdminController@getPledges');
route('donation-admin/getHospitalRequests', 'admin\DonationAdminController@getHospitalRequests');
route('donation-admin/runAlgorithm', 'admin\DonationAdminController@runAlgorithm');
route('donation-admin/getFilterMetadata', 'admin\DonationAdminController@getFilterMetadata');
route('donation-admin/getMatchDetails', 'admin\DonationAdminController@getMatchDetails');
route('donation-admin/getPatients', 'admin\DonationAdminController@getPatients');
route('donation-admin/getPatientDetails', 'admin\DonationAdminController@getPatientDetails');

// Tributes Admin Routes
route('tributes-admin/getHospitals', 'admin\TributesAdminController@getHospitals');
route('tributes-admin/getStories', 'admin\TributesAdminController@getStories');
route('tributes-admin/getTributeDetails', 'admin\TributesAdminController@getTributeDetails');
route('tributes-admin/deleteTribute', 'admin\TributesAdminController@deleteTribute');
route('tributes-admin/saveStory', 'admin\TributesAdminController@saveStory');
route('tributes-admin/updateStatus', 'admin\TributesAdminController@updateStatus');
route('tributes-admin/getPendingCount', 'admin\TributesAdminController@getPendingCount');
route('tributes-admin/bulkDeleteTributes', 'admin\TributesAdminController@bulkDeleteTributes');

// Aftercare Admin
route('aftercare-admin', 'admin\AftercareAdminController@index');
route('aftercare-admin/handle-action', 'admin\AftercareAdminController@handleAction');
route('aftercare-admin/updateSupportStatus', 'admin\AftercareAdminController@updateSupportStatus');
route('aftercare-admin/getPatients', 'admin\AftercareAdminController@getPatients');
route('aftercare-admin/getPatientDetails', 'admin\AftercareAdminController@getPatientDetails');
route('aftercare-admin/filter-support', 'admin\AftercareAdminController@filterSupportRequests');


// Financial Admin
route('financial-admin', 'admin\FinancialAdminController@index');
route('financial-admin/getAllDonations', 'admin\FinancialAdminController@getAllDonations');
route('financial-admin/updateSupportStatus', 'admin\FinancialAdminController@updateSupportStatus');
route('financial-admin/logout', 'admin\FinancialAdminController@logout');
route('financial-admin/logo-logout', 'admin\FinancialAdminController@logoLogout');

// Financial Donor Portal (Legacy redirects - now merged into DONOR)
route('financial-donor', 'Donor@financialRedirect');
route('financial-donor/update-profile', 'Donor@financialRedirect');
route('financial-donor/donate', 'Donor@financialRedirect');
route('financial-donor/history', 'Donor@financialRedirect');
route('financial-donor/process-donation', 'Donor@processFinancialDonation');

// Appointment routes
route('appointment/all', 'AppointmentController@getAll');
route('appointment/get', 'AppointmentController@getOne');
route('appointment/create', 'AppointmentController@create');
route('appointment/update', 'AppointmentController@update');
route('appointment/cancel', 'AppointmentController@cancel');
route('appointment/complete', 'AppointmentController@complete');
route('appointment/search', 'AppointmentController@search');
route('appointment/search-patient', 'AppointmentController@searchPatient');
route('appointment/filter', 'AppointmentController@filter');

// Static Home Pages
route('education', 'HomePages@education');
route('legal', 'HomePages@legal');
route('live-donation', 'HomePages@liveDonation');
route('deceased-donation', 'HomePages@deceasedDonation');
route('our-story', 'HomePages@ourStory');
route('reach-us', 'HomePages@reachUs');
route('reach-us/submit', 'HomePages@submitContact');
route('religion', 'HomePages@religion');
route('religion/buddhism', 'HomePages@faithDetails');
route('religion/hinduism', 'HomePages@faithDetails');
route('religion/islam', 'HomePages@faithDetails');
route('religion/christianity', 'HomePages@faithDetails');
route('religion/sikhism', 'HomePages@faithDetails');
route('religion/judaism', 'HomePages@faithDetails');
route('religion/other', 'HomePages@faithDetails');

// Educational Content Routes
route('learn/donors', 'HomePages@donors');
route('learn/aftercare', 'HomePages@aftercareGuide');
route('learn/custodians', 'HomePages@custodians');

// Support routes
route('support/all', 'SupportRequestController@getAll');
route('support/get', 'SupportRequestController@getOne');
route('support/create', 'SupportRequestController@create');
route('support/approve', 'SupportRequestController@approve');
route('support/reject', 'SupportRequestController@reject');
route('support/bulk-approve', 'SupportRequestController@bulkApprove');
route('support/bulk-reject', 'SupportRequestController@bulkReject');
route('support/search', 'SupportRequestController@search');

