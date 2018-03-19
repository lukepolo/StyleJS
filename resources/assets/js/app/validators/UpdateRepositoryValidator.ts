import Validator from "varie/lib/validation/Validator";

export default class UpdateRepositoryValidator extends Validator {
    public rules = {
        // Repository Details
        'branches': 'array',
        'default_branch': 'nullable|string',
        'no_ci': 'required|boolean',
        'file_types': 'required|file_types',
        'on_demand': 'required|boolean',
        'analysis_setting': 'required|analysis_settings',
        'ignore_directories': 'nullable|string',
        'include_directories': 'nullable|string',
        'cli_options': 'required',

        // Global Settings
        'cli_options.use-tabs': 'required|boolean',
        'cli_options.tab-width': 'required|integer',
        'cli_options.print-width': 'required|integer',

        // Javascript Options
        'cli_options.no-semi': 'required|boolean',
        'cli_options.single-quote': 'required|boolean',
        'cli_options.no-bracket-spacing': 'required|boolean',
        'cli_options.jsx-bracket-same-line': 'required|boolean',
        'cli_options.arrow-parens': 'required|prettier_arrow_parens',
        'cli_options.trailing-comma': 'required|prettier_trailing_comma',

        // Markdown Options
        'cli_options.prose-wrap': 'required|prettier_prose_wrap',

        // Special Options
        'cli_options.insert-pragma': 'required|boolean',
        'cli_options.require-pragma': 'required|boolean',
    };

    public messages = {

    };
}