/**
 * Main JS for Nirvana Painted theme.
 *
 * Add lightweight interactivity here (e.g., FAQ accordion toggles).
 */

(function () {
  "use strict";

  const faqToggles = document.querySelectorAll(".nirvana-faq__question");
  if (faqToggles.length) {
    faqToggles.forEach((toggle) => {
      toggle.addEventListener("click", () => {
        const wrapper = toggle.closest(".nirvana-faq__item");
        if (!wrapper) {
          return;
        }
        wrapper.classList.toggle("is-open");
      });
    });
  }
})();
