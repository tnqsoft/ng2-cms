import { Component, OnInit, Input } from '@angular/core';
import { FormBase } from '../base';

@Component({
  selector: 'app-field-error',
  templateUrl: './field-error.component.html',
  styleUrls: ['./field-error.component.scss']
})
export class FieldErrorComponent implements OnInit {

  @Input() formBase: FormBase;
  @Input() field: string;

  constructor() { }

  ngOnInit() {
  }

  getErrorField(): string {
    if (!this.formBase.errors || !this.formBase.errors[this.field]) {
      return null;
    }

    return this.formBase.errors[this.field];
  }

}