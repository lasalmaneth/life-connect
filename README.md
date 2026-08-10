# LifeConnect — Organ Donation & Healthcare Coordination Platform

A web-based platform designed to support **organ donation, financial contributions, hospital coordination, donor management, and post-donation care** within the Sri Lankan healthcare context.

---

## 📌 Project Overview

**LifeConnect** is a centralized donation management platform that connects **donors, hospitals, donation administrators, custodians, medical schools, and financial donors**.

The system is designed to digitize and coordinate key processes involved in organ donation, including:

* Donor registration and role management
* Organ donation consent management
* Living and after-death organ donation
* Full-body donation
* Donor–hospital organ matching
* Medical appointments and test results
* Donation status tracking
* Donor identification and certificates
* Post-donation aftercare
* Financial support requests and donations

The project focuses not only on system implementation but also on **business requirements, business rules, process modelling, stakeholder management, and workflow design**.

---

# 🎯 Business Problem

Organ donation involves multiple stakeholders and several interconnected processes such as donor registration, consent verification, hospital requests, donor matching, medical testing, appointments, donation tracking, and post-donation care.

When these activities are handled through disconnected or manual processes, several challenges can arise:

* Difficulty coordinating donors and hospitals
* Manual consent and document handling
* Delays in donor–recipient matching
* Limited visibility of donation status
* Difficulty managing medical reports and appointments
* Challenges in tracking donor history
* Limited coordination of post-donation care
* Difficulty managing financial support requests

**LifeConnect** aims to provide a centralized digital platform to improve coordination, transparency, and accessibility across these processes.

---

# 🎯 Project Objectives

The main objectives of LifeConnect are to:

* Centralize donation-related information and workflows
* Digitize donor consent and approval processes
* Support different types of donation
* Improve donor–hospital coordination
* Support organ matching and request management
* Provide appointment and medical test management
* Track donation progress and history
* Provide appropriate aftercare services
* Facilitate financial donations and support requests
* Protect sensitive personal and medical information
* Provide digital donor cards and donation certificates

---

# 👥 Stakeholders

| Stakeholder                | Responsibilities                                                                                                               |
| -------------------------- | ------------------------------------------------------------------------------------------------------------------------------ |
| **Donor**                  | Manage profile, select roles, submit consents, respond to organ requests, view appointments, test results and donation history |
| **Donation Administrator** | Verify users, review donation consents, approve/reject submissions and manage donation-related processes                       |
| **Hospital**               | Submit organ requests, manage matching, schedule appointments, upload test results and update donation status                  |
| **Custodian**              | Participate in after-death donation-related consent and verification                                                           |
| **Medical School**         | Coordinate full-body donation-related activities                                                                               |
| **Financial Donor**        | View support requests and make financial donations                                                                             |
| **System**                 | Validate information, enforce business rules, manage workflows, notifications and records                                      |

---

# 👤 User Roles

During the first login, users select their applicable roles.

The system supports three roles:

* **Organ Donor**
* **Financial Donor**
* **Non-Donor**

Users can have multiple roles where applicable.

### Role Restrictions

* **Organ Donor + Non-Donor** cannot exist simultaneously.
* **Financial Donor** can be combined with either Organ Donor or Non-Donor.
* An Organ Donor can become a Non-Donor only after withdrawing applicable organ donation consents.

The user's dashboard and available functionality are customized according to the selected roles.

---

# ✨ Key Features

## 1. Donor Management

The Donor Portal allows users to:

* Register as a donor
* Select and modify applicable roles
* View and update their profile
* Manage donation consents
* Withdraw donation consents
* Withdraw their account
* View donation history
* Receive donation-related notifications
* Download donor identification cards

After account verification, sensitive identity information such as **NIC and email cannot be changed**, while permitted contact and address details can be updated.

---

# 🫀 2. Organ Donation & Consent Management

LifeConnect supports three major donation consent types:

### Living Organ Donation

