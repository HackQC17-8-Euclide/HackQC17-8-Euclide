import { Stop } from './Stops';
import { Stops } from './Stops';

export class Stop_time {
  trip_id: number;
  arr: number;
  dep: number;
  stop_id: number;
  stop_sequence: number;
  pred: Stop_time;
  succ: Stop_time;
  stop: Stop;


  constructor(trip_id, arr, dep, stop_id, stop_sequence) {
    this.trip_id = trip_id;
    this.arr = arr;
    this.dep = dep;
    this.stop_id = stop_id;
    this.stop_sequence = stop_sequence;
    this.pred = null;
    this.succ = null;
    this.stop = null;
  }

}

export class Stops_times {
  static formatted_stop_times: Stop_time[] = new Array<Stop_time>();

  static stops_times =  [{"stop_id":13673,"trip_id":38463,"arr":74744,"dep":74744,"stop_sequence":30},
 {"stop_id":13677,"trip_id":38463,"arr":74437,"dep":74437,"stop_sequence":22},
 {"stop_id":13695,"trip_id":38463,"arr":74121,"dep":74121,"stop_sequence":9},
 {"stop_id":13700,"trip_id":38463,"arr":74662,"dep":74662,"stop_sequence":28},
 {"stop_id":13766,"trip_id":38463,"arr":74391,"dep":74391,"stop_sequence":20},
 {"stop_id":13770,"trip_id":38463,"arr":74506,"dep":74506,"stop_sequence":24},
 {"stop_id":13895,"trip_id":38463,"arr":74635,"dep":74635,"stop_sequence":27},
 {"stop_id":14052,"trip_id":38463,"arr":73620,"dep":73860,"stop_sequence":1},
 {"stop_id":14178,"trip_id":38463,"arr":74100,"dep":74100,"stop_sequence":8},
 {"stop_id":14365,"trip_id":38463,"arr":74580,"dep":74580,"stop_sequence":25},
 {"stop_id":14490,"trip_id":38463,"arr":73944,"dep":73944,"stop_sequence":2},
 {"stop_id":14491,"trip_id":38463,"arr":73963,"dep":73963,"stop_sequence":3},
 {"stop_id":14492,"trip_id":38463,"arr":73980,"dep":73980,"stop_sequence":4},
 {"stop_id":14524,"trip_id":38463,"arr":74940,"dep":74940,"stop_sequence":33},
 {"stop_id":14538,"trip_id":38463,"arr":74820,"dep":74820,"stop_sequence":32},
 {"stop_id":14563,"trip_id":38463,"arr":74060,"dep":74060,"stop_sequence":7},
 {"stop_id":14585,"trip_id":38463,"arr":74142,"dep":74142,"stop_sequence":10},
 {"stop_id":14589,"trip_id":38463,"arr":74002,"dep":74002,"stop_sequence":5},
 {"stop_id":14590,"trip_id":38463,"arr":74025,"dep":74025,"stop_sequence":6},
 {"stop_id":14593,"trip_id":38463,"arr":74170,"dep":74170,"stop_sequence":11},
 {"stop_id":14604,"trip_id":38463,"arr":74199,"dep":74199,"stop_sequence":12},
 {"stop_id":14613,"trip_id":38463,"arr":74220,"dep":74220,"stop_sequence":13},
 {"stop_id":14621,"trip_id":38463,"arr":74245,"dep":74245,"stop_sequence":14},
 {"stop_id":14629,"trip_id":38463,"arr":74272,"dep":74272,"stop_sequence":15},
 {"stop_id":14634,"trip_id":38463,"arr":74296,"dep":74296,"stop_sequence":16},
 {"stop_id":14640,"trip_id":38463,"arr":74311,"dep":74311,"stop_sequence":17},
 {"stop_id":14643,"trip_id":38463,"arr":74340,"dep":74340,"stop_sequence":18},
 {"stop_id":14651,"trip_id":38463,"arr":74373,"dep":74373,"stop_sequence":19},
 {"stop_id":14657,"trip_id":38463,"arr":74412,"dep":74412,"stop_sequence":21},
 {"stop_id":14664,"trip_id":38463,"arr":74460,"dep":74460,"stop_sequence":23},
 {"stop_id":14917,"trip_id":38463,"arr":74616,"dep":74616,"stop_sequence":26},
 {"stop_id":14918,"trip_id":38463,"arr":74704,"dep":74704,"stop_sequence":29},
 {"stop_id":14919,"trip_id":38463,"arr":74768,"dep":74768,"stop_sequence":31},
 //{"stop_id":13661,"trip_id":38464,"arr":78191,"dep":78191,"stop_sequence":67},
 {"stop_id":13747,"trip_id":38464,"arr":75895,"dep":75895,"stop_sequence":11},
 {"stop_id":13831,"trip_id":38464,"arr":76260,"dep":76260,"stop_sequence":25},
 {"stop_id":13835,"trip_id":38464,"arr":75660,"dep":75660,"stop_sequence":2},
 {"stop_id":13848,"trip_id":38464,"arr":75669,"dep":75669,"stop_sequence":3},
 {"stop_id":13973,"trip_id":38464,"arr":76346,"dep":76346,"stop_sequence":29},
 {"stop_id":13994,"trip_id":38464,"arr":77746,"dep":77746,"stop_sequence":54},
 {"stop_id":14056,"trip_id":38464,"arr":76980,"dep":77220,"stop_sequence":42},
 {"stop_id":14062,"trip_id":38464,"arr":77245,"dep":77245,"stop_sequence":43},
 {"stop_id":14087,"trip_id":38464,"arr":77687,"dep":77687,"stop_sequence":51},
 {"stop_id":14088,"trip_id":38464,"arr":77723,"dep":77723,"stop_sequence":53},
 {"stop_id":14090,"trip_id":38464,"arr":77769,"dep":77769,"stop_sequence":55},
 {"stop_id":14091,"trip_id":38464,"arr":77852,"dep":77852,"stop_sequence":58},
 {"stop_id":14092,"trip_id":38464,"arr":77872,"dep":77872,"stop_sequence":59},
 {"stop_id":14093,"trip_id":38464,"arr":78075,"dep":78075,"stop_sequence":64},
 {"stop_id":14094,"trip_id":38464,"arr":78093,"dep":78093,"stop_sequence":65},
 {"stop_id":14095,"trip_id":38464,"arr":78130,"dep":78130,"stop_sequence":66},
 {"stop_id":14130,"trip_id":38464,"arr":77276,"dep":77276,"stop_sequence":44},
 //{"stop_id":14291,"trip_id":38464,"arr":78240,"dep":78240,"stop_sequence":68},
 {"stop_id":14296,"trip_id":38464,"arr":76220,"dep":76220,"stop_sequence":23},
 {"stop_id":14312,"trip_id":38464,"arr":77709,"dep":77709,"stop_sequence":52},
 {"stop_id":14501,"trip_id":38464,"arr":76192,"dep":76192,"stop_sequence":21},
 {"stop_id":14522,"trip_id":38464,"arr":75764,"dep":75764,"stop_sequence":6},
 {"stop_id":14525,"trip_id":38464,"arr":74940,"dep":75600,"stop_sequence":1},
 {"stop_id":14539,"trip_id":38464,"arr":75720,"dep":75720,"stop_sequence":4},
 {"stop_id":14540,"trip_id":38464,"arr":75732,"dep":75732,"stop_sequence":5},
 {"stop_id":14541,"trip_id":38464,"arr":75815,"dep":75815,"stop_sequence":8},
 {"stop_id":15048,"trip_id":38464,"arr":75802,"dep":75802,"stop_sequence":7},
 {"stop_id":14542,"trip_id":38464,"arr":75840,"dep":75840,"stop_sequence":9},
 {"stop_id":14543,"trip_id":38464,"arr":75855,"dep":75855,"stop_sequence":10},
 {"stop_id":14544,"trip_id":38464,"arr":75926,"dep":75926,"stop_sequence":12},
 {"stop_id":14545,"trip_id":38464,"arr":75955,"dep":75955,"stop_sequence":13},
 {"stop_id":14546,"trip_id":38464,"arr":75970,"dep":75970,"stop_sequence":14},
 {"stop_id":14547,"trip_id":38464,"arr":75987,"dep":75987,"stop_sequence":15},
 {"stop_id":14548,"trip_id":38464,"arr":76011,"dep":76011,"stop_sequence":16},
 {"stop_id":14549,"trip_id":38464,"arr":76054,"dep":76054,"stop_sequence":17},
 {"stop_id":14550,"trip_id":38464,"arr":76080,"dep":76080,"stop_sequence":18},
 {"stop_id":14551,"trip_id":38464,"arr":76152,"dep":76152,"stop_sequence":19},
 {"stop_id":14552,"trip_id":38464,"arr":76180,"dep":76180,"stop_sequence":20},
 {"stop_id":14553,"trip_id":38464,"arr":76207,"dep":76207,"stop_sequence":22},
 {"stop_id":14554,"trip_id":38464,"arr":76238,"dep":76238,"stop_sequence":24},
 {"stop_id":14555,"trip_id":38464,"arr":76276,"dep":76276,"stop_sequence":26},
 {"stop_id":14556,"trip_id":38464,"arr":76316,"dep":76316,"stop_sequence":27},
 {"stop_id":14557,"trip_id":38464,"arr":76330,"dep":76330,"stop_sequence":28},
 {"stop_id":14560,"trip_id":38464,"arr":76382,"dep":76382,"stop_sequence":30},
 {"stop_id":14561,"trip_id":38464,"arr":76409,"dep":76409,"stop_sequence":31},
 {"stop_id":14562,"trip_id":38464,"arr":76440,"dep":76440,"stop_sequence":32},
 {"stop_id":14564,"trip_id":38464,"arr":76482,"dep":76482,"stop_sequence":33},
 {"stop_id":14565,"trip_id":38464,"arr":76540,"dep":76540,"stop_sequence":34},
 {"stop_id":14566,"trip_id":38464,"arr":76576,"dep":76576,"stop_sequence":35},
 {"stop_id":14567,"trip_id":38464,"arr":76620,"dep":76620,"stop_sequence":36},
 {"stop_id":14568,"trip_id":38464,"arr":76657,"dep":76657,"stop_sequence":37},
 {"stop_id":14569,"trip_id":38464,"arr":76687,"dep":76687,"stop_sequence":38},
 {"stop_id":14570,"trip_id":38464,"arr":76740,"dep":76740,"stop_sequence":39},
 {"stop_id":14571,"trip_id":38464,"arr":76802,"dep":76802,"stop_sequence":40},
 {"stop_id":14572,"trip_id":38464,"arr":76854,"dep":76854,"stop_sequence":41},
 {"stop_id":14623,"trip_id":38464,"arr":77798,"dep":77798,"stop_sequence":56},
 {"stop_id":14624,"trip_id":38464,"arr":77820,"dep":77820,"stop_sequence":57},
 {"stop_id":14683,"trip_id":38464,"arr":77340,"dep":77340,"stop_sequence":45},
 {"stop_id":14685,"trip_id":38464,"arr":77398,"dep":77398,"stop_sequence":46},
 {"stop_id":14692,"trip_id":38464,"arr":77520,"dep":77580,"stop_sequence":47},
 {"stop_id":14694,"trip_id":38464,"arr":77607,"dep":77607,"stop_sequence":48},
 {"stop_id":14759,"trip_id":38464,"arr":77624,"dep":77624,"stop_sequence":49},
 {"stop_id":14761,"trip_id":38464,"arr":77650,"dep":77650,"stop_sequence":50},
 {"stop_id":14852,"trip_id":38464,"arr":77917,"dep":77917,"stop_sequence":60},
 {"stop_id":14925,"trip_id":38464,"arr":77940,"dep":77940,"stop_sequence":61},
 {"stop_id":14926,"trip_id":38464,"arr":77975,"dep":77975,"stop_sequence":62},
 {"stop_id":14927,"trip_id":38464,"arr":78025,"dep":78025,"stop_sequence":63}]

