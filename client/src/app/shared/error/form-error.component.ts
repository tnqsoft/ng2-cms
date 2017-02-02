import { Component, OnInit, Input } from '@angular/core';
import { FormBase } from '../base/form-base';

@Component({
    selector: 'app-form-error',
    templateUrl: './form-error.component.html',
    styleUrls: ['./form-error.component.scss']
})
export class FormErrorComponent implements OnInit {

    @Input() formBase: FormBase;

    constructor() { }

    ngOnInit() {
    }

    isGlobalError(): boolean {
        if (typeof this.formBase.errors === 'string' || this.formBase.errors instanceof String) {
            return true;
        } else {
            return false;
        }
    }

}