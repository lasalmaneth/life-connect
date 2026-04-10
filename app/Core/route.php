<?php

route('home', 'Home@index');
route('login', 'Login@index');
route('login/verify', 'Login@verify');
route('logout', 'Login@logout');

route('forgot-password', 'ForgotPassword@index');
route('forgot-password/sendOtp', 'ForgotPassword@sendOtp');
route('forgot-password/verifyOtp', 'ForgotPassword@verifyOtp');
route('forgot-password/reset', 'ForgotPassword@reset');

route('user-admin', 'UserAdmin@index');
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
route('donor/appointments', 'Donor@appointments');
route('donor/appointment-action', 'Donor@appointmentAction');
route('donor/aftercare', 'Donor@aftercare');
route('donor/financial-history', 'Donor@financialHistory');
route('donor/financial-donate', 'Donor@financialDonate');
route('donor/process-financial-donation', 'Donor@processFinancialDonation');
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
route('custodian/archive',              'Custodian@archive');

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
route('hospital/organ-requests', 'Hospital@organRequests');
route('hospital/eligibility', 'Hospital@eligibility');
route('hospital/recipients', 'Hospital@recipients');
route('hospital/stories', 'Hospital@stories');
route('hospital/search-recipients', 'Hospital@searchRecipients');
route('hospital/export-recipients', 'Hospital@exportRecipients');
route('hospital/lab-reports', 'Hospital@labReports');
route('hospital/search-donors', 'Hospital@searchDonors');
route('hospital/addpatient', 'Hospital@addpatient');
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
route('medical-school/stories/create', 'MedicalSchool@createStory');
route('medical-school/stories/edit', 'MedicalSchool@editStory');

route('medical-school/archived', 'MedicalSchool@archived');
route('medical-school/archived/view', 'MedicalSchool@viewArchivedRecord');

route('medical-school/usage-logs', 'MedicalSchool@usageLogs');
route('medical-school/usage-logs/view', 'MedicalSchool@viewUsageLog');

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
route('user-admin/getPend ingDocuments', 'UserAdmin@getPendingDocuments');
route('user-admin/updateEntityVerification', 'UserAdmin@updateEntityVerification');
route('user-admin/getDashboardStats', 'UserAdmin@getDashboardStats');
route('user-admin/getUsers', 'UserAdmin@getUsers');
route('user-admin/updateUserStatus', 'UserAdmin@updateUserStatus');
route('user-admin/getNotifications', 'UserAdmin@getNotifications');
route('user-admin/sendNotification', 'UserAdmin@sendNotification');
route('user-admin/bulkUpdateUserStatus', 'UserAdmin@bulkUpdateUserStatus');
route('user-admin/getUser', 'UserAdmin@getUser');
route('user-admin/getDetailedUser', 'UserAdmin@getDetailedUser');
route('user-admin/updateUser', 'UserAdmin@updateUser');
route('user-admin/reviewUser', 'UserAdmin@reviewUser');
route('user-admin/profile', 'UserAdmin@profile');
route('user-admin/checkUsername', 'UserAdmin@checkUsername');
route('user-admin/bulkUpdateEntityVerification', 'UserAdmin@bulkUpdateEntityVerification');

// Donation Admin Routes
route('donation-admin', 'DonationAdminController@index');
route('donation-admin/getDashboardStats', 'DonationAdminController@getDashboardStats');
route('donation-admin/getOrganDetails', 'DonationAdminController@getOrganDetails');
route('donation-admin/updateOrganStatus', 'DonationAdminController@updateOrganStatus');
route('donation-admin/getPledges', 'DonationAdminController@getPledges');
route('donation-admin/getHospitalRequests', 'DonationAdminController@getHospitalRequests');
route('donation-admin/runAlgorithm', 'DonationAdminController@runAlgorithm');

// Tributes Admin Routes
route('tributes-admin/getHospitals', 'TributesAdminController@getHospitals');
route('tributes-admin/getStories', 'TributesAdminController@getStories');
route('tributes-admin/getTributeDetails', 'TributesAdminController@getTributeDetails');
route('tributes-admin/deleteTribute', 'TributesAdminController@deleteTribute');
route('tributes-admin/saveStory', 'TributesAdminController@saveStory');
route('tributes-admin/updateStatus', 'TributesAdminController@updateStatus');
route('tributes-admin/getPendingCount', 'TributesAdminController@getPendingCount');
route('tributes-admin/bulkDeleteTributes', 'TributesAdminController@bulkDeleteTributes');

// Aftercare Admin
route('aftercare-admin', 'AftercareAdminController@index');
route('aftercare-admin/handle-action', 'AftercareAdminController@handleAction');
route('aftercare-admin/get-support-requests', 'AftercareAdminController@getSupportRequests');
route('aftercare-admin/get-aftercare-patients', 'AftercareAdminController@getAftercarePatients');
route('aftercare-admin/approve-support-request', 'AftercareAdminController@approveSupportRequest');
route('aftercare-admin/reject-support-request', 'AftercareAdminController@rejectSupportRequest');


// Financial Admin
route('financial-admin', 'FinancialAdminController@index');
route('financial-admin/getAllDonations', 'FinancialAdminController@getAllDonations');
route('financial-admin/logout', 'FinancialAdminController@logout');
route('financial-admin/logo-logout', 'FinancialAdminController@logoLogout');

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
route('religion', 'HomePages@religion');

// Support routes
route('support/all', 'SupportRequestController@getAll');
route('support/get', 'SupportRequestController@getOne');
route('support/create', 'SupportRequestController@create');
route('support/approve', 'SupportRequestController@approve');
route('support/reject', 'SupportRequestController@reject');
route('support/bulk-approve', 'SupportRequestController@bulkApprove');
route('support/bulk-reject', 'SupportRequestController@bulkReject');
route('support/search', 'SupportRequestController@search');

