<?php

class gps_service {

    public function tracker_list() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.navixy.com/v2/tracker/list?hash=8a78dc39f647096f54bcb63056f93506",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 4d2f7304-ef7b-c20f-5def-ce85d2245048"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return $err;
        } else {
            $listado = json_decode($response, TRUE);
            $numero = count($listado['list']);
            for ($i = 0; $i < (int) $numero; $i++) {
                $patente3 = $listado['list'][$i]['label'];
                $patente["data"][] = Load::model('vehiculos')->find_first("conditions: patente='" . $patente3 . "'");
            }
        }
        return $patente;
    }

    public function tracker_list_id() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.navixy.com/v2/tracker/list?hash=8a78dc39f647096f54bcb63056f93506",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 4d2f7304-ef7b-c20f-5def-ce85d2245048"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return $err;
        } else {
            $listado = json_decode($response, TRUE);
            $numero = count($listado['list']);
            $trackerid = array('' => 'Seleccione Patente');
            for ($i = 0; $i < (int) $numero; $i++) {
                $patente = array($listado['list'][$i]['id'] => $listado['list'][$i]['label']);
                $trackerid = $trackerid + $patente;
            }
        }
        return $trackerid;
    }

    public function track_list($tracker_id, $desde, $hasta) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.navixy.com/v2/track/list?hash=8a78dc39f647096f54bcb63056f93506&tracker_id=" . "$tracker_id" . "&from=" . "$desde" . "%2000:00:00&to=" . "$hasta" . "%2023:59:00",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 1baf250a-0c77-e11b-83d5-b7debc0ac97e"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            $listado = json_decode($response, TRUE);
            $numero = count($listado['list']);
            if ($numero == 0) {
                $patente["data"][] = array();
            } else {
                for ($i = 0; $i < (int) $numero; $i++) {
                    $patente["data"][] = $listado['list'][$i];
                }
            }
        }
        return $patente;
    }

    public function track_list_general() {
        $patentes = $this->track_list_local();

        $numero = count($patentes['list']);
        for ($i = 0; $i < (int) $numero; $i++) {
            $listado["data"][] = array('id' => $patentes['list'][$i]['id'], 'patente' => $patentes['list'][$i]['label']);
        }

        return $listado;
    }

    function track_list_local() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.navixy.com/v2/tracker/list?hash=8a78dc39f647096f54bcb63056f93506",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 4d2f7304-ef7b-c20f-5def-ce85d2245048"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return $err;
        } else {
            $listado = json_decode($response, TRUE);
        }
        return $listado;
    }

    public function track_list_ciclo($tracker_id, $desde, $hasta) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.navixy.com/v2/track/list?hash=8a78dc39f647096f54bcb63056f93506&tracker_id=" . "$tracker_id" . "&from=" . "$desde" . "%2000:00:00&to=" . "$hasta" . "%2023:59:00",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 1baf250a-0c77-e11b-83d5-b7debc0ac97e"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

}
