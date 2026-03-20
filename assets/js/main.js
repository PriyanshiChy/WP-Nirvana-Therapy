/**
 * Main JS for Nirvana Painted theme.
 *
 * Add lightweight interactivity here (e.g., FAQ accordion toggles).
 */

// FAQ Accordion
document.querySelectorAll('.faq-trigger').forEach((trigger) => {
  trigger.addEventListener('click', () => {
    const answer = trigger.nextElementSibling;
    const isOpen = trigger.getAttribute('aria-expanded') === 'true';

    // Close all other open items
    document.querySelectorAll('.faq-trigger[aria-expanded="true"]').forEach((openTrigger) => {
      if (openTrigger !== trigger) {
        openTrigger.setAttribute('aria-expanded', 'false');
        openTrigger.nextElementSibling.hidden = true;
      }
    });

    trigger.setAttribute('aria-expanded', String(!isOpen));
    answer.hidden = isOpen;
  });
});
