import {Stop} from './Stops'
import {Stops} from './Stops'

export class Stop_time {
  id: number;
  trip_id: number;
  arr: number;
  dep: number;
  stop_id: number;
  stop_sequence: number;
  is_terminus: boolean;
  is_head: boolean;
  pred: Stop_time;
  succ: Stop_time;
  stop: Stop;


  constructor(id, trip_id, arr, dep, stop_id, stop_sequence, is_terminus, is_head) {
    this.id = id;
    this.trip_id = trip_id;
    this.arr = arr;
    this.dep = dep;
    this.stop_id = stop_id;
    this.stop_sequence = stop_sequence;
    this.is_terminus = is_terminus;
    this.is_head = is_head;
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
      Stops_times.formatted_stop_times[i] = new Stop_time(a._id, a.trip_id, a.arr, a.dep, a.stop_id, a.stop_sequence, a.is_terminus, a.is_head);
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

  private static stops_times = [
    {
      "_id": 1,
      "trip_id": 1,
      "arr": 6636,
      "dep": 6636,
      "stop_id": 6,
      "stop_sequence": 6,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 2,
      "trip_id":1,
      "arr": 4933,
      "dep": 4933,
      "stop_id": 4,
      "stop_sequence": 4,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 3,
      "trip_id": 1,
      "arr": 2902,
      "dep": 2902,
      "stop_id": 2,
      "stop_sequence": 2,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 4,
      "trip_id": 1,
      "arr": 4665,
      "dep": 4665,
      "stop_id": 3,
      "stop_sequence": 3,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 5,
      "trip_id": 1,
      "arr": 6974,
      "dep": 6974,
      "stop_id": 7,
      "stop_sequence": 7,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 6,
      "trip_id": 1,
      "arr": 5716,
      "dep": 5716,
      "stop_id": 5,
      "stop_sequence": 5,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 7,
      "trip_id": 1,
      "arr": 2892,
      "dep": 2892,
      "stop_id": 1,
      "stop_sequence": 1,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 8,
      "trip_id": 1,
      "arr": 9026,
      "dep": 9026,
      "stop_id": 8,
      "stop_sequence": 8,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 9,
      "trip_id": 1,
      "arr": 13219,
      "dep": 13219,
      "stop_id": 9,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 10,
      "trip_id": 10,
      "arr": 3792,
      "dep": 3792,
      "stop_id": 1,
      "stop_sequence": 8,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 11,
      "trip_id": 13,
      "arr": 10458,
      "dep": 10936,
      "stop_id": 1,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 12,
      "trip_id": 13,
      "arr": 7108,
      "dep": 6280,
      "stop_id": 5,
      "stop_sequence": 4,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 13,
      "trip_id": 14,
      "arr": 7296,
      "dep": 7456,
      "stop_id": 3,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 14,
      "trip_id": 5,
      "arr": 14209,
      "dep": 5215,
      "stop_id": 6,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 15,
      "trip_id": 12,
      "arr": 14149,
      "dep": 7924,
      "stop_id": 1,
      "stop_sequence": 4,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 16,
      "trip_id": 9,
      "arr": 11892,
      "dep": 4100,
      "stop_id": 6,
      "stop_sequence": 5,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 17,
      "trip_id": 15,
      "arr": 7067,
      "dep": 8767,
      "stop_id": 1,
      "stop_sequence": 1,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 18,
      "trip_id": 3,
      "arr": 9756,
      "dep": 9218,
      "stop_id": 8,
      "stop_sequence": 8,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 19,
      "trip_id": 18,
      "arr": 3716,
      "dep": 482,
      "stop_id": 6,
      "stop_sequence": 4,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 20,
      "trip_id": 4,
      "arr": 548,
      "dep": 13641,
      "stop_id": 2,
      "stop_sequence": 5,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 21,
      "trip_id": 1,
      "arr": 13748,
      "dep": 2259,
      "stop_id": 6,
      "stop_sequence": 4,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 22,
      "trip_id": 1,
      "arr": 7206,
      "dep": 3030,
      "stop_id": 5,
      "stop_sequence": 4,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 23,
      "trip_id": 6,
      "arr": 10712,
      "dep": 1922,
      "stop_id": 4,
      "stop_sequence": 6,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 24,
      "trip_id": 2,
      "arr": 3464,
      "dep": 6837,
      "stop_id": 8,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 25,
      "trip_id": 3,
      "arr": 11871,
      "dep": 10879,
      "stop_id": 10,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 26,
      "trip_id": 3,
      "arr": 5640,
      "dep": 11350,
      "stop_id": 3,
      "stop_sequence": 7,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 27,
      "trip_id": 12,
      "arr": 3140,
      "dep": 13553,
      "stop_id": 5,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 28,
      "trip_id": 18,
      "arr": 1746,
      "dep": 6337,
      "stop_id": 6,
      "stop_sequence": 7,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 29,
      "trip_id": 7,
      "arr": 7426,
      "dep": 2623,
      "stop_id": 6,
      "stop_sequence": 5,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 30,
      "trip_id": 15,
      "arr": 7462,
      "dep": 7633,
      "stop_id": 3,
      "stop_sequence": 7,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 31,
      "trip_id": 9,
      "arr": 6317,
      "dep": 2931,
      "stop_id": 5,
      "stop_sequence": 2,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 32,
      "trip_id": 18,
      "arr": 13570,
      "dep": 4598,
      "stop_id": 4,
      "stop_sequence": 9,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 33,
      "trip_id": 17,
      "arr": 13788,
      "dep": 5198,
      "stop_id": 6,
      "stop_sequence": 10,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 34,
      "trip_id": 12,
      "arr": 7118,
      "dep": 11945,
      "stop_id": 4,
      "stop_sequence": 10,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 35,
      "trip_id": 13,
      "arr": 10135,
      "dep": 1150,
      "stop_id": 3,
      "stop_sequence": 3,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 36,
      "trip_id": 10,
      "arr": 10567,
      "dep": 7882,
      "stop_id": 6,
      "stop_sequence": 3,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 37,
      "trip_id": 11,
      "arr": 12419,
      "dep": 924,
      "stop_id": 2,
      "stop_sequence": 8,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 38,
      "trip_id": 9,
      "arr": 4537,
      "dep": 3308,
      "stop_id": 4,
      "stop_sequence": 3,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 39,
      "trip_id": 15,
      "arr": 5145,
      "dep": 12846,
      "stop_id": 8,
      "stop_sequence": 10,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 40,
      "trip_id": 17,
      "arr": 6993,
      "dep": 5435,
      "stop_id": 5,
      "stop_sequence": 1,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 41,
      "trip_id": 14,
      "arr": 5050,
      "dep": 5648,
      "stop_id": 8,
      "stop_sequence": 3,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 42,
      "trip_id": 12,
      "arr": 8503,
      "dep": 1422,
      "stop_id": 9,
      "stop_sequence": 8,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 43,
      "trip_id": 4,
      "arr": 7565,
      "dep": 3012,
      "stop_id": 5,
      "stop_sequence": 2,
      "is_terminus": true,
      "is_head": false
    },
    {
      "_id": 44,
      "trip_id": 8,
      "arr": 3980,
      "dep": 5437,
      "stop_id": 7,
      "stop_sequence": 5,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 45,
      "trip_id": 8,
      "arr": 11373,
      "dep": 4886,
      "stop_id": 8,
      "stop_sequence": 6,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 46,
      "trip_id": 6,
      "arr": 8263,
      "dep": 2396,
      "stop_id": 10,
      "stop_sequence": 2,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 47,
      "trip_id": 7,
      "arr": 4172,
      "dep": 1132,
      "stop_id": 3,
      "stop_sequence": 8,
      "is_terminus": true,
      "is_head": true
    },
    {
      "_id": 48,
      "trip_id": 13,
      "arr": 1710,
      "dep": 661,
      "stop_id": 2,
      "stop_sequence": 3,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 49,
      "trip_id": 8,
      "arr": 4453,
      "dep": 14042,
      "stop_id": 7,
      "stop_sequence": 9,
      "is_terminus": false,
      "is_head": false
    },
    {
      "_id": 50,
      "trip_id": 14,
      "arr": 7146,
      "dep": 2950,
      "stop_id": 1,
      "stop_sequence": 2,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 51,
      "trip_id": 4,
      "arr": 5923,
      "dep": 10093,
      "stop_id": 9,
      "stop_sequence": 5,
      "is_terminus": false,
      "is_head": true
    },
    {
      "_id": 52,
      "trip_id": 11,
      "arr": 4159,
      "dep": 4560,
      "stop_id": 9,
      "stop_sequence": 1,
      "is_terminus": false,
      "is_head": true
    }
  ];
}