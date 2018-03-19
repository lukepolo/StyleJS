export interface RemoteRepositoriesState {
    repositories : Array<{
        id : number,
        full_name : string,
        user_provider_id : number,
    }>
}
