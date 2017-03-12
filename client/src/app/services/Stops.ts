import {Stop_time} from'./Stops_times'
import {Pos} from './pos_bus'

export class Stop{
  id:number;
  pos:Pos;
  lat:number;
  long:number;
  times:Stop_time[]= new Array <Stop_time>();

  constructor(id:number,lat:number,long:number){
    this.id=id;
    this.pos = new Pos();
    this.pos.lat=lat;
    this.pos.long=long;
    this.lat=lat;
    this.long=long;
  }

}


export class Stops{
  static formatted_stops:Stop[] = new Array<Stop>();
  static stops=[];



static compute_formatted_stops(){
    for (var i=0;i<Stops.stops.length;i++){
      Stops.formatted_stops[i]=new Stop(Stops.stops[i]._id,Stops.stops[i].lat,Stops.stops[i].long);
    }
  }
}