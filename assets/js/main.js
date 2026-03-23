/**
 * Main JS for Nirvana Painted theme.
 *
 * Add lightweight interactivity here (e.g., FAQ accordion toggles).
 */

const handled = (fn) => {
  try {
    fn();
  } catch (error) {
    console.error("Error in main.js:", error);
  }
};

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
  const prevButtons = document.querySelectorAll(".testimonial-carousel-previous");
  const nextButtons = document.querySelectorAll(".testimonial-carousel-next");
  const cards = Array.from(carousel.querySelectorAll(":scope > div"));

  if (!cards.length) return;

  let currentIndex = 0;
  let scrolling = false;

  // Calculate step once after layout settles.
  const getStep = () => {
    const cardWidth = cards[0].getBoundingClientRect().width;
    const gap = cards.length > 1
      ? cards[1].getBoundingClientRect().left - cards[0].getBoundingClientRect().right
      : 0;
    return cardWidth + gap;
  };

  const centerCard = (card) => {
    const carouselRect = carousel.getBoundingClientRect();
    const cardRect = card.getBoundingClientRect();
    const targetLeft = carousel.scrollLeft + cardRect.left - carouselRect.left
      - (carousel.clientWidth - card.clientWidth) / 2;
    carousel.scrollTo({ left: Math.max(0, targetLeft), behavior: "smooth" });
  };

  const lock = () => {
    scrolling = true;
    setTimeout(() => { scrolling = false; }, 500);
  };

  // Center first card on load.
  centerCard(cards[0]);

  prevButtons.forEach((button) => {
    button.addEventListener("click", () => {
      if (scrolling || currentIndex === 0) return;
      currentIndex--;
      carousel.scrollBy({ left: -getStep(), behavior: "smooth" });
      lock();
    });
  });

  nextButtons.forEach((button) => {
    button.addEventListener("click", () => {
      if (scrolling || currentIndex === cards.length - 1) return;
      currentIndex++;
      carousel.scrollBy({ left: getStep(), behavior: "smooth" });
      lock();
    });
  });
};

const scrollToHash = (hash) => {
  const target = document.querySelector(hash);
  if (!target) return;

  const headerBar =
    document.querySelector("#header-bar") ?? document.querySelector("header");
  const offset = headerBar ? headerBar.getBoundingClientRect().height : 120;
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

  menu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 1024) dismiss();
    });
  });
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

const showWatermark = () => {
  console.log(
    `---- Watermark ----\nNirvana Painted Theme\nDesigned by ✨ Pchy Designs ✨ \n(Priyanshi Choudhury)\nhttps://pchy.design\nReach out for custom Websites, design work, or just to say hi at priyanshichoudhury.work@gmail.com\n-------------------`,
  );
};

showWatermark();

handled(registerCarousel);
handled(registerFAQ);
handled(registerSmoothScroll);
handled(registerCountUp);
handled(registerMobileMenu);
handled(registerSectionFadeIn);