  static compute_formatted_stop_times() {
    for (var i = 0; i < Stops_times.stops_times.length; i++) {
      var a = Stops_times.stops_times[i];
      Stops_times.formatted_stop_times[i] = new Stop_time(a.trip_id, a.arr, a.dep, a.stop_id, a.stop_sequence);
    }
    for (var i = 0; i < Stops_times.stops_times.length; i++) {
      for (var j = 0; j < Stops.formatted_stops.length; j++) {
        if (Stops.formatted_stops[j].id == Stops_times.stops_times[i].stop_id) {
          Stops_times.formatted_stop_times[i].stop = Stops.formatted_stops[j];
          Stops.formatted_stops[j].times[Stops.formatted_stops[j].times.length] = Stops_times.formatted_stop_times[i];
          break;
        }
      }

    }

    Stops_times.build_trips();
  }


  static build_trips() {
    for (var i = 0; i < Stops_times.formatted_stop_times.length; i++) {
      var k = 0;
      for (var j = i; j < Stops_times.formatted_stop_times.length; j++) {
        if (Stops_times.formatted_stop_times[i].trip_id == Stops_times.formatted_stop_times[j].trip_id) {
          if (Stops_times.formatted_stop_times[i].stop_sequence == Stops_times.formatted_stop_times[j].stop_sequence + 1) {
            k++;
            Stops_times.formatted_stop_times[i].pred = Stops_times.formatted_stop_times[j];
            Stops_times.formatted_stop_times[j].succ = Stops_times.formatted_stop_times[i];
          }
          if (Stops_times.formatted_stop_times[j].stop_sequence == Stops_times.formatted_stop_times[i].stop_sequence + 1) {
            k++;
            Stops_times.formatted_stop_times[j].pred = Stops_times.formatted_stop_times[i];
            Stops_times.formatted_stop_times[i].succ = Stops_times.formatted_stop_times[j];
          }
          if (k == 2) break;
        }
      }

    }
  }
}
