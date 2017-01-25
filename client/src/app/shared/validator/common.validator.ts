import { Validator, AbstractControl, ValidatorFn } from '@angular/forms';

interface ValidationResult {
    [key: string]: boolean;
}

export class CommonValidator {

    constructor() { }

    static passwordMatch(control: AbstractControl): ValidationResult {
        let compareField = control.root.get('password');
        if (control.value === null || control.value === '' || compareField === null) {
            return null;
        }
        if (control.value !== compareField.value) {
            return { 'passwordmatch': true };
        }

        return null;
    }

}
