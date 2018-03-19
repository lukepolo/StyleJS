// TODO - fix this blade
<div class="landing-section-3">

    @if(!isset($options))
        <h2 class="fadeIn animated">
            We make your JavaScript code beautiful
        </h2>

        <h4 class="margin-bottom-3 gray-text fadeIn animated">
            Just push your ugly (but working) code and we make it pretty
        </h4>
    @endif

    <div class="editors">

        <div class="code-editor bounceInLeft animated">

            <div class="header">
                <div class="dot red"></div>
                <div class="dot yellow"></div>
                <div class="dot green"></div>
            </div>

            <div class="code">
                <div class="editor input">
                    <textarea class="loading" id="input-editor"></textarea>
                </div>
            </div>

        </div>

        <div class="code-editor bounceInRight animated">

            <div class="header">
                <div class="dot red"></div>
                <div class="dot yellow"></div>
                <div class="dot green"></div>
            </div>

            <div class="code">

                <div class="editor output">
                    <textarea class="loading" id="output-editor"></textarea>
                </div>

            </div>
        </div>
    </div>

    <section>
        <details class="container" @if(isset($options)) open @endif>

            <summary class="options-summary s-button s-button-more margin-top-4 margin-bottom-2" @if(isset($options)) style="display: none;" @endif>Options</summary>

            <div class="row options-container">
                <div class="options">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="printWidth">Print Width</label>
                            <input type="number" class="form-control" value="80" min="0" name="printWidth" id="printWidth" required>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tabWidth">Tab / Spaces Width</label>
                            <input type="number" class="form-control" value="2" min="0" name="tabWidth" id="tabWidth" required>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="useTabs">use-tabs
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-inverted id="semi">no-semi
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="singleQuote">single-quote
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" data-inverted id="bracketSpacing">no-bracket-spacing
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="jsxBracketSameLine">jsx-bracket-same-line
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="trailingComma">Trailing Comma</label>

                            <select name="trailingComma" class="form-control" id="trailingComma">
                                <option value="none">none</option>
                                <option value="es5">es5</option>
                                <option value="all">all</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="parser">Parser</label>

                            <select name="parser" class="form-control" id="parser">
                                <option value="babylon">babylon</option>
                                <option value="flow">flow</option>
                                <option value="typescript">typescript</option>
                                <option value="postcss">postcss</option>
                                <option value="json">json</option>
                                <option value="graphql">graphql</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </details>
    </section>
</div>

@push('scripts')

    <script src="https://prettier.io/lib/prettier-version.js"></script>

    <script>
        var state =  {
            options: undefined,
            content:
            'hello ( "world"\n);\n\n' +
            '[ "lorem", "ipsum", \'dolor\', sit("amet"), consectetur[ \'adipiscing\' ] + "elit" ].reduce(\n  (first, second) => first + second,\n  "")\n\n' +
            "const Foo = ({ bar, baz, things }) => {\n" +
            '  return <div style=@{{\ncolor: "papayawhip"}}>\n' +
            "    <br/>{things.map(thing => reallyLongPleaseDontPutOnOneLine(thing) ? <p>{ok}</p> : <Quax bar={bar} baz={ baz } {...thing}></Quax>)\n" +
            "  }</div>}"
        };

        var worker = new Worker("/worker.js");

        // Warm up the worker (load the current parser while CodeMirror loads)
        worker.postMessage({ text: "", options: state.options });

        window.onload = function() {
            var OPTIONS = [
                "printWidth",
                "tabWidth",
                "singleQuote",
                "trailingComma",
                "bracketSpacing",
                "jsxBracketSameLine",
                "parser",
                "semi",
                "useTabs",
            ];
            state.options && setOptions(state.options);

            function setOptions(options) {
                OPTIONS.forEach(function(option) {
                    var elem = document.getElementById(option);
                    if (elem.tagName === "SELECT") {
                        elem.value = options[option];
                    } else if (elem.type === "number") {
                        elem.value = options[option];
                    } else {
                        var isInverted = elem.hasAttribute("data-inverted");
                        elem.checked = isInverted ? !options[option] : options[option];
                    }
                });
            }

            function getOptions() {
                var options = {};
                OPTIONS.forEach(function(option) {
                    var elem = document.getElementById(option);
                    if (elem.tagName === "SELECT") {
                        options[option] = elem.value;
                    } else if (elem.type === "number") {
                        options[option] = Number(elem.value);
                    } else {
                        var isInverted = elem.hasAttribute("data-inverted");
                        options[option] = isInverted ? !elem.checked : elem.checked;
                    }
                });
                return options;
            }

            function formatAsync() {
                var options = getOptions();

                outputEditor.setOption("rulers", [
                    { column: options.printWidth, color: "#444444" }
                ]);

                worker.postMessage({
                    text: inputEditor.getValue(),
                    options: options,
                });
            }

            var editorOptions = {
                lineNumbers: true,
                keyMap: "sublime",
                autoCloseBrackets: true,
                matchBrackets: true,
                showCursorWhenSelecting: true,
                theme: "neat",
                tabWidth: 2
            };
            var inputEditor = CodeMirror.fromTextArea(
                document.getElementById("input-editor"),
                editorOptions
            );
            inputEditor.on("change", formatAsync);

            var outputEditor = CodeMirror.fromTextArea(
                document.getElementById("output-editor"),
                { readOnly: true, lineNumbers: true, theme: "neat" }
            );

            Array.from(document.querySelectorAll("textarea")).forEach(function(element) {
                element.classList.remove("loading");
            });

            worker.onmessage = function(message) {
                outputEditor.setValue(message.data.formatted);
            };

            inputEditor.setValue(state.content);
            document.querySelector(".options-container").onchange = formatAsync;
        };
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/codemirror.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/mode/javascript/javascript.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/display/rulers.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/search/searchcursor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/edit/matchbrackets.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/edit/closebrackets.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/comment/comment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/wrap/hardwrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/keymap/sublime.js"></script>

@endpush