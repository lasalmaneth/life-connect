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
route('donor/download-donor-card', 'Donor@viewDigitalCard');
route('donor/update-roles', 'Donor@updateRoles');
route('donor/set-portal-mode', 'Donor@setPortalMode');
route('donor/appointments', 'Donor@appointments');
route('donor/appointment-action', 'Donor@appointmentAction');
route('donor/aftercare', 'Donor@aftercare');
route('donor/financial-history', 'Donor@financialHistory');
route('donor/financial-donate', 'Donor@financialDonate');
route('donor/process-financial-donation', 'Donor@processFinancialDonation');
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
route('custodian/cadaver-sheet', 'Custodian@saveCadaverSheet');
route('custodian/cadaver-sheet-data', 'Custodian@getCadaverSheet');
route('custodian/coordination', 'Custodian@getCoordination');
route('custodian/timeline', 'Custodian@getTimeline');
// ─── Custodian Portal — Page Routes ────────────────────────────────────────
// /custodian hard-redirects to /custodian/dashboard (no inline render)
route('custodian',                      'Custodian@index');
route('custodian/dashboard',            'Custodian@dashboard');
route('custodian/consent',              'Custodian@consent');
route('custodian/donor-profile',        'Custodian@donorProfile');
route('custodian/co-custodian',         'Custodian@coCustodian');
route('custodian/report-death',         'Custodian@reportDeath');
route('custodian/legal-response',       'Custodian@legalResponse');
route('custodian/cadaver-data-sheet',   'Custodian@cadaverDataSheet');
route('custodian/documents',            'Custodian@documentsPage');
route('custodian/coordination',         'Custodian@coordinationPage');
route('custodian/timeline',             'Custodian@timelinePage');
route('custodian/certificates',         'Custodian@certificates');
route('custodian/authority-limits',     'Custodian@authorityLimits');

// ─── Custodian Portal — JSON API Routes (/api/custodian/*) ─────────────────
route('api/custodian/dashboard-data',       'Custodian@getDashboardData');
route('api/custodian/declare-death',        'Custodian@declareDeath');
route('api/custodian/consent',              'Custodian@getConsent');
route('api/custodian/profile',              'Custodian@getProfile');
route('api/custodian/custodians',           'Custodian@getCustodians');
route('api/custodian/update-contact',       'Custodian@updateContact');
route('api/custodian/legal-action',         'Custodian@submitLegalAction');
route('api/custodian/available-institutions','Custodian@getAvailableInstitutions');
route('api/custodian/select-institution',   'Custodian@selectInstitution');
route('api/custodian/upload-document',      'Custodian@uploadDocument');
route('api/custodian/documents',            'Custodian@getDocuments');
route('api/custodian/submit-institution',   'Custodian@submitToInstitution');
route('api/custodian/cadaver-sheet',        'Custodian@saveCadaverSheet');
route('api/custodian/cadaver-sheet-data',   'Custodian@getCadaverSheet');
route('api/custodian/coordination',         'Custodian@getCoordination');
route('api/custodian/timeline',             'Custodian@getTimeline');
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
route('medical-school', 'MedicalSchool@index');
route('medical-school/consents', 'MedicalSchool@consents');
route('medical-school/withdrawals', 'MedicalSchool@withdrawals');
route('medical-school/submissions', 'MedicalSchool@submissions');
route('medical-school/body-acceptance', 'MedicalSchool@bodyAcceptance');
route('medical-school/usage-logs', 'MedicalSchool@usageLogs');
route('medical-school/certificates', 'MedicalSchool@certificates');
route('medical-school/archived', 'MedicalSchool@archived');
route('medical-school/reports', 'MedicalSchool@reports');
route('medical-school/get-donor-details', 'MedicalSchool@getDonorDetails');
route('medical-school/save-usage-log', 'MedicalSchool@saveUsageLog');
route('medical-school/get-usage-history', 'MedicalSchool@getUsageHistory');

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

