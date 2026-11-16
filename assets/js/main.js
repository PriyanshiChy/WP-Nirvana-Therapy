/**
 * Main JS for Nirvana Painted theme.
 *
 * Add lightweight interactivity here (e.g., FAQ accordion toggles).
 */

const prefersReducedMotion = () =>
  window.matchMedia("(prefers-reduced-motion: reduce)").matches;

const scrollBehavior = () => (prefersReducedMotion() ? "auto" : "smooth");

const handled = (fn) => {
  try {
    fn();
  } catch (error) {
    console.error("Error in main.js:", error);
  }
};

const closeAnswer = (trigger) => {
  trigger.setAttribute("aria-expanded", "false");
  const answer = trigger.nextElementSibling;
  answer.style.maxHeight = "0";
  answer.inert = true;
};

const openAnswer = (trigger) => {
  trigger.setAttribute("aria-expanded", "true");
  const answer = trigger.nextElementSibling;
  answer.inert = false;
  answer.style.maxHeight = answer.scrollHeight + "px";
};

const registerFAQ = () => {
  document.querySelectorAll(".faq-trigger").forEach((trigger) => {
    trigger.addEventListener("click", () => {
      const isOpen = trigger.getAttribute("aria-expanded") === "true";

      document
        .querySelectorAll('.faq-trigger[aria-expanded="true"]')
        .forEach((openTrigger) => {
          if (openTrigger !== trigger) closeAnswer(openTrigger);
        });

      isOpen ? closeAnswer(trigger) : openAnswer(trigger);
    });
  });
};

const registerCarousel = () => {
  const carousel = document.querySelector(".testimonial-carousel");
  if (!carousel) return; // Not every page has testimonials.

  const prevButtons = document.querySelectorAll(".testimonial-carousel-previous");
  const nextButtons = document.querySelectorAll(".testimonial-carousel-next");
  const cards = Array.from(carousel.querySelectorAll(":scope > div"));

  if (!cards.length) return;

  let scrolling = false;

  const getStep = () => {
    const cardWidth = cards[0].getBoundingClientRect().width;
    const gap = cards.length > 1
      ? cards[1].getBoundingClientRect().left - cards[0].getBoundingClientRect().right
      : 0;
    return cardWidth + gap;
  };

  // Bounds come from the real scroll position, so the arrows stay in sync
  // when the last cards are already in view or the viewport is resized.
  const atStart = () => carousel.scrollLeft <= 1;
  const atEnd = () =>
    carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 1;

  const updateButtons = () => {
    prevButtons.forEach((b) => b.setAttribute("aria-disabled", String(atStart())));
    nextButtons.forEach((b) => b.setAttribute("aria-disabled", String(atEnd())));
  };

  const centerCard = (card) => {
    const carouselRect = carousel.getBoundingClientRect();
    const cardRect = card.getBoundingClientRect();
    const targetLeft = carousel.scrollLeft + cardRect.left - carouselRect.left
      - (carousel.clientWidth - card.clientWidth) / 2;
    carousel.scrollTo({ left: Math.max(0, targetLeft), behavior: scrollBehavior() });
  };

  const lock = () => {
    scrolling = true;
    setTimeout(() => { scrolling = false; }, 500);
  };

  // Center first card on load.
  centerCard(cards[0]);

  prevButtons.forEach((button) => {
    button.addEventListener("click", () => {
      if (scrolling || atStart()) return;
      carousel.scrollBy({ left: -getStep(), behavior: scrollBehavior() });
      lock();
    });
  });

  nextButtons.forEach((button) => {
    button.addEventListener("click", () => {
      if (scrolling || atEnd()) return;
      carousel.scrollBy({ left: getStep(), behavior: scrollBehavior() });
      lock();
    });
  });

  carousel.addEventListener("scroll", updateButtons, { passive: true });
  window.addEventListener("resize", updateButtons);
  updateButtons();
};

