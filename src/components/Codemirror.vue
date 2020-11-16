<template>
  <textarea ref="myCm"></textarea>
</template>

<script>
import CodeMirror from "codemirror";
// language
import "codemirror/mode/javascript/javascript";
import "codemirror/mode/css/css";
// theme css
import "codemirror/lib/codemirror.css";
import "codemirror/theme/monokai.css";
// require active-line.js
import "codemirror/addon/selection/active-line";
// styleSelectedText
import "codemirror/addon/selection/mark-selection";
import "codemirror/addon/search/searchcursor";
// hint
import "codemirror/addon/hint/show-hint";
import "codemirror/addon/hint/show-hint.css";
import "codemirror/addon/hint/javascript-hint";
// highlightSelectionMatches
import "codemirror/addon/scroll/annotatescrollbar";
import "codemirror/addon/search/matchesonscrollbar";
import "codemirror/addon/search/match-highlighter";
// keyMap
import "codemirror/mode/clike/clike";
import "codemirror/addon/edit/matchbrackets";
import "codemirror/addon/comment/comment";
import "codemirror/addon/dialog/dialog";
import "codemirror/addon/dialog/dialog.css";
import "codemirror/addon/search/search";
import "codemirror/keymap/sublime";
// foldGutter
import "codemirror/addon/fold/foldgutter.css";
import "codemirror/addon/fold/brace-fold";
import "codemirror/addon/fold/comment-fold";
import "codemirror/addon/fold/foldcode";
import "codemirror/addon/fold/foldgutter";
import "codemirror/addon/fold/indent-fold";
import "codemirror/addon/fold/markdown-fold";
import "codemirror/addon/fold/xml-fold";

export default {
  name: "Codemirror",
  props: {
    hidden: Boolean,
    value: String, // give to the component a start value
    options: Object // give the codeMirror options
  },
  data() {
    return {
      myCodemirror: undefined
    };
  },
  mounted() {
    this.myCodemirror = new CodeMirror.fromTextArea(this.$refs.myCm, this.options);
    this.myCodemirror.setValue(this.value);
    this.myCodemirror.on('change', cm => {
      if(this.$emit) {
        this.$emit('input', cm.getValue());
      }
    });
    this.myCodemirror.on('focus', cm => {
      cm.refresh();
    });
  }
};
</script>