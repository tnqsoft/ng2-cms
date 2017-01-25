import { Injectable } from '@angular/core';
import { Http, Headers, Response } from '@angular/http';
import { Observable } from 'rxjs';
import 'rxjs/add/operator/map';
import { environment } from '../../../environments/environment';

@Injectable()
export class UserService {

    constructor(private http: Http) { }

    updatePassword(oldPassword: string, newPassword: string): Observable<boolean> {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');

        return this.http.put(environment.apiUrl + 'user/change_password', JSON.stringify({
            oldPassword: oldPassword,
            newPassword: newPassword,
        }), {
                headers: headers
            })
            .map((response: Response) => response.json());
    }

}