The donor:

1. Selects the hospital requesting the organ
2. Provides details of two witnesses
3. Completes the consent form
4. Downloads the generated consent document
5. Obtains the required signatures
6. Uploads the signed document

The consent is submitted for **Donation Administrator approval**.

---

### After-Death Organ Donation

The donor:

1. Selects after-death donation
2. Provides details of two witnesses
3. Provides details of two custodians
4. Completes the consent form
5. Downloads the generated document
6. Obtains the required signatures
7. Uploads the signed document

Unlike living donation, the donor **does not need to select a hospital** for the after-death consent.

The Donation Administrator reviews the submission before the consent becomes active.

---

### Full-Body Donation

For full-body donation, the donor:

1. Selects a medical school
2. Provides details of two witnesses
3. Provides details of two custodians
4. Completes the consent form
5. Downloads the generated document
6. Obtains the required signatures
7. Uploads the signed document

The selected medical school is associated with the donation consent.

---

# 🔄 Consent Approval Workflow

```text
Select Donation Type
        ↓
Complete Consent Form
        ↓
Generate Consent PDF
        ↓
Download Form
        ↓
Obtain Required Signatures
        ↓
Upload Signed Form
        ↓
Pending Approval
        ↓
Donation Administrator Review
        ↓
   ┌────┴────┐
   ↓         ↓
Approve    Reject
   ↓         ↓
Active    Resubmit
Consent   Required
```

A consent becomes **Active only after administrator approval**.

---

# 🏥 3. Hospital Coordination

Hospitals can:

* Submit organ requests
* Search for matching donors
* Manage donor matching
* Schedule donor appointments
* Upload laboratory reports
* Upload medical test results
* Update donation status
* Manage recipient-related information
* Maintain relevant donation records

---

# 🔎 4. Organ Request & Donor Matching

The system searches hospital organ requests against eligible donors with active donation consent.

A donor may receive **multiple matching requests from different hospitals**.

The donor can:

* View matching requests
* Accept a request
* Reject a request

### Important Business Rule

A donor can accept **only one organ request**.

Once one request is accepted:

```text
Selected Hospital Request
          ↓
       ACCEPTED
          ↓
Other Matching Requests
          ↓
      REJECTED
```

The system notifies the relevant hospitals about the donor's decision.

---

# 📅 5. Appointments & Matching Tests

For living donation, donors may need medical tests for donor–recipient matching.

Donors can:

* View upcoming appointments
* View appointments through a calendar
* Accept appointments
* Reject appointments
* Provide a reason when rejecting an appointment

When an appointment is rejected, the hospital receives the reason and can **reschedule the appointment**.

---

# 🧪 6. Medical Test Results & Lab Reports

Hospitals can upload:

* Laboratory reports
* Medical test results
* Relevant medical documents

The system securely stores the records and links them to the relevant donor.

Donors can view their **own test results and reports** through their account.

Donors cannot modify hospital-uploaded medical results.

---

# 📊 7. Donation Status Tracking

Hospitals update the progress of donation activities.

Donors can view their own donation status and history.

Example stages include:

* Active
* In Progress
* Completed

The donor profile is updated when the hospital provides new status information.

---

# 🪪 8. Donor Identification Card

A donor who has at least **one approved and active donation consent** can generate a donor identification card.

The card contains relevant information such as:

* Donor name
* Donor ID
* Donation status

The donor card is generated as a downloadable PDF.

---

# 🏆 9. Donation Certificates & Recognition

After a donation is successfully completed, the system provides donor recognition features.

Eligible donors can receive:

* Donation certificates
* Donor profile rating

Certificates contain relevant information such as:

* Donor name
* Donation date

Certificates are available as downloadable PDF documents.

---

# ❤️ 10. Aftercare Portal

Donors who have completed an actual donation through the system can access the **Aftercare Portal**.

The system determines the appropriate aftercare package according to the **type of donation**.

The portal supports:

