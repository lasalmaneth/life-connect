// matching.js - JavaScript for Matching Management

var currentMatchingData = [];

// Initialize matching functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeMatching();
});

// Initialize matching functionality
function initializeMatching() {
    loadMatchingData();
    setupMatchingSearch();
    setupMatchingFilters();
}

// Load matching data from the table
function loadMatchingData() {
    var tableRows = document.querySelectorAll('#matching-table .table-row:not(:first-child)');
    currentMatchingData = [];
    for (var i = 0; i < tableRows.length; i++) {
        var row = tableRows[i];
        currentMatchingData.push({
            matchId: row.getAttribute('data-match-id'),
            donorName: row.children[0].textContent,
            organRequestId: row.children[1].textContent,
            hospitalName: row.children[2].textContent,
            matchDate: row.children[3].textContent,
            status: row.children[4].querySelector('.status-badge').textContent
        });
    }
}

// Setup search functionality for matching
function setupMatchingSearch() {
    var searchInput = document.getElementById('matching-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterMatchingTable();
        });
    }
}

// Setup filter functionality for matching
function setupMatchingFilters() {
    var statusFilter = document.getElementById('matching-status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterMatchingTable();
        });
    }
}

// Filter matching table based on search and filters
function filterMatchingTable() {
    var searchTerm = document.getElementById('matching-search').value.toLowerCase();
    var statusFilter = document.getElementById('matching-status-filter').value;
    
    var tableRows = document.querySelectorAll('#matching-table .table-row:not(:first-child)');
    
    for (var i = 0; i < tableRows.length; i++) {
        var row = tableRows[i];
        var donorName = row.children[0].textContent.toLowerCase();
        var organRequestId = row.children[1].textContent.toLowerCase();
        var hospitalName = row.children[2].textContent.toLowerCase();
        var status = row.children[4].querySelector('.status-badge').textContent;
        
        var matchesSearch = donorName.indexOf(searchTerm) > -1 || 
                           organRequestId.indexOf(searchTerm) > -1 || 
                           hospitalName.indexOf(searchTerm) > -1;
        
        var matchesStatus = statusFilter === '' || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

// View matching details
function viewMatchingDetails(matchId) {
    // Static data that matches our PHP data
    var matchingData = {
        'MAT001': {
            donorId: 'DON001',
            donorName: 'John Smith',
            bloodType: 'O+',
            organRequestId: 'REQ001',
            hospitalName: 'City General Hospital',
            hospitalLocation: '123 Main St, New York, NY',
            hospitalContact: '(555) 123-4567',
            matchDate: '2024-01-15',
            status: 'Pending'
        },
        'MAT002': {
            donorId: 'DON002',
            donorName: 'Sarah Johnson',
            bloodType: 'A-',
            organRequestId: 'REQ002',
            hospitalName: 'Memorial Medical Center',
            hospitalLocation: '456 Oak Ave, Los Angeles, CA',
            hospitalContact: '(555) 234-5678',
            matchDate: '2024-01-18',
            status: 'In Progress'
        },
        'MAT003': {
            donorId: 'DON003',
            donorName: 'Michael Brown',
            bloodType: 'B+',
            organRequestId: 'REQ003',
            hospitalName: 'Unity Health Center',
            hospitalLocation: '789 Pine Rd, Chicago, IL',
            hospitalContact: '(555) 345-6789',
            matchDate: '2024-01-20',
            status: 'Completed'
        },
        'MAT004': {
            donorId: 'DON004',
            donorName: 'Emily Davis',
            bloodType: 'AB+',
            organRequestId: 'REQ004',
            hospitalName: 'Hope Regional Hospital',
            hospitalLocation: '321 Elm St, Houston, TX',
            hospitalContact: '(555) 456-7890',
            matchDate: '2024-01-22',
            status: 'Cancelled'
        },
        'MAT005': {
            donorId: 'DON005',
            donorName: 'Robert Wilson',
            bloodType: 'O-',
            organRequestId: 'REQ005',
            hospitalName: 'LifeCare Medical',
            hospitalLocation: '654 Maple Dr, Phoenix, AZ',
            hospitalContact: '(555) 567-8901',
            matchDate: '2024-01-25',
            status: 'Pending'
        }
    };
    
    var match = matchingData[matchId];
    if (match) {
        document.getElementById('modal-matching-donor-id').textContent = match.donorId;
        document.getElementById('modal-matching-donor-name').textContent = match.donorName;
        document.getElementById('modal-matching-blood-type').textContent = match.bloodType;
        document.getElementById('modal-organ-request-id').textContent = match.organRequestId;
        document.getElementById('modal-hospital-name').textContent = match.hospitalName;
        document.getElementById('modal-hospital-location').textContent = match.hospitalLocation;
        document.getElementById('modal-hospital-contact').textContent = match.hospitalContact;
        document.getElementById('modal-match-date').textContent = match.matchDate;
        
        // Display status as read-only badge instead of dropdown
        var statusDisplay = document.getElementById('modal-matching-status-display');
        statusDisplay.textContent = match.status;
        statusDisplay.className = 'status-badge ' + getStatusClass(match.status);
        
        openMatchingModal();
    }
}

// Open matching modal
function openMatchingModal() {
    document.getElementById('matchingModal').style.display = 'flex';
}

// Close matching modal
function closeMatchingModal() {
    document.getElementById('matchingModal').style.display = 'none';
}

// Get CSS class for status
function getStatusClass(status) {
    switch (status) {
        case 'Pending': return 'status-pending';
        case 'In Progress': return 'status-in-progress';
        case 'Completed': return 'status-completed';
        case 'Cancelled': return 'status-cancelled';
        default: return 'status-pending';
    }
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    var modal = document.getElementById('matchingModal');
    if (event.target === modal) {
        closeMatchingModal();
    }
});