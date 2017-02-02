import { Component, OnInit, Inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserService } from '../../shared/services';
import { ChangepasswordForm } from './changepassword.form';

@Component({
  selector: 'app-changepassword',
  templateUrl: './changepassword.component.html',
  styleUrls: ['./changepassword.component.scss'],
  providers: [
    ChangepasswordForm
  ]
})
export class ChangepasswordComponent implements OnInit {

  private loading: boolean = false;

  constructor(
    private userService: UserService,
    private changepasswordForm: ChangepasswordForm) {
  }

  ngOnInit() {
  }

  changePassword(data: any): void {
    this.loading = true;
    this.changepasswordForm.errors = null;
    this.changepasswordForm.disableControl();
    this.userService.updatePassword(data.oldPassword, data.password)
      .subscribe(response => {
        this.loading = false;
        this.changepasswordForm.enableControl();
      }, error => {
        this.changepasswordForm.errors = error.error;
        this.loading = false;
        this.changepasswordForm.enableControl();
      });
  }

}
