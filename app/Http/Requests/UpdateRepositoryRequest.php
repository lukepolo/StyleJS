<?php

namespace App\Http\Requests;

use App\Rules\FileTypes;
use App\Rules\AnalysisSetting;
use App\Rules\PrettierProseWrap;
use App\Rules\PrettierArrowParens;
use App\Rules\PrettierTrailingComma;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRepositoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ! empty($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Repository Details
            'branches' => 'array',
            'default_branch' => 'string',
            'no_ci' => 'required|boolean',
            'file_types' => ['required', new FileTypes],
            'on_demand' => 'required|boolean',
            'analysis_setting' => ['required', new AnalysisSetting],
            'ignore_directories' => 'nullable|string',
            'include_directories' => 'nullable|string',
            'cli_options' => 'required',

            // Global Settings
            'cli_options.use-tabs' => 'required|boolean',
            'cli_options.tab-width' => 'required|integer',
            'cli_options.print-width' => 'required|integer',

            // Javascript Options
            'cli_options.no-semi' => 'required|boolean',
            'cli_options.single-quote' => 'required|boolean',
            'cli_options.no-bracket-spacing' => 'required|boolean',
            'cli_options.jsx-bracket-same-line' => 'required|boolean',
            'cli_options.arrow-parens' => ['required', new PrettierArrowParens],
            'cli_options.trailing-comma' => ['required', new PrettierTrailingComma],

            // Markdown Options
            'cli_options.prose-wrap' => ['required', new PrettierProseWrap],

            // Special Options
            'cli_options.insert-pragma' => 'required|boolean',
            'cli_options.require-pragma' => 'required|boolean',
        ];
    }
}
