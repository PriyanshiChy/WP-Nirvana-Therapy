/**
 * Booking popup: opens the step-by-step popup (parts/booking-popup.php)
 * from a Book button. Steps are edited in Site Settings → Booking Popup.
 *
 * Choices with data-then:
 *   next / step  show the next step / the step with data-target's ID
 *   link         a normal link (opens in a new tab); the popup closes
 *   script       runs nirvanaActions.run(data-script, choice, context), where
 *                context is the Book button's plus `answers`
 *                ({ "who": "Couple", … }); falls back to data-url
 *
 * Every final choice fires a bubbling "nirvana:booking" event with
 * { choice, answers, context }, e.g. for analytics.
 */
(() => {
  const dialog = document.getElementById("booking-popup");

  window.nirvanaActions.register("booking-popup", (button, context) => {
    // No steps saved: throwing makes the Book button work as a plain link.
    if (!dialog) throw new Error("The booking popup has no steps.");
    open(context);
  });

  if (!dialog) return;

  const steps = [...dialog.querySelectorAll("[data-step-id]")];
  const byId = new Map(steps.map((step) => [step.dataset.stepId, step]));
  const bar = dialog.querySelector(".booking-progress > span");
  const back = dialog.querySelector(".booking-back");

  let history = [];
  let answers = {};
  let context = {};

  const targetOf = (choice, step) => {
    if (choice.dataset.then === "next") return steps[steps.indexOf(step) + 1] ?? null;
    if (choice.dataset.then === "step") return byId.get(choice.dataset.target) ?? null;
    return null;
  };

  // Steps on the longest way through the popup, for the progress bar.
  const length = (step, seen = new Set()) => {
    if (!step || seen.has(step)) return 0;
    seen.add(step);
    const next = [...step.querySelectorAll("[data-then]")].map((choice) =>
      length(targetOf(choice, step), new Set(seen)),
    );
    return 1 + Math.max(0, ...next);
  };
  const total = length(steps[0]);

  const show = (step, focus = true) => {
    steps.forEach((each) => (each.hidden = each !== step));
    back.hidden = history.length === 0;
    bar.style.width = `${(history.length / Math.max(total, 1)) * 100}%`;

    const title = step.querySelector("h2");
    dialog.setAttribute("aria-labelledby", title.id);
    if (focus) title.focus({ preventScroll: true });
  };

  const go = (url) => {
    if (url) window.open(url, "_blank", "noopener");
  };

  const open = (buttonContext) => {
    context = buttonContext || {};
    history = [];
    answers = {};

    // Links without their own URL use this Book button's link.
    dialog.querySelectorAll("a[data-fallback]").forEach((link) => {
      if (context.bookingUrl) link.href = context.bookingUrl;
    });

    // Keep the header visible above the popup, as in the design.
    const header = document.querySelector("body > header, header");
    const top = header ? Math.max(0, header.getBoundingClientRect().bottom) : 0;
    dialog.style.setProperty("--booking-top", `${top}px`);

    show(steps[0], false);
    dialog.showModal();
    steps[0].querySelector("h2").focus({ preventScroll: true });
  };

  back.addEventListener("click", () => {
    const previous = history.pop();
    if (previous) show(previous);
  });

  dialog.querySelector(".booking-close").addEventListener("click", () => dialog.close());

  dialog.addEventListener("click", (event) => {
    // A click on the blurred area around the card closes the popup.
    if (event.target === dialog) {
      dialog.close();
      return;
    }

    const choice = event.target.closest("[data-then]");
    if (!choice) return;

    const step = choice.closest("[data-step-id]");
    if (choice.dataset.answer !== undefined) {
      answers[step.dataset.stepId] = choice.dataset.answer;
    }

    const then = choice.dataset.then;
    if (then === "next" || then === "step") {
      const target = targetOf(choice, step);
      if (!target) return;
      history.push(step);
      show(target);
      return;
    }

    const detail = { ...context, answers: { ...answers } };
    choice.dispatchEvent(
      new CustomEvent("nirvana:booking", {
        bubbles: true,
        detail: { choice: choice.dataset.answer ?? choice.textContent.trim(), answers: detail.answers, context },
      }),
    );

    if (then === "link") {
      dialog.close(); // The link itself opens.
      return;
    }

    event.preventDefault();
    dialog.close();
    const fallback = choice.dataset.url || context.bookingUrl;
    try {
      if (!window.nirvanaActions.run(choice.dataset.script, choice, detail)) go(fallback);
    } catch (error) {
      console.error(`Booking popup action "${choice.dataset.script}" failed:`, error);
      go(fallback);
    }
  });
})();
