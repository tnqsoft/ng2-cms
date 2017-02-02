import { Component, OnInit, Input } from '@angular/core';
import { FormBase } from '../base/form-base';

@Component({
  selector: 'app-validate-error',
  templateUrl: './validate-error.component.html',
  styleUrls: ['./validate-error.component.scss']
})
export class ValidateErrorComponent implements OnInit {

  @Input() formBase: FormBase;
  @Input() field: string;
  @Input() error: string;
  @Input() message: string;

  constructor() { }

  ngOnInit() {
  }

}
