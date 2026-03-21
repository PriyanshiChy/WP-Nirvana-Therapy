/**
 * Main JS for Nirvana Painted theme.
 *
 * Add lightweight interactivity here (e.g., FAQ accordion toggles).
 */

const registerFAQ = () => {
  document.querySelectorAll(".faq-trigger").forEach((trigger) => {
    trigger.addEventListener("click", () => {
      const answer = trigger.nextElementSibling;
      const isOpen = trigger.getAttribute("aria-expanded") === "true";

      // Close all other open items
      document
        .querySelectorAll('.faq-trigger[aria-expanded="true"]')
        .forEach((openTrigger) => {
          if (openTrigger !== trigger) {
            openTrigger.setAttribute("aria-expanded", "false");
            openTrigger.nextElementSibling.hidden = true;
          }
        });

      trigger.setAttribute("aria-expanded", String(!isOpen));
      answer.hidden = isOpen;
    });
  });
};

const registerCarousel = () => {
  const carousel = document.querySelector(".testimonial-carousel");
  const prevButton = document.querySelector(".testimonial-carousel-previous");
  const nextButton = document.querySelector(".testimonial-carousel-next");

  const card = document.querySelector(".testimonial-carousel > div");
  const gap = 48;
  const offset = card.clientWidth + gap;

  carousel.scrollLeft = 0; // Start at the beginning

  // Add navigation buttons here

  prevButton.addEventListener("click", () => {
    carousel.scrollBy({ left: -offset, behavior: "smooth" });
  });

  nextButton.addEventListener("click", () => {
    carousel.scrollBy({ left: offset, behavior: "smooth" });
  });
};

registerCarousel();
registerFAQ();
