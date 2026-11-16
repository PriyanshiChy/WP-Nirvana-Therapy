/* global wp */
/**
 * "Semibold" button for the block editor's rich text (paragraphs, headings,
 * lists). Wraps the selection in the theme's custom <semibold> tag, the same
 * as the TinyMCE button in the section blocks' WYSIWYG fields.
 */
(function () {
  const { registerFormatType, toggleFormat } = wp.richText;
  const { RichTextToolbarButton } = wp.blockEditor;
  const { createElement: el } = wp.element;

  registerFormatType("nirvana/semibold", {
    title: "Semibold",
    tagName: "semibold",
    className: null,
    edit({ isActive, value, onChange }) {
      return el(RichTextToolbarButton, {
        icon: "editor-bold",
        title: "Semibold",
        isActive,
        onClick: () => onChange(toggleFormat(value, { type: "nirvana/semibold" })),
      });
    },
  });
})();