* Aftercare plans
* Annual checkups
* Aftercare forms
* Donor follow-up records

Aftercare services are restricted to eligible donors who have completed a donation.

---

# 💰 11. Financial Donation

Users with the **Financial Donor** role can contribute financial support to patients and hospitals.

Financial donors can:

* View open support requests
* View support categories
* View required cost
* View urgency
* Make donations
* View donation history
* Download appreciation certificates

Sensitive personal medical information is not displayed to financial donors.

---

## 💳 Financial Support Requests

Support requests are categorized according to the type of financial assistance required.

Financial donors can view limited information such as:

* Support type
* Required amount/cost
* Urgency

Personal medical information is protected and not exposed to financial donors.

---

## 💵 Make Payment

A Financial Donor selects a support request and provides payment details.

The system:

1. Validates payment information
2. Ensures required fields are complete
3. Links the payment to the selected support request
4. Processes and records the donation
5. Updates the relevant donation records

---

## 📜 Financial Donation History

Financial donors can view their previous successful donations.

The system retrieves the donor's records and displays information such as:

* Donation amount
* Donation date
* Support type/request
* Donation status

---

## 🏅 Appreciation Certificates

Financial donors with completed donations can download:

### Individual Donation Certificate

Generated for a specific completed donation and includes:

* Donor name
* Donation amount
* Donation date

### Cumulative Donation Certificate

The system calculates the donor's total completed donation amount and generates a certificate representing the cumulative contribution.

Both certificates are available as downloadable PDF documents.

---

# 🚪 12. Account Withdrawal

Registered users can request account withdrawal.

Before processing the withdrawal, the system checks whether the user has:

* Active donation processes
* Pending donation processes
* Ongoing donation activities

If an active donation process exists, withdrawal is blocked.

If no active process exists, the withdrawal request is sent for administrator confirmation.

After approval:

```text
Withdrawal Request
        ↓
Check Active Processes
        ↓
No Active Process
        ↓
Administrator Approval
        ↓
Account Status = Withdrawn
        ↓
Account Access Disabled
```

---

# 📋 Business Rules

LifeConnect contains business rules that control system behaviour and protect data integrity.

Examples include:

### Role Management

* Organ Donor and Non-Donor cannot exist simultaneously.
* Financial Donor can be combined with Organ Donor or Non-Donor.
* Organ Donors must withdraw applicable consents before becoming Non-Donors.

### Consent Management

* Only Organ Donors can submit organ donation consent forms.
* Required witnesses and custodians must be provided where applicable.
* Signed consent documents must be uploaded.
* Consent becomes Active only after Donation Administrator approval.

### Organ Matching

* A donor can receive multiple matching hospital requests.
* A donor can accept only one request.
* Other pending matching requests are automatically rejected after acceptance.

### Donor Card

* At least one approved and active consent is required to download a donor card.

### Financial Donations

* Financial donations must be associated with a support request.
* Certificates are generated only for successful completed donations.

### Aftercare

* Aftercare is available only to donors who have completed an actual donation.
* The aftercare plan depends on the donation type.

---

# 📊 Business Analysis & Documentation

The project was analysed from both a **business and system perspective**.

Documentation includes:

* Stakeholder analysis
* Business requirements
* Functional requirements
* Non-functional requirements
* Business rules
* Business logic
* User stories
* Acceptance criteria
* Use cases
* Activity diagrams
* Process flows
* Testing and UAT

Detailed documentation is available in the [`docs/`](docs/) directory.

---

# 🔄 Major Business Processes

## Organ Donation Lifecycle

```text
Registration
     ↓
Role Selection
     ↓
Consent Submission
     ↓
Signed Consent Upload
     ↓
Administrator Approval
     ↓
Active Consent
     ↓
Hospital Organ Request
     ↓
Donor Matching
     ↓
Donor Notification
     ↓
Donor Accepts Request
     ↓
Appointments & Medical Tests
     ↓
Donation
     ↓
Donation Completed
     ↓
Certificate & Aftercare
```

