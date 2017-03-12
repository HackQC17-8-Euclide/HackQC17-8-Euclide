import {Stop} from './Stops'
import {Stops} from './Stops'

export class Stop_time {
  trip_id: number;
  arr: number;
  dep: number;
  stop_id: number;
  stop_sequence: number;
  pred: Stop_time;
  succ: Stop_time;
  stop: Stop;


  constructor( trip_id, arr, dep, stop_id, stop_sequence) {
    this.trip_id = trip_id;
    this.arr = arr;
    this.dep = dep;
    this.stop_id = stop_id;
    this.stop_sequence = stop_sequence;
    this.pred=null;
    this.succ=null;
    this.stop =null;
  }

}

export class Stops_times {
  static formatted_stop_times: Stop_time[]=new Array<Stop_time>();


  static compute_formatted_stop_times() {
    for (var i = 0; i < Stops_times.stops_times.length; i++) {
      var a = Stops_times.stops_times[i];
      Stops_times.formatted_stop_times[i] = new Stop_time(a.trip_id, a.arr, a.dep, a.stop_id, a.stop_sequence);
    }
    for (var i = 0; i < Stops_times.stops_times.length; i++) {
      for (var j=0;j<Stops.formatted_stops.length;j++){
        if (Stops.formatted_stops[j].id==Stops_times.stops_times[i].stop_id){
           Stops_times.formatted_stop_times[i].stop = Stops.formatted_stops[j];
           Stops.formatted_stops[j].times[Stops.formatted_stops[j].times.length]=Stops_times.formatted_stop_times[i];
        }
        //  console.log (Stops.formatted_stops[j].id);
        //   console.log (Stops_times.stops_times[i].stop_id);
      }
     
    }

    Stops_times.build_trips();
  }
  

  static build_trips() {
        for (var i = 0; i < Stops_times.formatted_stop_times.length; i++) {
            for (var j = i; j < Stops_times.formatted_stop_times.length; j++) {
                if (Stops_times.formatted_stop_times[i].trip_id==Stops_times.formatted_stop_times[j].trip_id){
                    if (Stops_times.formatted_stop_times[i].stop_sequence==Stops_times.formatted_stop_times[j].stop_sequence+1){
                        Stops_times.formatted_stop_times[i].pred=Stops_times.formatted_stop_times[j];
                        Stops_times.formatted_stop_times[j].succ=Stops_times.formatted_stop_times[i];
                    }
                    if (Stops_times.formatted_stop_times[j].stop_sequence==Stops_times.formatted_stop_times[i].stop_sequence+1){
                        Stops_times.formatted_stop_times[j].pred=Stops_times.formatted_stop_times[i];
                        Stops_times.formatted_stop_times[i].succ  =Stops_times.formatted_stop_times[j];
                    }
                }
            }
        }
    }

  public static stops_times = [];
}