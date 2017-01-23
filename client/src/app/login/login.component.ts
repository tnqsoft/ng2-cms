import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  private model: any = {};
  private loading: boolean = false;
  private error = '';

  constructor(private router: Router) { }

  ngOnInit() {
  }

  login() {
    // this.router.navigate(['/dashboard']);
  }

}