const scrollToHash = (hash) => {
  const target = document.querySelector(hash);
  if (!target) return;

  const headerBar =
    document.querySelector("#header-bar") ?? document.querySelector("header");
  const offset = headerBar ? headerBar.getBoundingClientRect().height : 120;
  const top = target.getBoundingClientRect().top + window.scrollY - offset;

  window.scrollTo({ top, behavior: scrollBehavior() });
};

// Move focus to the jump target so keyboard / screen-reader users continue
// from there (e.g. the "Skip to content" link). tabindex="-1" makes any
// element focusable without adding it to the Tab order.
const focusTarget = (target) => {
  if (!target.hasAttribute("tabindex")) target.setAttribute("tabindex", "-1");
  target.focus({ preventScroll: true });
};

const registerSmoothScroll = () => {
  // On page load, correct the scroll position if a hash is present
  if (window.location.hash) {
    // Use requestAnimationFrame to run after the browser's default anchor scroll
    requestAnimationFrame(() => scrollToHash(window.location.hash));
  }

  document.querySelectorAll('a[href*="#"]').forEach((anchor) => {
    anchor.addEventListener("click", (e) => {
      const url = new URL(anchor.href, window.location.href);
      if (!url.hash) return;

      // Same-page: prevent default and scroll with offset
      if (url.pathname === window.location.pathname) {
        const target = document.querySelector(url.hash);
        if (!target) return;
        e.preventDefault();
        history.pushState(null, "", url.hash);
        scrollToHash(url.hash);
        focusTarget(target);
      }
      // Cross-page: let the browser navigate; the load handler above will correct the offset
    });
  });
};

const registerCountUp = () => {
  const elements = document.querySelectorAll(".stat-count");
  if (!elements.length || prefersReducedMotion()) return;

  const animate = (el) => {
    const target = parseInt(el.dataset.target, 10);
    const suffix = el.dataset.suffix || "";
    const duration = 1000;
    const start = performance.now();

    const step = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const eased = -(Math.cos(Math.PI * progress) - 1) / 2;
      el.textContent = Math.floor(eased * target) + suffix;
      if (progress < 1) requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animate(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 },
  );

  elements.forEach((el) => observer.observe(el));
};

const registerMobileMenu = () => {
  const menu = document.querySelector("#mobile-menu");
  const hamburger = document.querySelector("#header-hamburger");

  if (!menu || !hamburger) return;

  const openLabel = hamburger.getAttribute("aria-label") || "Open menu";
  const closeLabel = hamburger.dataset.closeLabel || "Close menu";
  const isOpen = () => hamburger.getAttribute("aria-expanded") === "true";

  const open = () => {
    hamburger.setAttribute("aria-expanded", "true");
    hamburger.setAttribute("aria-label", closeLabel);
    menu.removeAttribute("aria-hidden");
    document.body.style.overflow = "hidden";
  };

  const dismiss = ({ returnFocus = false } = {}) => {
    hamburger.setAttribute("aria-expanded", "false");
    hamburger.setAttribute("aria-label", openLabel);
    menu.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    if (returnFocus) hamburger.focus();
  };

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && isOpen()) dismiss({ returnFocus: true });
  });

  const toggle = () => {
    isOpen() ? dismiss() : open();
  };

  hamburger.addEventListener("click", toggle);

  menu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 1024) dismiss();
    });
  });
};

const registerSectionFadeIn = () => {
  const sections = document.querySelectorAll("section");
  if (!("IntersectionObserver" in window)) {
    sections.forEach((section) => section.classList.add("visible"));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 },
  );

  sections.forEach((section) => observer.observe(section));
};

const showWatermark = () => {
  console.log(
    `---- Watermark ----\nNirvana Painted Theme\nDesigned by ✨ Pchy Designs ✨ \n(Priyanshi Choudhury)\nhttps://pchy.design\nReach out for custom Websites, design work, or just to say hi at priyanshichoudhury.work@gmail.com\n-------------------`,
  );
};

window.nirvanaReady = true; // Tells the <head> fail-safe that main.js loaded.

showWatermark();

handled(registerCarousel);
handled(registerFAQ);
handled(registerSmoothScroll);
handled(registerCountUp);
handled(registerMobileMenu);
handled(registerSectionFadeIn);
