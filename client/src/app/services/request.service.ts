import { Http } from '@angular/http';
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/toPromise';

@Injectable()
export class RequestService {
    stop_data: Object;
    time_data: Object;
    ip: string = '10.128.154.203';

    constructor(private http: Http) {
        this.stop_data = new Object();
        this.time_data = new Object();
    }

    public getStops(): Promise<Object> {
        return this.http.get('http://' + this.ip + '/8_euclide/fetch_cur_pos_stop.php?' +
        'api_key=JesusVousAime<3!&cur_datetime=2017-03-12 20:42&lng=-73.123&lat=45.564')
            .toPromise()
            .then(response => {
                const temp = response;
                this.stop_data  = JSON.parse(temp.toString())[1];
                console.log(this.stop_data);
                return this.stop_data;
            })
            .catch(this.handleError);
    }

    public getStopTimes(): Promise<Object> {
        return this.http.get('http://' + this.ip + '/8_euclide/fetch_cur_pos_stop_times.php?' +
        'api_key=JesusVousAime<3!&cur_datetime=2017-03-12 20:42&lng=-73.123&lat=45.564')
            .toPromise()
            .then(response => {
                const temp = response.json();
                this.time_data  = JSON.parse(temp.toString());
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