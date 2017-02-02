import { Component, OnInit, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../shared/services';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { LoginForm } from './login.form';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
  providers: [
    LoginForm
  ]
})
export class LoginComponent implements OnInit {

  private loading: boolean = false;

  constructor(
    private router: Router,
    private authService: AuthService,
    private loginForm: LoginForm) {
  }

  ngOnInit() {
    this.authService.logout();
  }

  login(data: any) {
    this.loading = true;
    this.loginForm.errors = null;
    this.loginForm.disableControl();
    this.authService.login(data.username, data.password, data.remember)
      .subscribe(result => {
        this.loading = false;
        this.loginForm.enableControl();
        this.router.navigate(['/']);
      }, error => {
        this.loginForm.errors = error.error;
        this.loading = false;
        this.loginForm.enableControl();
      });
  }

}