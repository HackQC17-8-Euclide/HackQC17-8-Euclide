import { Stops } from './Stops'
import { Stops_times } from './Stops_times'
import { Stop_time } from './Stops_times'

export class Pos {
    public lat: number;
    public long: number;

    moy(p: Pos) {
        var pos = new Pos();
        pos.lat = 1 / 2 * (this.lat + pos.lat);
        pos.long = 1 / 2 * (this.long + pos.long);
        return pos;
    }
}


export class pos_bus {

    public static get_pos_bus(temps_actuel: number): Pos[] {
        //Stops.compute_formatted_stops();
        //Stops_times.compute_formatted_stop_times();
        // console.log('Stops_times.formatted_stop_times', Stops_times.formatted_stop_times);
        let Hash_pred = new Array<Stop_time>();
        //calcul du dernier arrêt du bus
        for (var stop_time of Stops_times.formatted_stop_times) {            
            if (stop_time.arr < temps_actuel && Hash_pred[stop_time.trip_id] == null)
                Hash_pred[stop_time.trip_id] = stop_time;
            else if (stop_time.arr < temps_actuel && stop_time.arr > Hash_pred[stop_time.trip_id].arr)
                Hash_pred[stop_time.trip_id] = stop_time;
        }
        console.log('Hash_pred', Hash_pred);
        

        //calcul de la position des bus
        let positions = new Array<Pos>();
        for (var stop_ref of Hash_pred) {
            if (stop_ref !== undefined && stop_ref.succ !== undefined && stop_ref.succ !== null) {
                
                //console.log('stop_ref');
                var lat1 = stop_ref.stop.lat;
                var long1 = stop_ref.stop.long;
                var lat2 = stop_ref.succ.stop.lat;
                var long2 = stop_ref.succ.stop.long;
                var b = (temps_actuel - stop_ref.dep) / (stop_ref.succ.arr - stop_ref.dep);
                positions[stop_ref.trip_id] = new Pos();
                positions[stop_ref.trip_id].lat = lat1 * (1 - b) + lat2 * (b);
                positions[stop_ref.trip_id].long = long1 * (1 - b) + long2 * (b);

            }
        }
        return positions;

    }
}