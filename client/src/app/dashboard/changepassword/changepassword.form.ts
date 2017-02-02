import { Injectable } from '@angular/core';
import { FormBase } from '../../shared/base';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { CommonValidator } from '../../shared/validator/common.validator';

@Injectable()
export class ChangepasswordForm extends FormBase {

    getListInput(): Array<string> {
        return ['oldPassword', 'password', 'confirmPassword'];
    }

    setForm(fb: FormBuilder): void {
        this.form = fb.group({
            oldPassword: [null, Validators.required],
            password: [null, Validators.compose([Validators.required, Validators.minLength(6)])],
            confirmPassword: [null, Validators.compose([Validators.required, CommonValidator.passwordMatch])],
        });
    }
}