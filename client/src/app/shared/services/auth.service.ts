import { Injectable } from '@angular/core';
import { Http, Headers, Response } from '@angular/http';
import { Observable } from 'rxjs';
import { JwtHelper } from '../helper';
import 'rxjs/add/operator/map';
import { environment } from '../../../environments/environment';
import { AuthHttpService } from './auth-http.service';

@Injectable()
export class AuthService {
  public token: string;
  public username: string;
  public userid: number;

  constructor(
    private http: AuthHttpService,
    private jwtHelper: JwtHelper
  ) {
    // set token if saved in local storage
    this.token = this.getToken();
  }

  login(username: string, password: string, remember: boolean): Observable<boolean> {
    return this.http.post(environment.apiUrl+'user/login', JSON.stringify({
      username: username,
      password: password,
      remember: remember
    }))
      .map((response: Response) => {
        // login successful if there's a jwt token in the response
        let token = response.json() && response.json().token;
        if (token) {
          // set token property
          this.token = token;

          // store username and jwt token in local storage to keep user logged in between page refreshes
          if (remember === true) {
            localStorage.setItem('token', token);
          } else {
            sessionStorage.setItem('token', token);
          }

          // Get new Token
          this.token = this.getToken();

          // return true to indicate successful login
          return true;
        } else {
          // return false to indicate failed login
          return false;
        }
      })
      .catch((error: any) => Observable.throw(error.json() || 'Server error'));
  }

  logout(): void {
    // clear token remove user from local storage to log user out
    this.token = null;
    localStorage.removeItem('token');
    sessionStorage.removeItem('token');
  }

  getToken(): string {
    let token = localStorage.getItem('token');

    if (token === null) {
      token = sessionStorage.getItem('token');
    }

    if (null !== token) {
      let info = this.jwtHelper.decodeToken(token);
      this.username = info.username;
      this.userid = info.id;
    }

    return token;
  }
}