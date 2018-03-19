import RepositoryPatchModel from "@models/RepositoryPatchModel";

export interface PatchesState {
    patch : RepositoryPatchModel,
    paginatedPatches : {
        meta : {
            first: string,
            last: string,
            prev: string,
            next: string
        },
        links : {
            current_page: number,
            from: number,
            last_page: number,
            path: string,
            per_page: number,
            to: number,
            total: number
        },
        data : Array<RepositoryPatchModel>
    }
}
