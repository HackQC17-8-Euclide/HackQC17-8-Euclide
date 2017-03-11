import { Stops } from './Stops'
import { Stops_times } from './Stops_times'
import { Stop_time } from './Stops_times'

export class Pos {
    lat: number;
    long: number;
}

export class trip {
    pred: trip;
    succ: trip;
    id: "58c3782d73f81c56719891b8";
    trip_id: 4;
    arr: 6636;
    dep: 12872;
    stop_id: 4;
    stop_sequence: 8;
    is_terminus: true;
    is_head: true;
}


export class pos_bus {

    public get_pos_bus(temps_actuel): Pos[] {
        Stops.compute_formatted_stops();
        //console.log(Stops.formatted_stops);
        Stops_times.compute_formatted_stop_times();
        let Hash_pred = new Array<Stop_time>();
        let Hash_next = new Array<Stop_time>();
        //calcul du dernier arrêt du bus
        for (var stop_time of Stops_times.formatted_stop_times) {
            if (stop_time.arr < temps_actuel && Hash_pred[stop_time.trip_id] == null)
                Hash_pred[stop_time.trip_id] = stop_time;
            else if (stop_time.arr < temps_actuel && stop_time.arr > Hash_pred[stop_time.trip_id].arr)
                Hash_pred[stop_time.trip_id] = stop_time;
        }
        //calcul de la position des bus
        let positions = new Array<Pos>();
        for (var stop_ref of Hash_pred) {

            //  console.log (stop_ref);
            if (stop_ref !== undefined && stop_ref.succ !== undefined && stop_ref.succ !== null) {
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

        console.log(positions);
        return positions;

    }
}