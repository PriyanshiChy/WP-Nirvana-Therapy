/**
 * Floating "Back to top" on therapist profiles (single-therapist.php).
 *
 * Shows once the profile's Book button has scrolled up out of view, so a
 * visitor reading the bio can jump straight back to it. The scroll is quick
 * (~400ms, eases out); instant with "reduce motion". Focus then moves to the
 * page's heading, so keyboard users land at the top too.
 */
(() => {
  const button = document.querySelector("[data-back-to-top]");
  const book = document.querySelector(".therapist-book");
  if (!button || !book) return;

  // Visible while the Book button is above the viewport (scrolled past).
  new IntersectionObserver(([entry]) => {
    const past = !entry.isIntersecting && entry.boundingClientRect.bottom < 0;
    button.toggleAttribute("data-visible", past);
  }).observe(book);

  const reduce = window.matchMedia("(prefers-reduced-motion: reduce)");
  const ease = (t) => 1 - (1 - t) ** 3;

  const scrollToTop = () => {
    const html = document.documentElement;
    const start = window.scrollY;
    // The site's smooth scrolling would slow each step down: off while animating.
    const behavior = html.style.scrollBehavior;
    html.style.scrollBehavior = "auto";

    if (reduce.matches || start <= 0) {
      window.scrollTo(0, 0);
      html.style.scrollBehavior = behavior;
      return;
    }

    const duration = Math.min(450, 250 + start / 20);
    const began = performance.now();
    let cancelled = false;
    const cancel = () => (cancelled = true);
    window.addEventListener("wheel", cancel, { once: true, passive: true });
    window.addEventListener("touchstart", cancel, { once: true, passive: true });

    const step = (now) => {
      const t = Math.min(1, (now - began) / duration);
      if (!cancelled) window.scrollTo(0, start * (1 - ease(t)));
      if (t < 1 && !cancelled) {
        requestAnimationFrame(step);
      } else {
        html.style.scrollBehavior = behavior;
        window.removeEventListener("wheel", cancel);
        window.removeEventListener("touchstart", cancel);
      }
    };
    requestAnimationFrame(step);
  };

  button.addEventListener("click", () => {
    scrollToTop();
    const heading = document.querySelector("#main h1");
    if (heading) {
      heading.setAttribute("tabindex", "-1");
      heading.focus({ preventScroll: true });
    }
  });
})();
