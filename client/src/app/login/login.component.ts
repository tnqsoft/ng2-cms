import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../shared/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  private model: any = {};
  private loading: boolean = false;
  private error = '';

  constructor(private router: Router, private authService: AuthService) { }

  ngOnInit() {
    this.authService.logout();
  }

  login() {
    this.loading = true;
    this.authService.login(this.model.username, this.model.password, this.model.remember)
      .subscribe(result => {
        this.loading = false;
        this.router.navigate(['/']);
      }, err => {
        let error = err.json();
        this.error = error.message;
        this.loading = false;
      });
  }

}