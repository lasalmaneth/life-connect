<header class="glass-nav">
    <div id="top-links">
        <div class="top-nav-container">
            <div class="contact-info">
                <a href="tel:+94112345678"><i class="fa-solid fa-phone"></i> +94 11 234 5678</a>
                <a href="mailto:support@lifeconnect.gov.lk"><i class="fa-solid fa-envelope"></i> support@lifeconnect.gov.lk</a>
            </div>
            <div class="auth-links">
                <?php if(isset($_SESSION['username'])): ?>
                    <span class="user-welcome">Welcome, <strong><?= esc($_SESSION['username']) ?></strong></span>
                    <a href="<?= ROOT ?>/logout" class="auth-btn logout-btn">Log out</a>
                <?php else: ?>
                    <a href="<?= ROOT ?>/login" class="auth-btn login-btn">Log in</a>
                    <a href="<?= ROOT ?>/signup" class="auth-btn signup-btn">Sign up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="main-header-content">
        <a href="<?= ROOT ?>/home" class="logo-link">
            <div id="logo">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="Life Connect Logo">
                <div class="name">
                    Life Connect
                    <span>Ministry of Health Sri Lanka</span>
                </div>
            </div>
        </a>

        <nav class="desktop-nav">
            <a href="<?= ROOT ?>/home" class="nav-item">Home</a>
            <a href="<?= ROOT ?>/stats" class="nav-item">Stats</a>
            <a href="<?= ROOT ?>/education" class="nav-item">Education</a>
            <a href="<?= ROOT ?>/legal" class="nav-item">Legal</a>
            <a href="<?= ROOT ?>/tributes" class="nav-item">Tributes</a>
            <a href="<?= ROOT ?>/signup" class="btn-premium nav-cta">Become a Donor</a>
        </nav>

        <div class="header-actions">
            <div class="search-bar glass">
                <input type="text" placeholder="Search..." id="searchInput">
                <button id="searchBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <button class="mobile-toggle"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</header>

<style>
/* Navigation buttons for search - minimal styling to match existing design */
#prevBtn, #nextBtn {
    background: var(--white-color);
    color: var(--secondary-color);
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    font-size: 12px;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
    margin-left: 5px;
}
#prevBtn:hover, #nextBtn:hover {
    box-shadow: 0 0 5px 1px rgba(255, 255, 255, 0.4);
}
.search-bar:hover #prevBtn,
.search-bar:hover #nextBtn {
    display: flex;
}
/* Highlight styles - matches site theme */
mark.search-highlight {
    background-color: #d0e4ff; /* Light blue matching site theme */
    color: var(--secondary-color);
    padding: 2px 4px;
    border-radius: 3px;
}
mark.search-highlight.active {
    background-color: var(--primary-color); /* Site primary blue */
    color: #fff;
    font-weight: bold;
    padding: 3px 5px;
}
</style>

<script>
(function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    let highlights = [];
    let currentIndex = -1;

    // Remove all highlights
    function clearHighlights() {
        const marks = document.querySelectorAll('mark.search-highlight');
        marks.forEach(mark => {
            const parent = mark.parentNode;
            parent.replaceChild(document.createTextNode(mark.textContent), mark);
            parent.normalize();
        });
        highlights = [];
        currentIndex = -1;
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }

    // Highlight text in element
    function highlightText(element, searchText) {
        // Skip script, style, input, textarea tags
        const skipTags = ['SCRIPT', 'STYLE', 'INPUT', 'TEXTAREA', 'NOSCRIPT'];
        if (skipTags.includes(element.tagName)) return;

        // Process text nodes
        if (element.nodeType === 3) { // Text node
            const text = element.nodeValue;
            const regex = new RegExp('(' + searchText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
            
            if (regex.test(text)) {
                const fragment = document.createDocumentFragment();
                let lastIndex = 0;
                
                text.replace(regex, function(match, p1, offset) {
                    // Add text before match
                    if (offset > lastIndex) {
                        fragment.appendChild(document.createTextNode(text.substring(lastIndex, offset)));
                    }
                    
                    // Add highlighted match
                    const mark = document.createElement('mark');
                    mark.className = 'search-highlight';
                    mark.textContent = match;
                    fragment.appendChild(mark);
                    highlights.push(mark);
                    
                    lastIndex = offset + match.length;
                });
                
                // Add remaining text
                if (lastIndex < text.length) {
                    fragment.appendChild(document.createTextNode(text.substring(lastIndex)));
                }
                
                element.parentNode.replaceChild(fragment, element);
            }
        } else if (element.nodeType === 1 && element.childNodes && !skipTags.includes(element.tagName)) {
            // Element node - process children
            Array.from(element.childNodes).forEach(child => highlightText(child, searchText));
        }
    }

    // Navigate to specific highlight
    function goToHighlight(index) {
        if (highlights.length === 0) return;
        
        // Remove active class from all
        highlights.forEach(mark => mark.classList.remove('active'));
        
        // Wrap index
        if (index < 0) index = highlights.length - 1;
        if (index >= highlights.length) index = 0;
        
        currentIndex = index;
        
        // Add active class and scroll into view
        const activeHighlight = highlights[currentIndex];
        activeHighlight.classList.add('active');
        activeHighlight.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Search on input
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Clear previous highlights
        clearHighlights();
        
        if (query.length < 2) return;
        
        // Highlight all matches in body
        highlightText(document.body, query);
        
        // Show navigation buttons if matches found
        if (highlights.length > 0) {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
            goToHighlight(0);
        }
    });

    // Previous button
    prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (highlights.length > 0) {
            goToHighlight(currentIndex - 1);
        }
    });

    // Next button
    nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (highlights.length > 0) {
            goToHighlight(currentIndex + 1);
        }
    });

    // Focus input when clicking search button
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        searchInput.focus();
    });

    // Clear on escape key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            clearHighlights();
        }
    });
})();
</script>
