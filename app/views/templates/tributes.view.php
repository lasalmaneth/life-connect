
<div class="book-container">
  <div class="flipbook" id="flipbook">
    <!-- Cover Page -->
    <div class="page cover">
      <div class="page-content">
        <div class="ornament top-left">❦</div>
        <h1>In Loving Memory</h1>
        <div class="subtitle">A Celebration of Life</div>
        <div class="date-range">1945 — 2024</div>
        <div class="ornament bottom-right">❦</div>
      </div>
    </div>

    <!-- Memorial Page with Photo -->
    <div class="page tribute">
      <div class="page-content">
        <h2>Cherished Memories</h2>
        <div class="photo-frame">
          <div class="photo-placeholder">Photo placeholder<br>300 × 200 pixels</div>
        </div>
        <div class="memory-text">
          A life well-lived leaves an indelible mark on all who were fortunate enough to witness its journey. Through countless acts of kindness, wisdom shared, and love given freely, some souls touch the world in ways that echo through generations. This is a story of such a life—one that brought light to every room, comfort to every heart, and hope to every tomorrow.
        </div>
        <div class="quote">
          Those we love never truly leave us, for they live on in our hearts and memories forever.
        </div>
        <div class="page-number">1</div>
      </div>
    </div>

    <!-- Story Page -->
    <div class="page tribute text-only">
      <div class="page-content">
        <h2>A Beautiful Journey</h2>
        <div class="memory-text">
          Every life tells a unique story, and this one spoke of resilience, compassion, and unwavering hope. From humble beginnings to moments of triumph, from quiet acts of service to bold stands for what was right, this journey was marked by an extraordinary ability to find joy in simple pleasures and to share that joy with others. The legacy left behind is not measured in years, but in the countless lives touched and hearts forever changed.
        </div>
        <div class="quote">
          What we have once enjoyed we can never lose. All that we love deeply becomes a part of us.
          <div class="signature">— Helen Keller</div>
        </div>
        <div class="page-number">2</div>
      </div>
    </div>

    <!-- Family Memories Page -->
    <div class="page tribute">
      <div class="page-content">
        <h2>Family & Friends</h2>
        <div class="photo-frame">
          <div class="photo-placeholder">Family photo<br>300 × 200 pixels</div>
        </div>
        <div class="memory-text">
          The bonds of family and friendship were the cornerstone of a life lived with purpose and meaning. Sunday dinners filled with laughter, bedtime stories that sparked imagination, and quiet conversations that provided comfort during difficult times—these moments created a tapestry of love that will be treasured always. The wisdom shared and lessons taught continue to guide and inspire, ensuring that this beautiful spirit lives on in all who were blessed to call them family or friend.
        </div>
        <div class="quote">
          Family isn't always blood. It's the people in your life who want you in theirs.
        </div>
        <div class="page-number">3</div>
      </div>
    </div>

    <!-- Final Page -->
    <div class="page end">
      <div class="page-content">
        <div class="ornament top-left">❦</div>
        <h2>Forever Remembered</h2>
        <div class="closing-message">
          Though we must say goodbye for now, the love shared and memories created will remain in our hearts always. This is not an ending, but a celebration of a life beautifully lived and deeply cherished.
        </div>
        <div class="final-quote">
          "Until we meet again, may you find peace in the arms of angels and joy in eternal rest."
        </div>
        <div class="ornament bottom-right">❦</div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <button id="prev" class="nav-button hidden"><i class="fa-solid fa-circle-arrow-right fa-rotate-180"></i></button>
  <button id="next" class="nav-button"><i class="fa-solid fa-circle-arrow-right"></i></button>
</div>

<script>
  class TributeFlipbook {
    constructor() {
      this.pages = document.querySelectorAll('.page');
      this.prevBtn = document.getElementById('prev');
      this.nextBtn = document.getElementById('next');
      this.currentPage = 0;
      this.totalPages = this.pages.length;
      this.isAnimating = false;
      
      this.init();
    }
    
    init() {
      this.setupEventListeners();
      this.updateDisplay();
      this.setupKeyboardNavigation();
    }
    
    setupEventListeners() {
      this.nextBtn.addEventListener('click', () => this.nextPage());
      this.prevBtn.addEventListener('click', () => this.prevPage());
      
      // Touch support for mobile
      let startX = null;
      const flipbook = document.getElementById('flipbook');
      
      flipbook.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
      });
      
      flipbook.addEventListener('touchend', (e) => {
        if (!startX) return;
        
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;
        
        if (Math.abs(diff) > 50) { // Minimum swipe distance
          if (diff > 0) {
            this.nextPage();
          } else {
            this.prevPage();
          }
        }
        
        startX = null;
      });
    }
    
    setupKeyboardNavigation() {
      document.addEventListener('keydown', (e) => {
        if (this.isAnimating) return;
        
        switch(e.key) {
          case 'ArrowRight':
          case ' ':
            e.preventDefault();
            this.nextPage();
            break;
          case 'ArrowLeft':
            e.preventDefault();
            this.prevPage();
            break;
          case 'Home':
            e.preventDefault();
            this.goToPage(0);
            break;
          case 'End':
            e.preventDefault();
            this.goToPage(this.totalPages - 1);
            break;
        }
      });
    }
    
    nextPage() {
      if (this.isAnimating || this.currentPage >= this.totalPages - 1) return;
      
      this.isAnimating = true;
      this.currentPage++;
      this.updateDisplay();
      
      setTimeout(() => {
        this.isAnimating = false;
      }, 1200);
    }
    
    prevPage() {
      if (this.isAnimating || this.currentPage <= 0) return;
      
      this.isAnimating = true;
      this.currentPage--;
      this.updateDisplay();
      
      setTimeout(() => {
        this.isAnimating = false;
      }, 1200);
    }
    
    goToPage(pageIndex) {
      if (this.isAnimating || pageIndex < 0 || pageIndex >= this.totalPages) return;
      
      this.isAnimating = true;
      this.currentPage = pageIndex;
      this.updateDisplay();
      
      setTimeout(() => {
        this.isAnimating = false;
      }, 1200);
    }
    
    updateDisplay() {
      // Update page transformations
      this.pages.forEach((page, index) => {
        if (index < this.currentPage) {
          page.classList.add('flipped');
        } else {
          page.classList.remove('flipped');
        }
        
        // Set z-index for proper stacking
        page.style.zIndex = this.totalPages - index;
      });
      
      // Update navigation buttons
      this.prevBtn.classList.toggle('hidden', this.currentPage === 0);
      this.nextBtn.classList.toggle('hidden', this.currentPage === this.totalPages - 1);
      
      // Add subtle animation feedback
      this.addPageTurnEffect();
    }
    
    addPageTurnEffect() {
      const currentPageElement = this.pages[this.currentPage];
      if (currentPageElement) {
        currentPageElement.style.boxShadow = '0 8px 30px rgba(0,0,0,0.2)';
        setTimeout(() => {
          currentPageElement.style.boxShadow = '';
        }, 300);
      }
    }
  }
  
  // Initialize the flipbook when the page loads
  document.addEventListener('DOMContentLoaded', () => {
    new TributeFlipbook();
  });
  
  // Add smooth scroll behavior for better UX
  document.documentElement.style.scrollBehavior = 'smooth';
</script>