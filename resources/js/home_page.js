class UltraModernSlider {
    constructor() {
        this.currentSlide = 0;
        this.totalSlides = 3;
        this.autoplayInterval = null;
        this.slideInterval = 6000;
        this.isPlaying = true;
        this.isDragging = false;
        this.startX = 0;
        this.currentX = 0;

        this.init();
    }

    init() {
        this.bindEvents();
        this.startAutoplay();
        this.updateSlideCounter();
    }

    bindEvents() {
        // Navigation arrows
        document.querySelector('.prev-arrow').addEventListener('click', () => this.prevSlide());
        document.querySelector('.next-arrow').addEventListener('click', () => this.nextSlide());

        // Pagination dots
        document.querySelectorAll('.dot').forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Side navigation
        document.querySelectorAll('.side-nav-dot').forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
        });

        // Only bind drag events to the slider content area, not the entire container
        const slidesWrapper = document.querySelector('.slides-wrapper');

        // Mouse events
        slidesWrapper.addEventListener('mousedown', (e) => this.handleDragStart(e));
        document.addEventListener('mousemove', (e) => this.handleDragMove(e));
        document.addEventListener('mouseup', () => this.handleDragEnd());

        // Touch events - only horizontal dragging
        slidesWrapper.addEventListener('touchstart', (e) => this.handleDragStart(e), {passive: true});
        slidesWrapper.addEventListener('touchmove', (e) => {
            if (this.isDragging) {
                const touch = e.touches[0];
                const deltaX = Math.abs(touch.clientX - this.startX);
                const deltaY = Math.abs(touch.clientY - this.startY);

                // Only handle horizontal swipes
                if (deltaX > deltaY && deltaX > 10) {
                    e.preventDefault();
                    this.handleDragMove(e);
                }
            }
        }, {passive: false});
        slidesWrapper.addEventListener('touchend', () => this.handleDragEnd(), {passive: true});

        // Pause on hover - only on slider area
        slidesWrapper.addEventListener('mouseenter', () => this.pauseAutoplay());
        slidesWrapper.addEventListener('mouseleave', () => this.resumeAutoplay());
    }

    handleDragStart(e) {
        this.isDragging = true;
        this.startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
        this.startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
        this.pauseAutoplay();
    }

    handleDragMove(e) {
        if (!this.isDragging) return;
        e.preventDefault();
        this.currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
    }

    handleDragEnd() {
        if (!this.isDragging) return;
        this.isDragging = false;

        const deltaX = this.currentX - this.startX;
        const threshold = 50;

        if (Math.abs(deltaX) > threshold) {
            if (deltaX > 0) {
                this.prevSlide();
            } else {
                this.nextSlide();
            }
        }

        this.resumeAutoplay();
    }

    goToSlide(index) {
        if (index === this.currentSlide) return;

        this.currentSlide = index;
        this.updateSlider();
        this.restartAutoplay();
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.updateSlider();
        this.restartAutoplay();
    }

    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.updateSlider();
        this.restartAutoplay();
    }

    updateSlider() {
        const slides = document.querySelectorAll('.slide');
        const slidesWrapper = document.querySelector('.slides-wrapper');

        // Update slide positions
        slidesWrapper.style.transform = `translateX(-${this.currentSlide * 100}%)`;

        // Update active states
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === this.currentSlide);
        });

        // Update navigation dots
        document.querySelectorAll('.dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });

        // Update side navigation
        document.querySelectorAll('.side-nav-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });

        // Update slide counter
        this.updateSlideCounter();

        // Restart progress bar
        this.startProgressBar();
    }

    updateSlideCounter() {
        const currentSlideEl = document.querySelector('.current-slide');
        currentSlideEl.textContent = String(this.currentSlide + 1).padStart(2, '0');
    }

    startProgressBar() {
        const progressFill = document.querySelector('.progress-fill');
        progressFill.style.transition = 'none';
        progressFill.style.width = '0%';

        // Force reflow
        progressFill.offsetHeight;

        progressFill.style.transition = `width ${this.slideInterval}ms linear`;
        progressFill.style.width = '100%';
    }

    startAutoplay() {
        if (this.autoplayInterval) return;

        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, this.slideInterval);

        this.startProgressBar();
    }

    pauseAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }

        // Pause progress bar
        const progressFill = document.querySelector('.progress-fill');
        const currentWidth = getComputedStyle(progressFill).width;
        progressFill.style.transition = 'none';
        progressFill.style.width = currentWidth;
    }

    resumeAutoplay() {
        if (!this.autoplayInterval) {
            this.startAutoplay();
        }
    }

    restartAutoplay() {
        this.pauseAutoplay();
        this.startAutoplay();
    }
}

// Initialize slider when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new UltraModernSlider();
});

// Add some interactive particles on mouse move
document.addEventListener('mousemove', (e) => {
    const particles = document.querySelectorAll('.particle');
    const mouseX = e.clientX / window.innerWidth;
    const mouseY = e.clientY / window.innerHeight;

    particles.forEach((particle, index) => {
        const speed = (index % 3 + 1) * 0.5;
        const x = mouseX * 20 * speed;
        const y = mouseY * 20 * speed;

        particle.style.transform = `translate(${x}px, ${y}px)`;
    });
});

// Add scroll-based interactions (if needed for future enhancements)
let ticking = false;

function updateParallax() {
    const slides = document.querySelectorAll('.slide.active');
    slides.forEach(slide => {
        const bg = slide.querySelector('.slide-bg');
        if (bg) {
            // Subtle parallax effect on mouse movement
            const rect = slide.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            // This could be enhanced with actual mouse position tracking
            bg.style.transform = `scale(1.05) translate(0px, 0px)`;
        }
    });
    ticking = false;
}

// Intersection Observer for performance optimization
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const slideObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
        } else {
            entry.target.classList.remove('in-view');
        }
    });
}, observerOptions);

// Observe all slides for performance
document.querySelectorAll('.slide').forEach(slide => {
    slideObserver.observe(slide);
});

