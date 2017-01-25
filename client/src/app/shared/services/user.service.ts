import { Injectable } from '@angular/core';
import { Http, Headers, Response } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map';
import { environment } from '../../../environments/environment';
import { AuthHttpService } from './auth-http.service';

@Injectable()
export class UserService {

    constructor(private http: AuthHttpService) { }

    updatePassword(oldPassword: string, newPassword: string): Observable<boolean> {
        return this.http.put(environment.apiUrl + 'user/change_password', JSON.stringify({
            oldPassword: oldPassword,
            newPassword: newPassword,
        }))
        .map((response: Response) => response.json())
        .catch((error: any) => Observable.throw(error.json() || 'Server error'));
    }

}
