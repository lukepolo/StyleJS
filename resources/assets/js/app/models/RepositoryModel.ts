import Model from "varie/lib/support/Model";
import RepositoryPatchModel from "@models/RepositoryPatchModel";

export default class RepositoryModel extends Model {

    public id : number;
    public hash : string;
    public no_ci : Boolean;
    public repository : string;
    public on_demand : boolean;
    public cli_options : object;
    public default_branch : string;
    public branches : Array<string>;
    public analysis_setting : string;
    public user_provider_id : number;
    public file_types : Array<string>;
    public ignore_directories : string;
    public include_directories : string;
    public last_patch : RepositoryPatchModel;

    protected defaults() {
        this.branches = [];
        this.no_ci = false;
        this.file_types = [];
        this.on_demand = false;
        this.ignore_directories = 'public';
        this.include_directories = '';
        this.cli_options = {
            // Global Settings
            "use-tabs": false,
            "tab-width": 2,
            "print-width": 80,

            // Javascript Options
            "no-semi": false,
            "single-quote": false,
            "no-bracket-spacing": false,
            "jsx-bracket-same-line": false,
            "arrow-parens": "avoid",
            "trailing-comma": "none",

            // Markdown Options
            "prose-wrap": "preserve",

            // Special Options
            "insert-pragma": false,
            "require-pragma": false,
        }
    }
}
