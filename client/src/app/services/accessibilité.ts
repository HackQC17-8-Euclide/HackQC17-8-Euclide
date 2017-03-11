import { Stops } from './Stops'
import { Stops_times } from './Stops_times'
import { Pos } from './pos_bus'

class couple<X,Y>{
    arg1:X;
    arg2:Y;
}

export class accessibilité{
    ma_pos: Pos;
    stop_dist:number[];
    accessible_stop:boolean[];
    
    accessibilité(pos:Pos){
        this.ma_pos=pos;
    }

    compute_limit_distance(vit_km_h:number, time_sec:number){
        //renvoie la distance en km parcourrue en un temps t
        return vit_km_h*time_sec/3600;
    }

    static compute_accessible_stops(limit_dist:number, lat:number, long:number):couple<number[],boolean[]>{
        //calcule pour chaque arrêt s'il est atteingnable à pied et sa distance à l'utilisateur
        var tab = Stops.formatted_stops;
        for (var i=0;i<tab.length;i++){
            var d = this.distance(tab[i].lat,tab[i].long);
            var stop_dist:number[];
            var accessible_stop:boolean[];
            stop_dist[tab[i].id]=d;
            accessible_stop[tab[i].id]=d<limit_dist;
        }  
        var c = new couple();
        c.arg1=stop_dist;
        c.arg2=accessible_stop;
        return c;
    }

    static distance(lat:number, long:number):number{
        //calcule la distance entre une stations et ma position
        //retourne le résultat en km
        var R = 6378;
        var x= (lat)*R*Math.PI/180;
        var y= (long)*R*Math.PI*Math.cos(this.ma_pos.lat*Math.PI/180)/180;

        var mon_x= (this.ma_pos.lat)*R*Math.PI/180;
        var mon_y= (this.ma_pos.long)*R*Math.PI*Math.cos(this.ma_pos.lat*Math.PI/180)/180;
        return Math.sqrt(Math.pow(mon_x-x,2)+Math.pow(mon_y-y,2));
    }
}