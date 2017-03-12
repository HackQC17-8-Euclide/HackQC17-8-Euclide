import { Http } from '@angular/http';
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/toPromise';

@Injectable()
export class RequestService {
    stop_data: any;
    time_data: any;
    ip: string = '10.128.154.203';

    constructor(private http: Http) {
    }

    public getStops(): Promise<Object> {
        return this.http.get('http://' + this.ip + '/8_euclide/fetch_cur_pos_stop.php?' +
        'api_key=JesusVousAime<3!&cur_datetime=2017-03-12 20:42&lng=-73.123&lat=45.564')
            .toPromise()
            .then( response => {
                this.stop_data = response.json();
                return this.stop_data;
            })
            .catch(this.handleError);
    }

    public getStopTimes(): Promise<Object> {
        return this.http.get('http://' + this.ip + '/8_euclide/fetch_cur_pos_stop_times.php?' +
        'api_key=JesusVousAime<3!&cur_datetime=2017-03-12 20:42&lng=-73.123&lat=45.564')
            .toPromise()
            .then(response => {
                this.time_data = response.json();
                console.log(this.time_data);
                return this.time_data;

            })
            .catch(this.handleError);
    }

    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error); // for demo purposes only
        return Promise.reject(error.message || error);
    }

}