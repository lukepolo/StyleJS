import file_types from "@app/rules/file_types";
import analysis_settings from "@app/rules/analysis_settings";
import prettier_prose_wrap from "@app/rules/prettier_prose_wrap";
import prettier_arrow_parens from "@app/rules/prettier_arrow_parens";
import prettier_trailing_comma from "@app/rules/prettier_trailing_comma";

export default {
  /*
  |--------------------------------------------------------------------------
  | Custom Rules
  |--------------------------------------------------------------------------
  |
  | You can supply your custom rules here
  |
  */

  rules: {
    file_types,
    analysis_settings,
    prettier_prose_wrap,
    prettier_arrow_parens,
    prettier_trailing_comma,
  },
};
