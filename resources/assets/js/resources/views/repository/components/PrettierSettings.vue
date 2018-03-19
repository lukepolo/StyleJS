<template>
    <div>
        <div class="row">
            <div class="col-sm-4">
                <h3 class="dotted-title">
                    <span>
                        Global Formatting
                    </span>
                </h3>
                <global-options
                    :useTabs.sync="options['use-tabs']"
                    :tabWidth.sync="options['tab-width']"
                    :printWidth.sync="options['print-width']"
                ></global-options>
            </div>
            <div class="col-sm-8">
                <h3 class="dotted-title">
                    <span>
                        JavaScript Formatting
                    </span>
                </h3>
                <div class="row">
                    <javascript-options
                        :noSemi.sync="options['no-semi']"
                        :arrowParens.sync="options['arrow-parens']"
                        :singleQuote.sync="options['single-quote']"
                        :trailingComma.sync="options['trailing-comma']"
                        :noBracketSpacing.sync="options['no-bracket-spacing']"
                        :jsxBracketSameLine.sync="options['jsx-bracket-same-line']"
                    ></javascript-options>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <h3 class="dotted-title">
                    <span>
                        Markdown Options
                    </span>
                </h3>
                <markdown-options
                    :proseWrap.sync="options['prose-wrap']"
                ></markdown-options>
            </div>
            <div class="col-sm-8">
                <h3 class="dotted-title">
                    <span>
                        Special Options
                    </span>
                </h3>
                <special-options
                    :insertPragma.sync="options['insert-pragma']"
                    :requirePragma.sync="options['require-pragma']"
                ></special-options>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import GlobalOptions from "./prettier-options/GlobalOptions";
import SpecialOptions from "./prettier-options/SpecialOptions";
import MarkdownOptions from "./prettier-options/MarkdownOptions";
import JavascriptOptions from "./prettier-options/JavascriptOptions";

export default Vue.extend({
  props: ["options"],
  components: {
    GlobalOptions,
    SpecialOptions,
    MarkdownOptions,
    JavascriptOptions,
  },
  watch: {
    options: function() {
      this.$emit("update:options", options);
    },
  },
});
</script>
