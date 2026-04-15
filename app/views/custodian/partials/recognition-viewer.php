<?php
/**
 * Recognition Viewer Modal Partial
 * Premium "Split View" document viewer for Certificates & Letters.
 * Externalized to prevent PHP variable collision and simplify inclusion.
 */
?>

<!-- Document Modal HTML -->
<div id="docModalBackdrop" class="doc-modal-backdrop" onclick="if(event.target === this) closeDocumentModal()">
    <div class="doc-modal-container">
        <div class="doc-modal-header">
            <div class="doc-modal-title" id="docModalTitle">
                <i class="fas fa-award text-primary"></i>
                Recognition Documents
            </div>
            <div class="doc-modal-actions">
                <button onclick="printIframeDoc()" class="btn-modal-print">
                    <i class="fas fa-print"></i> Print / Download
                </button>
                <button onclick="closeDocumentModal()" class="btn-modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="doc-modal-body">
            <div class="doc-modal-sidebar" id="docSidebar">
                <div class="sidebar-section-title">Available Documents</div>
                <div id="sidebarTabs"></div>
            </div>
            <div class="doc-modal-viewer">
                <iframe id="docIframe" class="doc-modal-iframe"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Main Modal Launcher for Recognition Documents
 */
function openRecognitionBundle(certUrl, letterUrl) {
    const backdrop = document.getElementById('docModalBackdrop');
    const sidebar = document.getElementById('docSidebar');
    const tabsContainer = document.getElementById('sidebarTabs');
    const titleEl = document.getElementById('docModalTitle');
    
    titleEl.innerHTML = `<i class="fas fa-award text-primary"></i> Recognition Bundle`;
    tabsContainer.innerHTML = '';
    
    // Setup Sidebar Tabs
    const docs = [];
    if (certUrl && certUrl !== '') docs.push({ name: 'Donation Certificate', url: certUrl, icon: 'fa-award' });
    if (letterUrl && letterUrl !== '') docs.push({ name: 'Appreciation Letter', url: letterUrl, icon: 'fa-envelope-open-text' });

    docs.forEach((doc, idx) => {
        const tab = document.createElement('div');
        tab.className = `doc-tab ${idx === 0 ? 'active' : ''}`;
        tab.innerHTML = `<i class="fas ${doc.icon}"></i> ${doc.name}`;
        tab.onclick = () => switchDocTab(doc.url, doc.name, tab);
        tabsContainer.appendChild(tab);
    });

    if (docs.length > 1) {
        sidebar.classList.add('active');
    } else {
        sidebar.classList.remove('active');
    }

    // Set first doc
    if (docs.length > 0) {
        document.getElementById('docIframe').src = docs[0].url;
    }

    backdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/**
 * Standard Document Modal Launcher (Single Doc)
 */
function openDocumentModal(url, title) {
    const backdrop = document.getElementById('docModalBackdrop');
    const iframe = document.getElementById('docIframe');
    const titleEl = document.getElementById('docModalTitle');
    const sidebar = document.getElementById('docSidebar');
    
    sidebar.classList.remove('active');
    if (title) titleEl.innerHTML = `<i class="fas fa-file-alt text-primary"></i> ${title}`;
    
    iframe.src = url;
    backdrop.classList.add('active');
    document.body.style.overflow = 'hidden'; 
}

function switchDocTab(url, title, tabEl) {
    const iframe = document.getElementById('docIframe');
    document.querySelectorAll('.doc-tab').forEach(t => t.classList.remove('active'));
    tabEl.classList.add('active');
    iframe.src = url;
}

function closeDocumentModal() {
    const backdrop = document.getElementById('docModalBackdrop');
    backdrop.classList.remove('active');
    document.body.style.overflow = 'auto';
    
    setTimeout(() => {
        document.getElementById('docIframe').src = '';
    }, 300);
}

function printIframeDoc() {
    const iframe = document.getElementById('docIframe');
    if (iframe.contentWindow) {
        iframe.contentWindow.print();
    }
}
</script>
