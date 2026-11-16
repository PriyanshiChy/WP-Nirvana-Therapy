# Book button actions

Each `.js` file here is a custom action for the "Book a Session" buttons.
`calendly.js` appears in the Action dropdown (Site Settings → Buttons, and on
each therapist) as "Script: Calendly". Names: lowercase letters, digits, `-`.

A file registers one handler:

```js
nirvanaActions.register("calendly", (button, context) => {
  // context: { bookingUrl, therapistId, therapistName, profileUrl }
  // (the therapist keys only on a therapist's button)
});
```

- The script loads only on pages that show a button using it.
- The button stays a link to the Booking URL: if the script fails to load,
  the link works; if the handler throws, the link is followed.
- Every handled click fires a bubbling `nirvana:action` event
  (`detail: { name, context }`).

- `nirvanaActions.run(name, element, context)` runs an action from your own
  code (the booking popup uses it for choices set to a script). It returns
  `false` when the action isn't registered.

`booking-popup.js` is the step-wise booking popup (content in Site Settings →
Booking Popup; see `inc/booking-popup.php`). It can't be picked as a choice
inside itself.

See `inc/actions.php` and `assets/js/actions.js`.
