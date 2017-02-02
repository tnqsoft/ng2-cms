import { Injectable } from '@angular/core';
import { Router, CanActivate } from '@angular/router';
import { AuthService } from './auth.service';
import { JwtHelper } from '../helper';

@Injectable()
export class AuthGuard implements CanActivate {

  constructor(
    private router: Router,
    private jwtHelper: JwtHelper,
    private authService: AuthService
  ) { }

  canActivate() {
    let token = this.authService.token;
    if (token && this.jwtHelper.isTokenExpired(token) === false) {
      // logged in so return true
      return true;
    }

    // not logged in so redirect to login page
    this.router.navigate(['/login']);
    return false;
  }
}