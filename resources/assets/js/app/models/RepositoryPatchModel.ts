import Model from "varie/lib/support/Model";

export default class RepositoryPatchModel extends Model {
    public id : number;
    public sha : string;
    public branch : string;
    public status : string;
    public runtime : number;
    public run_date : string;
    public repository : number;
    public patch_branch : string;
}
