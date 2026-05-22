(function () {
  'use strict';

  class VideoWallSlider {
    constructor(container) {
      this.container = container;
      this.scroller = container.querySelector('.vws-scroller');
      this.slides = container.querySelectorAll('.vws-slide');
      this.wallId = container.getAttribute('data-wall-id');
      this.autoplay = parseInt(container.getAttribute('data-autoplay'));
      this.mute = parseInt(container.getAttribute('data-mute'));
      this.loop = parseInt(container.getAttribute('data-loop'));
      this.lazy = parseInt(container.getAttribute('data-lazy'));

      this.players = new Map();
      this.velocityY = 0;
      this.isGrabbing = false;
      this.lastY = 0;
      this.touchStartY = 0;
      this.inertiaMomentum = 0;
      this.decelerationRate = 0.95;
      this.minVelocity = 0.5;

      this.init();
    }

    init() {
      this.bindEvents();
      this.initializeVideos();
      this.setupIntersectionObserver();
    }

    bindEvents() {
      // Mouse events
      this.scroller.addEventListener('mousedown', (e) => this.handleMouseDown(e));
      document.addEventListener('mousemove', (e) => this.handleMouseMove(e));
      document.addEventListener('mouseup', () => this.handleMouseUp());

      // Touch events
      this.scroller.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
      this.scroller.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: true });
      this.scroller.addEventListener('touchend', () => this.handleTouchEnd(), { passive: true });

      // Scroll event
      this.scroller.addEventListener('scroll', () => this.updateVideoPlayback());
    }

    handleMouseDown(e) {
      if (e.button !== 0) return; // Left click only
      this.isGrabbing = true;
      this.lastY = e.clientY;
      this.velocityY = 0;
      this.scroller.classList.add('is-dragging');
      this.container.style.cursor = 'grabbing';
    }

    handleMouseMove(e) {
      if (!this.isGrabbing) return;
      const deltaY = this.lastY - e.clientY;
      this.velocityY = deltaY;
      this.scroller.scrollTop += deltaY;
      this.lastY = e.clientY;
    }

    handleMouseUp() {
      if (!this.isGrabbing) return;
      this.isGrabbing = false;
      this.scroller.classList.remove('is-dragging');
      this.container.style.cursor = 'grab';
      this.applyInertia();
    }

    handleTouchStart(e) {
      this.touchStartY = e.touches[0].clientY;
      this.velocityY = 0;
      this.lastY = this.touchStartY;
    }

    handleTouchMove(e) {
      const currentY = e.touches[0].clientY;
      const deltaY = this.lastY - currentY;
      this.velocityY = deltaY;
      this.scroller.scrollTop += deltaY;
      this.lastY = currentY;
    }

    handleTouchEnd() {
      this.applyInertia();
    }

    applyInertia() {
      const momentum = () => {
        if (Math.abs(this.velocityY) > this.minVelocity) {
          this.scroller.scrollTop += this.velocityY;
          this.velocityY *= this.decelerationRate;
          requestAnimationFrame(momentum);
        }
      };
      momentum();
    }

    initializeVideos() {
      this.slides.forEach((slide, index) => {
        const videoId = slide.getAttribute('data-video-id');
        if (!videoId) return;

        const iframe = slide.querySelector('.vws-video-iframe');
        if (!iframe) return;

        // Add a small delay to ensure YouTube API is ready
        setTimeout(() => {
          this.createYouTubePlayer(videoId, iframe, index);
        }, 100 * (index + 1));
      });
    }

    createYouTubePlayer(videoId, iframe, index) {
      if (typeof YT === 'undefined' || !YT.Player) {
        setTimeout(() => this.createYouTubePlayer(videoId, iframe, index), 100);
        return;
      }

      const player = new YT.Player(iframe, {
        events: {
          onReady: (event) => this.onPlayerReady(event, index),
          onStateChange: (event) => this.onPlayerStateChange(event, index),
        },
      });

      this.players.set(index, player);
    }

    onPlayerReady(event, index) {
      const player = event.target;

      if (this.autoplay) {
        player.playVideo();
      }

      if (this.mute) {
        player.mute();
      }

      if (this.loop) {
        player.setLoop(true);
      }
    }

    onPlayerStateChange(event, index) {
      // Handle video state changes if needed
    }

    setupIntersectionObserver() {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            const slide = entry.target;
            const index = Array.from(this.slides).indexOf(slide);
            const player = this.players.get(index);

            if (!player) return;

            if (entry.isIntersecting) {
              if (this.autoplay) {
                player.playVideo();
              }
            } else {
              player.pauseVideo();
            }
          });
        },
        {
          threshold: 0.5,
        }
      );

      this.slides.forEach((slide) => observer.observe(slide));
    }

    updateVideoPlayback() {
      // Called on scroll
    }
  }

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    const containers = document.querySelectorAll('.vws-container');
    containers.forEach((container) => {
      new VideoWallSlider(container);
    });
  });
})();
