// Carousel functionality - ĐÃ SỬA: Thêm infinite loop mượt mà
let currentIndex = 0;
let slides, dots, track;
let isTransitioning = false;

document.addEventListener("DOMContentLoaded", function () {
  slides = document.querySelectorAll(".carousel-slide");
  dots = document.querySelectorAll(".dot");
  track = document.querySelector(".carousel-track");

  showSlide(currentIndex, false);

  // Auto slide every 4 seconds
  setInterval(() => {
    moveSlide(1);
  }, 2000);
});

function showSlide(index, animate = true) {
  if (!track) return;

  // ĐÃ SỬA: Xử lý infinite loop
  if (index >= slides.length) {
    currentIndex = 0;
  } else if (index < 0) {
    currentIndex = slides.length - 1;
  } else {
    currentIndex = index;
  }

  // Move track
  if (animate) {
    track.style.transition =
      "transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)";
  } else {
    track.style.transition = "none";
  }

  track.style.transform = `translateX(-${currentIndex * 100}%)`;

  // Update dots
  dots.forEach((dot, i) => {
    dot.classList.toggle("active", i === currentIndex);
  });
}

function moveSlide(direction) {
  if (isTransitioning) return;
  isTransitioning = true;

  // ĐÃ SỬA: Logic chuyển slide mượt mà
  let nextIndex = currentIndex + direction;

  showSlide(nextIndex, true);

  setTimeout(() => {
    isTransitioning = false;
  }, 800);
}

function currentSlide(index) {
  if (isTransitioning) return;
  currentIndex = index;
  showSlide(currentIndex, true);
}
