import { Inject } from '@angular/core';
import { AbstractControl, FormGroup, FormBuilder } from '@angular/forms';

export abstract class FormBase {

    public form: FormGroup;

    public errors: any;

    abstract getListInput(): Array<string>;

    abstract setForm(fb: FormBuilder): void;

    constructor( @Inject(FormBuilder) fb: FormBuilder) {
        this.setForm(fb);
        this.form.valueChanges.subscribe(data => {
            this.errors = null;
        });
    }

    isError(controlName: string): boolean {
        let control: AbstractControl = this.form.controls[controlName];
        return control.errors && (control.dirty || control.touched);
    }

    hasErrorWith(controlName: string, error: string): boolean {
        let control: AbstractControl = this.form.controls[controlName];
        return control.errors[error];
    }

    enableControl(): void {
        let inputs = this.getListInput();
        for (let input of inputs) {
            let ctrl = this.form.get(input);
            ctrl.enable();
        }
    }

    disableControl(): void {
        let inputs = this.getListInput();
        for (let input of inputs) {
            let ctrl = this.form.get(input);
            ctrl.disable();
        }
    }
}
