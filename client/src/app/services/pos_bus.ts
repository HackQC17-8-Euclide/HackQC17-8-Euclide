import { Stops } from './Stops'
import { Stops_times } from './Stops_times'

export class Pos {
    lat: number;
    long: number;
}

export class pos_bus {

    public get_pos_bus(temps_actuel): Pos[] {
        let Hash_pred = [];
        for (var stop_time of Stops_times.stops_times) {
            if (stop_time['arr'] < temps_actuel && Hash_pred[stop_time['trip_id']] == null)
                Hash_pred[stop_time['trip_id']] = stop_time;
            else if (stop_time['arr'] < temps_actuel && stop_time['arr'] > Hash_pred[stop_time['trip_id']]['arr'])
                Hash_pred[stop_time['trip_id']] = stop_time;
        }
        let Hash_next = [];
        for (var stop_time of Stops_times.stops_times) {
            if (stop_time['stop_sequence'] == Hash_pred[stop_time['trip_id']]['stop_sequence'] + 1)
                Hash_next[stop_time['trip_id']] = stop_time;
        }
        let positions = new Array<Pos>();
        for (var stop of Hash_pred) {
            var lat1 = stop['lat'];
            var long1 = stop['long'];
            var lat2 = Hash_next[stop['trip_id']]['lat'];
            var long2 = Hash_next[stop['trip_id']]['long'];
            var b = (temps_actuel - stop['dep']) / (Hash_next[stop['trip_id']]['arr'] - stop['dep']);
            positions[stop['trip_id']].lat = lat1 * (1 - b) + lat2 * (b);
            positions[stop['trip_id']].long = long1 * (1 - b) + long2 * (b);

        }

        console.log(positions);
        return positions;
    }
}