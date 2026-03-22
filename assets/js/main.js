/**
 * Main JS for Nirvana Painted theme.
 *
 * Add lightweight interactivity here (e.g., FAQ accordion toggles).
 */

const closeAnswer = (trigger) => {
  trigger.setAttribute("aria-expanded", "false");
  trigger.nextElementSibling.style.maxHeight = "0";
};

const openAnswer = (trigger) => {
  trigger.setAttribute("aria-expanded", "true");
  const answer = trigger.nextElementSibling;
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
  const prevButton = document.querySelectorAll(
    ".testimonial-carousel-previous",
  );
  const nextButton = document.querySelectorAll(".testimonial-carousel-next");

  const card = document.querySelector(".testimonial-carousel > div");
  const gap = 48;
  const offset = card.clientWidth + gap;

  carousel.scrollLeft = 0;

  let scrolling = false;

  carousel.addEventListener("scrollend", () => {
    scrolling = false;
  });

  prevButton.forEach((button) => {
    button.addEventListener("click", () => {
      if (scrolling || carousel.scrollLeft === 0) return;
      scrolling = true;
      carousel.scrollBy({ left: -offset, behavior: "smooth" });
    });
  });

  nextButton.forEach((button) => {
    button.addEventListener("click", () => {
      if (
        scrolling ||
        carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth
      )
        return;
      scrolling = true;
      carousel.scrollBy({ left: offset, behavior: "smooth" });
    });
  });
};

const scrollToHash = (hash) => {
  const target = document.querySelector(hash);
  if (!target) return;

  const header = document.querySelector("header");
  const offset = header ? header.getBoundingClientRect().height : 120;
  const top = target.getBoundingClientRect().top + window.scrollY - offset;

  window.scrollTo({ top, behavior: "smooth" });
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
      }
      // Cross-page: let the browser navigate; the load handler above will correct the offset
    });
  });
};

const registerCountUp = () => {
  const elements = document.querySelectorAll(".stat-count");

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

  const open = () => {
    hamburger.setAttribute("aria-expanded", "true");
    menu.removeAttribute("aria-hidden");
    document.body.style.overflow = "hidden";
  };

  const dismiss = () => {
    hamburger.setAttribute("aria-expanded", "false");
    menu.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  };

  const toggle = () => {
    hamburger.getAttribute("aria-expanded") === "true" ? dismiss() : open();
  };

  hamburger.addEventListener("click", toggle);
};

const registerSectionFadeIn = () => {
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

  document.querySelectorAll("section").forEach((section) => {
    observer.observe(section);
  });
};

registerCarousel();
registerFAQ();
registerSmoothScroll();
registerCountUp();
registerMobileMenu();
registerSectionFadeIn();
