/* global tinymce */
/**
 * "Semibold" button for TinyMCE (SCF WYSIWYG fields).
 * Wraps the selection in the theme's custom <semibold> tag.
 *
 * Must be a regular function, not an arrow function: TinyMCE 4 calls
 * `new Plugin(editor)`, and arrow functions can't be constructed.
 */
tinymce.PluginManager.add("nirvana_semibold", function (editor) {
  editor.on("init", () => {
    editor.formatter.register("semibold", { inline: "semibold" });
  });

  editor.addButton("nirvana_semibold", {
    text: "Semibold",
    tooltip: "Semibold",
    onclick: () => editor.formatter.toggle("semibold"),
    onPostRender() {
      const button = this;
      editor.on("NodeChange", () => {
        button.active(editor.formatter.match("semibold"));
      });
    },
  });
});
