import Validator from "varie/lib/validation/Validator";

export default class UpdateUserValidator extends Validator {
    public rules = {
        'name' : 'required|string',
        'email' : 'required|email'
    };

    public messages = {

    };
}