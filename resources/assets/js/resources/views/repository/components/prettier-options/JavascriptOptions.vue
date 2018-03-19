<template>
    <div>
        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="cli_options.no-semi" :checked="noSemi" @click="updateSync($event.target, 'no-semi')" validate>
                    No Semicolons
                </label>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="cli_options.single-quote" :checked="singleQuote" @click="updateSync($event.target, 'single-quote')" validate> Single Quotes
                </label>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="cli_options.no-bracket-spacing" :checked="noBracketSpacing" @click="updateSync($event.target, 'no-bracket-spacing')" validate> No Bracket Spacing
                </label>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="cli_options.jsx-bracket-same-line" :checked="jsxBracketSameLine" @click="updateSync($event.target, 'jsx-bracket-same-line')" validate>
                    JSX Bracket on the same line
                </label>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="arrow-parens">Arrow Function Parentheses</label>
                <select name="cli_options.arrow-parens" class="form-control" id="arrow-parens" :value="arrowParens" @input="updateSync($event.target, 'arrow-parens')" validate>
                    <option v-for="option in arrowParentOptions" :value="option">
                        {{ option }}
                    </option>
                </select>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="trailing-comma">Trailing Comma</label>
                <select name="cli_options.trailing-comma" class="form-control" id="trailing-comma" :value="trailingComma" @input="updateSync($event.target, 'trailing-comma')" validate>
                    <option v-for="option in trailingCommaOptions" :value="option">
                        {{ option }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { mapGetters } from "vuex";

export default Vue.extend({
  props: [
    "noSemi",
    "arrowParens",
    "singleQuote",
    "trailingComma",
    "noBracketSpacing",
    "jsxBracketSameLine",
  ],
  computed: {
    ...mapGetters({
      arrowParentOptions: "constants/arrowParentOptions",
      trailingCommaOptions: "constants/trailingCommaOptions",
    }),
  },
});
</script>
