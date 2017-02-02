import { Injectable } from '@angular/core';
import { FormBase } from '../shared/base';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';

@Injectable()
export class LoginForm extends FormBase {

    getListInput(): Array<string> {
        return ['username', 'password', 'remember'];
    }

    setForm(fb: FormBuilder): void {
        this.form = fb.group({
            username: new FormControl(null, Validators.required),
            password: new FormControl(null, Validators.required),
            remember: new FormControl(true)
        });
    }
}