## After-Death Donation Lifecycle

```text
Organ Donor
     ↓
After-Death Consent
     ↓
Witness Details
     ↓
Custodian Details
     ↓
Generate PDF
     ↓
Obtain Signatures
     ↓
Upload Signed Consent
     ↓
Administrator Review
     ↓
Approval
     ↓
Active Consent
```

## Full-Body Donation Lifecycle

```text
Organ Donor
     ↓
Full-Body Donation
     ↓
Select Medical School
     ↓
Witness Details
     ↓
Custodian Details
     ↓
Generate PDF
     ↓
Obtain Signatures
     ↓
Upload Signed Consent
     ↓
Administrator Review
     ↓
Approval
     ↓
Active Consent
```

## Financial Donation Lifecycle

```text
Financial Donor
     ↓
View Support Requests
     ↓
Select Support Request
     ↓
Make Payment
     ↓
Payment Confirmation
     ↓
Donation History
     ↓
Appreciation Certificate
```

---

# 🧩 Business Analysis Artifacts

The repository contains documentation demonstrating the analysis and design process:

```text
docs/
│
├── 01_Project_Overview.md
├── 02_Business_Analysis.md
├── 03_Stakeholders.md
├── 04_Requirements.md
├── 05_Business_Rules.md
├── 06_User_Stories.md
├── 07_Use_Cases.md
├── 08_Process_Flows.md
├── 09_Activity_Diagrams.md
└── 10_Testing_UAT.md
```

Visual diagrams are maintained separately:

```text
diagrams/
```

Application screenshots are maintained in:

```text
screenshots/
```

---

# 🛠️ Technology Stack

* **Backend:** PHP 7.4+
* **Database:** MySQL 5.7+
* **Frontend:** HTML5, CSS3, JavaScript (ES6)
* **Styling:** Custom CSS with responsive design
* **Server:** Apache / XAMPP
* **Version Control:** Git / GitHub

---

# 📁 Project Structure

```text
life-connect/
│
├── app/
│   ├── controllers/          # PHP Controllers
│   ├── models/               # Data Models
│   ├── views/                # View Templates
│   └── core/                 # Core Framework Files
│
├── public/
│   ├── assets/
│   │   ├── css/              # Stylesheets
│   │   ├── js/               # JavaScript
│   │   └── images/           # Static Images
│   │
│   └── index.php             # Application Entry Point
│
├── docs/                     # Business & System Documentation
├── diagrams/                 # System & Process Diagrams
├── screenshots/              # Application Screenshots
├── .gitignore
└── README.md
```

---

# 🗄️ Database

The application uses MySQL for persistent data storage.

Major data areas include:

* Donor information
* User roles
* Donation consents
* Organ requests
* Hospital information
* Recipient information
* Appointments
* Medical test results
* Lab reports
* Donation records
* Financial donations
* Support requests
* Certificates
* Aftercare records

---

# 🔐 Security & Data Protection

The system includes security mechanisms such as:

* Password hashing
* Prepared SQL statements
* Input validation and sanitization
* XSS protection
* CSRF protection
* Secure file upload validation
* Role-based access control
* Restricted access to personal and medical information

# 📚 Learning Outcomes

Through this project, I gained practical experience in:

* Business and requirements analysis
* Stakeholder identification
* Business rule definition
* Business process modelling
* User story development
* Use case modelling
* UML activity diagrams
* Requirements-to-feature mapping
* Role-based access control
* Database-driven application development
* Functional testing
* User acceptance testing
* Git and GitHub collaboration
* Translating business requirements into system functionality

---

# 🎓 Project Information

**Project:** LifeConnect — Organ Donation & Healthcare Coordination Platform

**Institution:** University of Colombo School of Computing

**Program:** Information Systems

**Type:** Academic Group Project

**Status:** Completed Academic Project
