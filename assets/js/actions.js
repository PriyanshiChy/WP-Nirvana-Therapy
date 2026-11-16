/**
 * Registry for custom Book button actions (see inc/actions.php).
 *
 * Each script in assets/js/actions/ registers one handler:
 *
 *   nirvanaActions.register("calendly", (button, context) => { ... });
 *
 * A click on a button with data-nirvana-action="calendly" runs it instead of
 * following the link. If the handler isn't registered (the script failed to
 * load) the link works as a normal link. If it throws, the link is followed.
 *
 * Every handled click also fires a bubbling "nirvana:action" event with
 * { name, context }, e.g. for analytics.
 */
(() => {
  const handlers = new Map();

  window.nirvanaActions = {
    register(name, handler) {
      handlers.set(name, handler);
    },

    /**
     * Runs a registered action, e.g. from a booking popup choice. Returns
     * false when it isn't registered. Errors are the caller's to handle.
     */
    run(name, element, context = {}) {
      const handler = handlers.get(name);
      if (!handler) return false;

      element.dispatchEvent(
        new CustomEvent("nirvana:action", { bubbles: true, detail: { name, context } }),
      );
      handler(element, context);
      return true;
    },
  };

  const readContext = (button) => {
    try {
      return JSON.parse(button.dataset.nirvanaContext || "{}");
    } catch {
      return {};
    }
  };

  const followLink = (button) => {
    if (!button.href) return;
    if (button.target === "_blank") {
      window.open(button.href, "_blank", "noopener");
    } else {
      window.location.assign(button.href);
    }
  };

  document.addEventListener("click", (event) => {
    const button = event.target.closest("[data-nirvana-action]");
    if (!button) return;

    const name = button.dataset.nirvanaAction;
    if (!handlers.has(name)) return;

    event.preventDefault();
    try {
      window.nirvanaActions.run(name, button, readContext(button));
    } catch (error) {
      console.error(`Book action "${name}" failed:`, error);
      followLink(button);
    }
  });
})();
