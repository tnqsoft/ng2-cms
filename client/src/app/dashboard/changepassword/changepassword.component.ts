import { Component, OnInit, Inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { CommonValidator } from '../../shared/validator/common.validator';
import { UserService } from '../../shared/services';

@Component({
  selector: 'app-changepassword',
  templateUrl: './changepassword.component.html',
  styleUrls: ['./changepassword.component.scss']
})
export class ChangepasswordComponent implements OnInit {

  private loading: boolean = false;
  private error;
  private form: FormGroup;

  constructor( @Inject(FormBuilder) fb: FormBuilder, private userService: UserService) {
    this.form = fb.group({
      oldPassword: ['123456', Validators.required],
      password: ['123456', Validators.compose([Validators.required, Validators.minLength(6)])],
      confirmPassword: ['123456', Validators.compose([Validators.required, CommonValidator.passwordMatch])],
    });
  }

  ngOnInit() {
  }

  isError(input: string): boolean {
    return this.form.controls[input].errors && (this.form.controls[input].dirty || this.form.controls[input].touched);
  }

  hasErrorWith(input: string, error: string): boolean {
    return this.form.controls[input].errors[error];
  }

  changePassword(data: any): void {
    this.loading = true;
    this.error = null;
    this.userService.updatePassword(data.oldPassword, data.password)
      .subscribe(response => {
        this.loading = false;
      }, error => {
        this.error = JSON.parse(error.message);
      });
  }

}
